<?php

namespace App\Services\Verification;

use App\Domain\DTOs\ApiResponseDto;
use App\Domain\DTOs\Request\Verification\VerificationRequestDto;
use App\Domain\DTOs\Response\Verification\VerificationResponseDto;
use App\Domain\Entities\VerificationEntity;
use App\Domain\Interfaces\Verification\IVerificationService;
use App\enums\HttpStatusCode;
use App\Events\KYCEvent;
use App\Models\User;
use App\Models\Verification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VerificationService implements IVerificationService
{
    protected ?\Illuminate\Contracts\Auth\Authenticatable $_currentUser;
    function __construct()
    {
        $this->_currentUser = Auth::user();
    }
    public function submitVerification(Request $request)
    {
        try {
            $validator =  Validator::make($request->all(), 
                    [
                        'document_type' => 'required|string',
                        'government_id' => 'required|string',
                        'selfie_with_id' => 'required|string',
                        'full_name' => 'required|string|max:255',
                        'date_of_birth' => 'required|date',
                        'current_address' => 'required|string|max:255',
                        'phone_number' => 'required|string|max:20',
                        'email' => 'required|email|max:255',
                        'nationality' => 'required|string|max:100',
                        'id_document_number' => 'required|string|max:100',
             ]);
            if($validator->fails()){
                Log::info(" validator errors ", $validator->errors()->toArray());
                return new ApiResponseDto(false, "validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            $data = $validator->validated();
            $dto = new VerificationRequestDto($data);

            // Check for existing verification with similar data
            $existingVerification = Verification::where('email', $dto->email)
                ->orWhere('phone_number', $dto->phone_number)
                ->orWhere('id_document_number', $dto->id_document_number)
                ->first();

            if ($existingVerification) {
                return new ApiResponseDto(false, "A verification record with similar data already exists. Please use one account for your verification. ", HttpStatusCode::CONFLICT);
            }

            $verification = new Verification();
            $verification->user_id = $this->_currentUser->id;
            $verification->document_type = $dto->document_type;
            $verification->government_id = $dto->government_id;
            $verification->selfie_with_id = $dto->selfie_with_id;
            $verification->full_name = $dto->full_name;
            $verification->date_of_birth = $dto->date_of_birth;
            $verification->current_address = $dto->current_address;
            $verification->phone_number = $dto->phone_number;
            $verification->email = $dto->email;
            $verification->nationality = $dto->nationality;
            $verification->id_document_number = $dto->id_document_number;
            $verification->created_at = Carbon::now();
            $verification->updated_at = null;
            $verification->status = "processing";
            $verification->save();

            return new ApiResponseDto(true, "Your Verification Data have been submitted successfully and is undergoing review, always check for your email for response", HttpStatusCode::OK);
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }


    }

    public function getSubmittedVerifications(): ApiResponseDto
    {

        try {
            $data = Verification::all();
            if($data->isNotEmpty()){
                $dto = $data->map(function($d){
                    $verificationEntity = new VerificationEntity($d);
                    return new VerificationResponseDto($verificationEntity);
                });
                return new ApiResponseDto(true, "Data fetched", HttpStatusCode::OK, $dto->toArray());

            }else{
                return new ApiResponseDto(false, 'Not found', HttpStatusCode::NOT_FOUND);
            }
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function getSubmittedVerificationById(int $id): ApiResponseDto
    {
        try {
            $verification = Verification::findOrFail($id);
            if($verification != null){
                $verificationEntity = new VerificationEntity($verification);
                $verificationDto = new VerificationResponseDto($verificationEntity);
                return new ApiResponseDto(true, "Data fetched", HttpStatusCode::OK, $verificationDto->toArray());
            }else{
                return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getMySubmittedVerification(): ApiResponseDto
    {
        try {
            $verification = Verification::where('user_id', $this->_currentUser->id)->first();
            if($verification != null){
                $verificationEntity = new VerificationEntity($verification);
                $verificationDto = new VerificationResponseDto($verificationEntity);
                return new ApiResponseDto(true, "Data fetched", HttpStatusCode::OK, $verificationDto->toArray());
            }else{
                return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function approve(int $id): ApiResponseDto
    {
        try {
            $verification= Verification::findOrFail($id);
            if($verification != null){
                $verification->setStatus('approved');
                $verification->save();
                $client = User::findOrFail($verification->user_id);
                event(new KYCEvent($client, $verification));
                return new ApiResponseDto(true, "Verification approved successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function reject(int $id): ApiResponseDto
    {
        try {
            $verification= Verification::findOrFail($id);
            if($verification != null){
                $verification->setStatus('rejected');
                $verification->save();
                $client = User::findOrFail($verification->user_id);
                event(new KYCEvent($client, $verification));
                return new ApiResponseDto(true, "Verification rejected successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteVerificaiton(int $id): ApiResponseDto
    {
        try {
            $verification= Verification::findOrFail($id);
            if($verification != null){
                $verification->delete();
                $verification->save();
                return new ApiResponseDto(true, "Verification deleted successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


}
