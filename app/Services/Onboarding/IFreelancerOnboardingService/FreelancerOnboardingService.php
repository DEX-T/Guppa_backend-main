<?php

namespace App\Services\Onboarding\IFreelancerOnboardingService;

use App\Domain\DTOs\ApiResponseDto;
use App\Domain\DTOs\Request\Onboarding\FreelancerOnboardingRequestDto;
use App\Domain\Interfaces\Onboarding\IFreelancerOnboardingService;
use App\enums\HttpStatusCode;
use App\Models\FreelancerOnBoarding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FreelancerOnboardingService implements IFreelancerOnboardingService
{
    // Implement your service methods here
    public function onBoard(Request $request)
    {
       try {
        $validator = Validator::make($request->all(), [
            'gigs' => ['required'],
            'years_of_experience' => ['required', 'string'],
            'looking_for' => ['required', 'string'],
            'skills' => ['required'],
            'portfolio_link_website' => ['required', 'url'],
            'language' => ['required', 'string'],
            'short_bio' => ['required', 'min:20', 'max:5000'],
            'hourly_rate' => ['required'],
            'category' => ['required', 'integer']

        ]);

        if($validator->fails()){
            return new ApiResponseDto(false, 'Validation Error', HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
        }
        $validate = $validator->validated();
        $dto = new FreelancerOnboardingRequestDto($validate);
        $user = Auth()->user();

        //check if user have on boarded but due to network error they couldn't complete it
        $onBoarded = FreelancerOnBoarding::where('user_id', $user->id)->first();
        if($onBoarded != null){
            $onBoarded->gigs = $dto->gigs;
            $onBoarded->years_of_experience = $dto->years_of_experience;
            $onBoarded->looking_for = $dto->looking_for;
            $onBoarded->skills = $dto->skills;
            $onBoarded->portfolio_link_website = $dto->portfolio_link_website;
            $onBoarded->language = $dto->language;
            $onBoarded->short_bio = $dto->short_bio;
            $onBoarded->hourly_rate = $dto->hourly_rate;
            $onBoarded->category = $dto->category;
            $onBoarded->save();
        }else {
            $onBoard = new FreelancerOnBoarding();
            $onBoard->user_id = $user->id;
            $onBoard->gigs = $dto->gigs;
            $onBoard->years_of_experience = $dto->years_of_experience;
            $onBoard->looking_for = $dto->looking_for;
            $onBoard->skills = $dto->skills;
            $onBoard->portfolio_link_website = $dto->portfolio_link_website;
            $onBoard->language = $dto->language;
            $onBoard->short_bio = $dto->short_bio;
            $onBoard->hourly_rate = $dto->hourly_rate;
            $onBoard->category = $dto->category;
            $onBoard->save();

            return new ApiResponseDto(true, "On boarding completed", HttpStatusCode::OK);
        }
       } catch (\Exception $e) {
        return new ApiResponseDto(false, "Server Error : ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
       }
    }
}
