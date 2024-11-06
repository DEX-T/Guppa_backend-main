<?php

namespace App\Http\Controllers\Reviews;

use App\Domain\Interfaces\Reviews\IRateFreelancerService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FreelancerReviewController extends Controller
{
    private IRateFreelancerService $_rateService;

    function __construct(IRateFreelancerService $rateService)
    {
        $this->_rateService = $rateService;
    }

    /**
     * @OA\Get(
     *     path="/api/reviews/freelancer-reviews/{freelancer_id}",
     *     operationId="freelancerReviews",
     *     summary="Get all freelancer reviews",
     *     description="Return all freelancer reveiws",
     *     tags={"Reviews"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *          name="freelancer_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer"),
     *          description="ID of the freelancer"
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function freelancer_reviews(Request $request)
    {
        $application = $this->_rateService->getFreelancerReviews($request->freelancer_id);

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
            'data' => $application->data,
        ], $application->code);
    }

    /**
     * @OA\Post(
     *     path="/api/reviews/rate-freelancer",
     *     operationId="rateFreelancer",
     *     tags={"Reviews"},
     *     security={{"sanctum":{}}},
     *     summary="rate freelancer performance",
     *     description="Leave a review for a freelancer, base on their performace",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"freelancer_id", "rated_by", "rating"},
     *             @OA\Property(property="freelancer_id", type="integer", example=0, description="Freelancer Id"),
     *             @OA\Property(property="rated_by", type="int", example=0, description="rater id"),
     *             @OA\Property(property="rating", type="int", example=5)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="rate submitted",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *     )
     * )
     */
    public function rate_freelancer(Request $request): \Illuminate\Http\JsonResponse
    {
        $status = $this->_rateService->rateFreelancer($request);
        return response()->json([
            'success' => $status->status,
            'message' => $status->message,
            'error' => $status->data
        ], $status->code);
    }

}
