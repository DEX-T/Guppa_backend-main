<?php

 namespace App\Domain\DTOs\Request\TwoFA;

class TwoFARequestDto
{
    public string $code;

    public function __construct(int $code){
        $this->code = $code;
    }
    // Define your DTO properties and methods here
}