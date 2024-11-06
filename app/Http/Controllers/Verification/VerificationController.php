<?php

namespace App\Http\Controllers\Verification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\VerificationRequest;
use App\Domain\Interfaces\Verification\IVerificationService;

class VerificationController extends Controller
{
    protected IVerificationService $verificationService;

    public function __construct(IVerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    /**
     * @OA\Post(
     *     path="/api/verification/submit",
     *     tags={"Verification"},
     *     summary="Submit a verification request",
     *     description="Submits a verification request and checks for existing records with similar data.",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={
     *                 "government_type",
     *                 "government_id",
     *                 "selfie_with_id",
     *                 "full_name",
     *                 "date_of_birth",
     *                 "current_address",
     *                 "phone_number",
     *                 "email",
     *                 "nationality",
     *                 "id_document_number"
     *             },
     *             @OA\Property(property="document_type", type="string", example="passport or Driver's license"),
     *             @OA\Property(property="government_id", type="string", example="file path: 00000/doc.pdf"),
     *             @OA\Property(property="selfie_with_id", type="string", example="file path: 0000/doc.pdf"),
     *             @OA\Property(property="full_name", type="string", example="John Doe"),
     *             @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01"),
     *             @OA\Property(property="current_address", type="string", example="123 Main St, Anytown, USA"),
     *             @OA\Property(property="phone_number", type="string", example="+1234567890"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="nationality", type="string", example="USA"),
     *             @OA\Property(property="id_document_number", type="string", example="78901234")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Your Verification Data have been submitted successfully and is undergoing review, always check for your email for response."),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflict",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="A verification record with similar data already exists. Please use one account for your verification."),
     *             @OA\Property(property="status", type="integer", example=409)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function submitVerification(Request $request): \Illuminate\Http\JsonResponse
    {
        Log::info("verification request ", [$request]);
        // $validated = $request->validated();
        $response = $this->verificationService->submitVerification($request);
        return response()->json( [
                'success' => $response->status,
                'message' => $response->message,
                'data' => $response->data
                ], $response->code);
    }

    /**
     * @OA\Get(
     *     path="/api/verification/getAll",
     *     tags={"Verification"},
     *     summary="Get all submitted verifications",
     *     description="Retrieves all submitted verifications.",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data fetched"),
     *             @OA\Property(property="status", type="integer", example=200),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Not found"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function getSubmittedVerifications(): \Illuminate\Http\JsonResponse
    {
        $response = $this->verificationService->getSubmittedVerifications();
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message,
            'data' => $response->data
        ], $response->code);
    }

    /**
     * @OA\Get(
     *     path="/api/verification/get-verification-id/{id}",
     *     tags={"Verification"},
     *     summary="Get a submitted verification by ID",
     *     description="Retrieves a specific submitted verification by its ID.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="The ID of the verification"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data fetched"),
     *             @OA\Property(property="status", type="integer", example=200),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Not Found"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function getSubmittedVerificationById(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->verificationService->getSubmittedVerificationById($request->id);
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message,
            'data' => $response->data
        ], $response->code);
    }


    /**
     * @OA\Get(
     *     path="/api/verification/my-verification",
     *     tags={"Verification"},
     *     summary="Get my  submitted verification",
     *     description="Retrieves my specific submitted verification.",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data fetched"),
     *             @OA\Property(property="status", type="integer", example=200),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Not Found"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function getMySubmittedVerification(): \Illuminate\Http\JsonResponse
    {
        $response = $this->verificationService->getMySubmittedVerification();
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message,
            'data' => $response->data
        ], $response->code);
    }


    /**
     * @OA\PUT(
     *     path="/api/verification/approve/{id}",
     *     tags={"Verification"},
     *     summary="Approve a verification",
     *     description="Approves a specific verification by its ID.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="The ID of the verification"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Verification approved successfully"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Not Found"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function approve(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->verificationService->approve($request->id);
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message
        ], $response->code);
    }

    /**
     * @OA\PUT(
     *     path="/api/verification/reject/{id}",
     *     tags={"Verification"},
     *     summary="Reject a verification",
     *     description="Rejects a specific verification by its ID.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="The ID of the verification"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Verification rejected successfully"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Not Found"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function reject(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->verificationService->reject($request->id);
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message
        ], $response->code);
    }

    /**
     * @OA\Delete(
     *     path="/api/verification/delete/{id}",
     *     tags={"Verification"},
     *     summary="Delete a verification",
     *     description="Deletes a specific verification by its ID.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="The ID of the verification"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Verification deleted successfully"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Not Found"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function deleteVerification(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->verificationService->deleteVerificaiton($request->id);
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message
        ], $response->code);
    }

}
