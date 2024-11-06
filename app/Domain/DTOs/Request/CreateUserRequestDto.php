<?php

namespace App\Domain\DTOs\Request;

class CreateUserRequestDto
{

    public string $first_name;
    public string $last_name;
    public string $phone_no;
    public string $country;
    public string $email;
    public string $role;


    public function __construct(string $first_name, string $last_name, string $phone_no, string $country, string $email, string $role) {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->phone_no = $phone_no;
        $this->country = $country;
        $this->email = $email;
        $this->role = $role;
    }
}
