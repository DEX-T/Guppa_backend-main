<?php

 namespace App\Domain\DTOs\Response;

use App\Domain\Entities\UserEntity;
use App\Models\FreelancerOnBoarding;
use App\Models\Verification;

class LoginResponseDto
{
    public int $id;
    public string $first_name;
    public string $last_name;
    public string $phone_no;
    public string $country;
    public string $email;
    public  $token;
    public string $role;
    public bool $isVerified;
    public bool $isOnBoarded;
    public $chatId;
    public $profile_photo;
    public $IsClientVerified;
    public $age_group;
    public $gender;
    public $Is2FaActive;
    public $Is2FaVerified;


    public function __construct(UserEntity $user,  $token = null) {
        $this->id = $user->getUserId();
        $this->first_name = $user->getFirstName();
        $this->last_name = $user->getLastName();
        $this->phone_no = $user->getPhoneNo();
        $this->country = $user->getCountry();
        $this->email = $user->getEmail();
        $this->token = $token;
        $this->isVerified = $user->getIsVerified();
        $this->role = $user->getRole();
        $this->isOnBoarded = $this->IsOnBoarded();
        $this->chatId = $user->getChatId();
        $this->profile_photo = $user->getProfilePic();
        $this->IsClientVerified = $this->IsClientVerified($user->getUserId());
        $this->age_group = $user->getAgeGroup();
        $this->gender = $user->getGender();
        $this->Is2FaActive = $user->get2FaActive();
        $this->Is2FaVerified = $user->getIs2FaVerified();
    }

    public function IsClientVerified($clientId){
        $verified = Verification::where(['user_id' => $clientId, 'status' => 'approved'])->first();
        if($verified != null){
            return true;
        }
        return false;
    }
    public function IsOnBoarded(){
        $onBoarded = FreelancerOnBoarding::where('user_id', $this->id)->first();
        if($onBoarded != null){
            return true;
        }
        return false;
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone_no' => $this->phone_no,
            'email' => $this->email,
            'country' => $this->country,
            'age_group' => $this->age_group,
            'gender' => $this->gender,
            'IsEmailVerified' => $this->isVerified,
            'role' => $this->role,
            'token' => $this->token ?? null,
            'IsOnBoarded' => $this->isOnBoarded,
            'chat_id' => $this->chatId,
            'profile_photo' => $this->profile_photo,
            'is_client_verified' => $this->IsClientVerified,
            'Is2FaActive' => $this->Is2FaActive,
            'Is2FaVerified' => $this->Is2FaVerified,

        ];
    }
    // Define your DTO properties and methods here
}
