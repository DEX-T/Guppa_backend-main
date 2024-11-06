<?php

 namespace App\Domain\DTOs\Response\Reports;

use App\Domain\Entities\UserEntity;

class UsersReportResponseDto
{
    public int $id;
    public string $first_name;
    public string $last_name;
    public string $phone_no;
    public string $country;
    public string $email;
    public bool $IsEmailVerified;
    public string $role;
    public string $status;
    public  $gender;
    public  $age_group;
    public $chatId;
    public $user_ratings;
    public $date_registered;


    public function __construct(UserEntity $userEntity){
        $this->id = $userEntity->getUserId();
        $this->first_name = $userEntity->getFirstName();
        $this->last_name = $userEntity->getLastName();
        $this->phone_no = $userEntity->getPhoneNo();
        $this->country = $userEntity->getCountry();
        $this->email = $userEntity->getEmail();
        $this->IsEmailVerified = $userEntity->getIsVerified();
        $this->role = $userEntity->getRole();
        $this->status = $userEntity->getStatus();
        $this->gender = $userEntity->getGender();
        $this->age_group = $userEntity->getAgeGroup();
        $this->chatId = $userEntity->getChatId();
        $this->user_ratings = $userEntity->getUserRatings();
        $this->date_registered = $userEntity->getCreatedAt();
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
            'IsEmailVerified' => $this->IsEmailVerified,
            'role' => $this->role,
            'status' => $this->status,
            'gender' => $this->gender,
            'age_group' => $this->age_group,
            'chat_id' => $this->chatId,
            'user_ratings' => $this->user_ratings,
            'date_registered' => $this->date_registered
        ];
    }

}
