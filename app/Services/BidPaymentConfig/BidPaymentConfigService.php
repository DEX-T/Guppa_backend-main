<?php

namespace App\Services\BidPaymentConfig;

use App\Domain\DTOs\Request\BidPaymentConfig\BidPaymentConfigRequestDto;
use App\Domain\DTOs\Request\BidPaymentConfig\UpdateBidPaymentConfigRequestDto;
use App\Domain\DTOs\Response\BidPaymentConfig\BidPaymentConfigResponseDto;
use App\Domain\Entities\BidPaymentConfig\BidPaymentConfigEntity;
use App\Domain\Interfaces\BidPaymentConfig\IBidPaymentConfigService;
use App\Models\BidPaymentConfig;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Domain\DTOs\ApiResponseDto;
use Illuminate\Support\Facades\Validator;

class BidPaymentConfigService implements IBidPaymentConfigService
{

    public function createBidPaymentConfig(Request $request): ApiResponseDto
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount' => ['required','integer']
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validated = $validator->validated();
            $dto = new BidPaymentConfigRequestDto($validated['amount']);
            $config = BidPaymentConfig::first();
            if($config == null):
                $config = new BidPaymentConfig();
                $config->id = 1;
                $config->amount = $dto->amount;
                $config->created_at = Carbon::now();
                $message = "Config Created";
            else:
                $config->amount = $dto->amount;
                $config->updated_at = Carbon::now();
                $message = "Config Updated";
            endif;
            if ($config->save()) {
                return new ApiResponseDto(true, $message, HttpStatusCode::OK);
            } else {
                return new ApiResponseDto(false, "Error creating or updating Bid Payment Config", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function getBidPaymentConfig(): ApiResponseDto
    {
        try {
            $bidpaymentconfig = BidPaymentConfig::first();

            if ($bidpaymentconfig == null) {
                return new ApiResponseDto(false, "Bid Payment Config not found", HttpStatusCode::NOT_FOUND);
            }
            $bidpaymentconfigEntity = new BidPaymentConfigEntity($bidpaymentconfig);
            $dto = new BidPaymentConfigResponseDto($bidpaymentconfigEntity);
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }





}
