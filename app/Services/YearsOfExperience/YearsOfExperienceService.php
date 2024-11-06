<?php

namespace App\Services\YearsOfExperience;

use App\Domain\DTOs\Request\YearsOfExperience\YearsOfExperienceRequestDto;
use App\Domain\DTOs\Request\YearsOfExperience\UpdateYearsOfExperienceRequestDto;
use App\Domain\DTOs\Response\YearsOfExperience\YearsOfExperienceResponseDto;
use App\Domain\Entities\YearsOfExperience\YearsOfExperienceEntity;
use App\Domain\Interfaces\YearsOfExperience\IYearsOfExperienceService;
use App\Models\YearOfExperience;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Domain\DTOs\ApiResponseDto;
use Illuminate\Support\Facades\Validator;

class YearsOfExperienceService implements IYearsOfExperienceService
{

    public function createYearsOfExperience(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'yearOfExperience' => ['required'],

            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            $validated = $validator->validated();
            $dto = new YearsOfExperienceRequestDto($validated['yearOfExperience']);
            $yearofexperience = new YearOfExperience();
            $yearofexperience->year_of_experience = $dto->yearOfExperience;
            $yearofexperience->status = "active";
            if ($yearofexperience->save()) {
                //return response
                return new ApiResponseDto(true, "Year of Experience created successfully", HttpStatusCode::CREATED);
            }else{
                return new ApiResponseDto(false, "Error creating Year of Experience", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function getAllYearsOfExperience()
    {
        try {
            $yearofexperience = YearOfExperience::all();

            if ($yearofexperience->isEmpty()) {
                    return new ApiResponseDto(false, "No Year of Experience found", HttpStatusCode::NOT_FOUND);
            }
            $dto = $yearofexperience->map(function($yearsofexperience){
                $YearofExperienceEntity = new YearsOfExperienceEntity($yearsofexperience);
                return new YearsOfExperienceResponseDto($YearofExperienceEntity);
            });
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getYearsOfExperiencebyId(int $id)
    {
        try {
            $yearofexperience = YearOfExperience::findOrFail($id);

            if ($yearofexperience == null) {
                return new ApiResponseDto(false, "Year of Experience not found", HttpStatusCode::NOT_FOUND);
            }
            $yearsofexperienceEntity = new YearsOfExperienceEntity($yearofexperience);
            $dto = new YearsOfExperienceResponseDto($yearsofexperienceEntity);
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function updateYearsOfExperience(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => ['required', 'integer'],
                'yearOfExperience' => ['required', 'string'],

            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validatedData = $validator->validated();

            $dto = new UpdateYearsOfExperienceRequestDto(
                $validatedData['id'],
                $validatedData['yearOfExperience'],
            );

            // 4. Find the Gigs item by ID (from the DTO)
            $yearofexperience = YearOfExperience::findOrFail($dto->id);

            if (!$yearofexperience) {
                return new ApiResponseDto(false, "Year of Experience not found", HttpStatusCode::NOT_FOUND);
            }

            // 5. Update the Year of Experience
            $yearofexperience->update([
                'year_of_experience' => $dto->yearOfExperience,

            ]);

            // 6. Prepare the response DTO
            $experienceEntity = new YearsOfExperienceEntity($yearofexperience);
            $experienceResponseDto = new YearsOfExperienceResponseDto($experienceEntity);

            return new ApiResponseDto(true, "Gigs updated successfully", HttpStatusCode::OK,  $experienceResponseDto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }



    public function deleteYearsOfExperience(int $id)
    {
        try {
            $yearofexperience = YearOfExperience::findOrFail($id);
            if ($yearofexperience) {
                $yearofexperience->delete();
                return new ApiResponseDto(true, "Year of Experience deleted successfully", HttpStatusCode::OK);
            } else {
                return new ApiResponseDto(false, "Year of Experience not found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

}
