<?php

namespace App\Http\Controllers\Onboarding;

use App\Domain\Interfaces\Onboarding\IFreelancerOnboardingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FreelancerController extends Controller
{
    //construct
    private IFreelancerOnboardingService $_freelancerOnboard;
    public function __construct(IFreelancerOnboardingService $freelancerOnboard){
            $this->_freelancerOnboard = $freelancerOnboard;
    }

      /**
     * @OA\Post(
     *     path="/api/freelancer_onboarding/onboard",
     *     operationId="onboardFreelancer",
     *     tags={"FreelancerOnboarding"},
     *     summary="On board a freelancer",
     *     description="create a new route",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"gigs", "years_of_experience", "looking_for", "skills", "portfolio", "language", "short_bio", "hourly_rate", "category"},
     *             @OA\Property(property="gigs", type="string", example="string,string,string"),
     *             @OA\Property(property="years_of_experience", type="string", example="string"),
     *             @OA\Property(property="looking_for", type="string", example="string"),
     *             @OA\Property(property="skills", type="string", example="string"),
     *             @OA\Property(property="portfolio_link_website", type="string", example="string"),
     *             @OA\Property(property="language", type="string", example="string"),
     *             @OA\Property(property="short_bio", type="string", example="string"),
     *             @OA\Property(property="hourly_rate", type="string", example="string"),
     *            @OA\Property(property="category", type="integer", example="0"),
     *
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     ),
     *
     * )
     */
    public function onboard(Request $request): \Illuminate\Http\JsonResponse
    {

            $create = $this->_freelancerOnboard->onBoard($request);

            if ($create->status) {
                return response()->json([
                    'success' => true,
                    'message' => $create->message
                ], $create->code);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $create->message,
                    'error' => $create->data
                ], $create->code);


            }

    }
}
