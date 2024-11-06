<?php

namespace App\Domain\Interfaces\Verification;

use Illuminate\Http\Request;

interface IVerificationService
{
    public function submitVerification(Request $request);
    public function getSubmittedVerifications();
    public function getSubmittedVerificationById(int $id);
    public function getMySubmittedVerification();
    public function approve(int $id);
    public function reject(int $id);
    public function deleteVerificaiton(int $id);

}
