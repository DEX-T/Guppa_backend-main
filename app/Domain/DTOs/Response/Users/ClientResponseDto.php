<?php

namespace App\Domain\DTOs\Response\Users;

use App\Domain\Entities\UserEntity;

class ClientResponseDto
{
    public int $id;
    public string $first_name;
    public string $last_name;
    public string $phone_no;
    public string $country;
    public string $email;
    public bool $isVerified;
    public string $role;

    public function __construct(UserEntity $userEntity){
       $this->id = $userEntity->getUserId();
        $this->first_name = $userEntity->getFirstName();
        $this->last_name = $userEntity->getLastName();
        $this->phone_no = $userEntity->getPhoneNo();
        $this->country = $userEntity->getCountry();
        $this->email = $userEntity->getEmail();
        $this->isVerified = $userEntity->getIsVerified();
        $this->role = $userEntity->getRole();
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone_no' => $this->phone_no,
            'email' => $this->email,
            'country' => $this->country,
            'IsEmailVerified' => $this->isVerified,
            'role' => $this->role
        ];
    }
}
