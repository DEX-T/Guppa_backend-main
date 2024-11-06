<?php

namespace App\Services\Reviews;

use App\Domain\DTOs\ApiResponseDto;
use App\Domain\DTOs\Request\Reviews\RateFreelancerRequestDto;
use App\Domain\DTOs\Response\Reviews\RateFreelancerResponseDto;
use App\Domain\Entities\RateFreelancerEntity;
use App\enums\HttpStatusCode;
use App\Models\FreelancerRating;
use Illuminate\Http\Request;
use App\Domain\Interfaces\Reviews\IRateFreelancerService;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RateFreelancerService  implements IRateFreelancerService
{
    /**
     * @throws ValidationException
     */
    public function rateFreelancer(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'freelancer_id' => ['required', 'integer', 'exists:users,id'],
                'rated_by' => ['required', 'integer', 'exists:users,id'],
                'rating' => ['required', 'integer', 'min:1', 'max:5'],
                'comment' => ['string'],
            ]);
            Log::info("Request validation ", [$validator]);
            if ($validator->fails()){
                Log::error("Request validation error", [$validator]);
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            $validated = $validator->validated();
            $dto = new RateFreelancerRequestDto($validated);
            Log::info("Request dto ", [$dto]);

            $rating = new FreelancerRating();
            $rating->freelancer_id = $dto->freelancer_id;
            $rating->rated_by = $dto->rated_by;
            $rating->rating = $dto->rating;
            $rating->comment = $dto->comment;
            $rating->created_at = Carbon::now();
            $rating->save();
            Log::info("rating created ", [$rating]);

            $freelancer = User::findOrFail($dto->freelancer_id);
            $freelancer->user_ratings = $freelancer->receivedRatings()->avg('rating');
            $freelancer->save();
            Log::info("Freelancer ratings updated ", [$freelancer]);

            return new ApiResponseDto(true, 'freelancer rated', HttpStatusCode::OK);
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getFreelancerReviews(int $freelancerId){
        try {
            $reviews = FreelancerRating::where('freelancer_id', $freelancerId)->get();
            if($reviews->isNotEmpty()){
                $dto = $reviews->map(function ($review){
                    $rateEntity = new RateFreelancerEntity($review);
               return new RateFreelancerResponseDto($rateEntity);
            });
                return new ApiResponseDto(true, "data fetched", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "No review found", HttpStatusCode::NOT_FOUND);
            }
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ", HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
