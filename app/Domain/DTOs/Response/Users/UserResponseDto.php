<?php

namespace App\Domain\DTOs\Response\Users;

use App\Domain\Entities\UserEntity;

class UserResponseDto
{
    public int $id;
    public string $first_name;
    public string $last_name;
    public string $phone_no;
    public string $country;
    public string $email;
    public bool $isVerified;
    public string $role;
    public string $status;
    public string $profile_pic;
    public  $gender;
    public  $age_group;
    public $chatId;
    public $user_ratings;
    public $onboarding;
    public $verification_data;
    public $total_gigs;
    public $total_jobs;


    public function __construct(UserEntity $userEntity){
       $this->id = $userEntity->getUserId();
        $this->first_name = $userEntity->getFirstName();
        $this->last_name = $userEntity->getLastName();
        $this->phone_no = $userEntity->getPhoneNo();
        $this->country = $userEntity->getCountry();
        $this->email = $userEntity->getEmail();
        $this->isVerified = $userEntity->getIsVerified();
        $this->role = $userEntity->getRole();
        $this->status = $userEntity->getStatus();
        $this->profile_pic = $userEntity->getProfilePic();
        $this->gender = $userEntity->getGender();
        $this->age_group = $userEntity->getAgeGroup();
        $this->chatId = $userEntity->getChatId();
        $this->user_ratings = $userEntity->getUserRatings();
        $this->onboarding = $userEntity->getOnboardingData();
        $this->verification_data = $userEntity->getVerificationData();
        $this->total_gigs = $userEntity->getTotal_gigs();
        $this->total_jobs = $userEntity->getTotal_jobs();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone_no' => $this->phone_no,
            'email' => $this->email,
            'country' => $this->country,
            'IsEmailVerified' => $this->isVerified,
            'role' => $this->role,
            'status' => $this->status,
            'gender' => $this->gender,
            'age_group' => $this->age_group,
            'chat_id' => $this->chatId,
            'user_ratings' => $this->user_ratings,
            'total_gigs' => $this->total_gigs,
            'total_jobs' => $this->total_jobs,
            'onboarding_data' => $this->onboarding,
            'verification_data' => $this->verification_data,
        ];
    }
}
