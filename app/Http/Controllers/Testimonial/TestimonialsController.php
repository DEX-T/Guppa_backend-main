<?php

namespace App\Http\Controllers\Testimonial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Interfaces\Testimonial\ITestimonialsService;


class TestimonialsController extends Controller
{

    private ITestimonialsService $testimonialsService;
    public function __construct(ITestimonialsService $testimonialsService) {
        $this->testimonialsService = $testimonialsService;
    }



    /**
     * @OA\Get(
     *     path="/api/testimonial/getAllTestimonials",
     *     tags={"Testimonial"},
     *     security={{"sanctum":{}}},
     *     summary="Get all Testimonials",
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
    public function getAllTestimonials()
    {
        $dto = $this->testimonialsService->getAllTestimonials();

        return response()->json([
            'success' =>  $dto->status,
            'message' =>  $dto->message,
            'data' =>     $dto->data
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/testimonial/getTestimonial/{id}",
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
    public function getTestimonial($id)
    {
        $dto = $this->testimonialsService->getTestimonial($id);
        return response()->json([
            'success' =>  $dto->status,
            'message' =>  $dto->message,
            'data' =>     $dto->data
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/testimonial/createTestimonial",
     *     tags={"Testimonial"},
     *     summary="Create a new Testimonial",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description"},
     *             @OA\Property(property="title", type="string", example="string"),
     *             @OA\Property(property="description", type="string", example="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Testimonial added successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error creating Testimonial"
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
    public function createTestimonial(Request $request)
    {
        $testimonial = $this->testimonialsService->createTestimonial($request);
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
     *     path="/api/testimonial/updateTestimonial/{id}",
     *     tags={"Testimonial"},
     *     summary="Update a Testimonial",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description"},
     *             @OA\Property(property="title", type="string", example="string"),
     *             @OA\Property(property="description", type="string", example="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Testimonial updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Testimonial does not exist"
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
    public function updateTestimonial(Request $request, $id)
    {
        $update = $this->testimonialsService->updateTestimonial($request, $id);
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
     *     path="/api/testimonial/deleteTestimonial/{id}",
     *     tags={"Testimonial"},
     *     summary="Delete a Testimonial",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Testimonial deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Testimonial not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function deleteTestimonial($id)
    {
        $delete = $this->testimonialsService->deleteTestimonial($id);
        return response()->json([
            'success' => true,
            'message' => $delete->message,
        ], $delete->code);

    }


}
