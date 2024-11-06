<?php

namespace App\Domain\Interfaces\Authentication;

use App\Domain\DTOs\Request\LoginRequestDto;
use App\Domain\DTOs\Request\RegisterRequestDto;
use App\Domain\Entities\UserEntity;
use Illuminate\Http\Request;

interface IAuthService
{
    // Define your service interface methods here
    public function Register(Request $request);
    public function Login(Request $request);
    public function redirectToFacebook();
    public function handleFacebookCallback();
    public function redirectToGoogle();
    public function handleGoogleCallback();
    public function Logout();
    public function enable2fa();
    public function disable2fa();
    public function verify2fa(Request $request);
    public function prompt();
    public function verify(Request $request);
    public function resendCode();
    public function prompt_email();
    public function verify_email(Request $request);
    public function resendEmailCode();

    public function CreateUser(Request $request);
    public function Onboard(Request $request);

}
