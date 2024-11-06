<?php

 namespace App\Domain\DTOs\Request\PasswordReset;

class PasswordResetRequestDto
{
    public  $email;
    public  $password;
    public  $token;

    public function __construct($email = null,  $token = null, $password= null){
        $this->email = $email;
        $this->password = $password;
        $this->token = $token;
    }
    // Define your DTO properties and methods here
}