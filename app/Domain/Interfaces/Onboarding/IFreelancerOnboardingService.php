<?php

namespace App\Domain\Interfaces\Onboarding;

use Illuminate\Http\Request;

interface IFreelancerOnboardingService
{
   public function onBoard(Request $request);

}
