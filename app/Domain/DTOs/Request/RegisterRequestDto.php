<?php

namespace App\Domain\DTOs\Request;

class RegisterRequestDto
{
    public string $first_name;
    public string $last_name;
    public string $phone_no;
    public string $country;
    public string $email;
    public string $password;
    public string $account_type;
    public bool $agreement_policy;

    public function __construct(string $first_name, string $last_name, string $phone_no, string $country, string $email, string $password, string $account_type, bool $agreement_policy) {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->phone_no = $phone_no;
        $this->country = $country;
        $this->email = $email;
        $this->password = $password;
        $this->account_type = $account_type;
        $this->agreement_policy = $agreement_policy;
    }
}
