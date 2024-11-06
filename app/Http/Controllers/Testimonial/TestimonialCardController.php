<?php

namespace App\Http\Controllers\Testimonial;

use App\Domain\Interfaces\Testimonial\ITestimonialCardService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class TestimonialCardController extends Controller
{
    public ITestimonialCardService $testimonialCardService;
    public function __construct(ITestimonialCardService $testimonialCardService) {
        $this->testimonialCardService = $testimonialCardService;
    }

    /**
     * @OA\Get(
     *     path="/api/testimonial/getAllTestimonialCards",
     *     tags={"Testimonial"},
     *     security={{"sanctum":{}}},
     *     summary="Get all Testimonial cards",
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getAllTestimonialCards()
    {
        $dto = $this->testimonialCardService->getAllTestimonialCards();

        return response()->json([
            'success' =>  $dto->status,
            'message' =>  $dto->message,
            'data' =>     $dto->data
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/testimonial/getTestimonialCard/{id}",
     *     tags={"Testimonial"},
     *     security={{"sanctum":{}}},
     *     summary="Get testimonial by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getTestimonialCard($id)
    {
        $dto = $this->testimonialCardService->gettestimonialcard_id($id);
        return response()->json([
            'success' =>  $dto->status,
            'message' =>  $dto->message,
            'data' =>     $dto->data
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/testimonial/createTestimonialCard",
     *     tags={"Testimonial"},
     *     summary="Create a new Testimonial Card",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"testimonial", "testimonial_id"},
     *             @OA\Property(property="testimonial", type="string", example="string"),
     *             @OA\Property(property="testimonial_id", type="int", example=0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Testimonial card added successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error creating Testimonial card"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function createTestimonialCard(Request $request)
    {
        $testimonial = $this->testimonialCardService->createTestimonialCard($request);
        //dd($testimonial->status);
        if ($testimonial->status) {
            return response()->json([
                'success' => true,
                'message' => $testimonial->message
            ], $testimonial->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $testimonial->message,
                'data' => $testimonial->data
            ], $testimonial->code);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/testimonial/updateTestimonialCard/{id}",
     *     tags={"Testimonial"},
     *     summary="Update a Testimonial card",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"testimonial", "testimonial_card_id", "testimonial_id"},
     *             @OA\Property(property="testimonial", type="string", example="string"),
     *             @OA\Property(property="testimonial_card_id", type="int", example=0),
     *             @OA\Property(property="testimonial_id", type="int", example=0),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Testimonial card updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Testimonial card does not exist"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function updateTestimonialCard(Request $request, $id)
    {
        $update = $this->testimonialCardService->updateTestimonialCard($request, $id);
        if ($update->status) {
            return response()->json([
                'success' => true,
                'message' => $update->message
            ], $update->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $update->message,
                'error' => $update->data
            ], $update->code);


        }
    }

    /**
     * @OA\Delete(
     *     path="/api/testimonial/deleteTestimonialCard/{id}",
     *     tags={"Testimonial"},
     *     summary="Delete a Testimonial card",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Testimonial card deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Testimonial card not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function deleteTestimonialCard($id)
    {
        $delete = $this->testimonialCardService->deleteTestimonialCard($id);
        return response()->json([
            'success' => true,
            'message' => $delete->message,
        ], $delete->code);

    }

    //
}
