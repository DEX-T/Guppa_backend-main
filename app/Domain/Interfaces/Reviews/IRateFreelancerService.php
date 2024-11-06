<?php

namespace App\Domain\Interfaces\Reviews;

use Illuminate\Http\Request;

interface IRateFreelancerService
{
//     1. *Leave Reviews:*
//    - Provide ratings and reviews for freelancers upon project completion.

// 2. *View Freelancer Reviews:* // In Dev
//    - Access and read reviews and ratings left by other clients for freelancers.
    public function rateFreelancer(Request $request);
    public function getFreelancerReviews(int $freelancerId);


}
