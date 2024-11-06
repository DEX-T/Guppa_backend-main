<?php

namespace App\Http\Controllers\YearsOfExperience;

use App\Domain\Interfaces\YearsOfExperience\IYearsOfExperienceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class YearsOfExperienceController extends Controller
{
    public IYearsOfExperienceService $_yearofexperience;

    public function __construct(IYearsOfExperienceService $_yearofexperience)
    {
        $this->_yearofexperience = $_yearofexperience;
    }


#region Year of Experience

/**
 * @OA\Post(
 *     path="/api/yearsofexperience/create",
 *     operationId="createYearOfExperience",
 *     tags={"Year Of Experience"},
 *     security={{"sanctum":{}}},
 *     summary="Create new Year of Experience",
 *     description="Create a new Year of Experience",
 *      @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"yearOfExperience"},
 *             @OA\Property(property="yearOfExperience", type="string", example="5 Years"),
 *
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function createYearsOfExperience(Request $request): \Illuminate\Http\JsonResponse
{
    $create = $this->_yearofexperience->createYearsOfExperience($request);
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

/**
 * @OA\Get(
 *     path="/api/yearsofexperience/getAll",
 *     operationId="getAllYearofExperience",
 *     tags={"Year Of Experience"},
 *     summary="Get All Year of Experience",
 *     description="Returns list of All Year of Experience",
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No Gigs founf"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getAllYearsOfExperience(): \Illuminate\Http\JsonResponse
{
    $yearofexperience = $this->_yearofexperience->getAllYearsOfExperience();
    return response()->json([
        'success' => $yearofexperience->status,
        'message' => $yearofexperience->message,
        'data' => $yearofexperience->data
    ]);
}

/**
 * @OA\Get(
 *     path="/api/yearsofexperience/getById/{id}",
 *     operationId="getYearofExperienceById",
 *     tags={"Year Of Experience"},
 *     summary="Get Year of Experience information by Id",
 *     description="Returns Year of Experience",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Year of Experience not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getYearsOfExperiencebyId(Request $request): \Illuminate\Http\JsonResponse
{
    $experience = $this->_yearofexperience->getYearsOfExperiencebyId($request->id);
    if ($experience->status) {
        return response()->json([
            'success' => $experience->status,
            'message' => $experience->message,
            'data' => $experience->data
        ], $experience->code);
    } else {
        return response()->json([
            'success' => $experience->status,
            'message' => $experience->message,
            'data' => $experience->data
        ], $experience->code);
    }
}

/**
 * @OA\Put(
 *     path="/api/yearsofexperience/update",
 *     operationId="UpdateYearofExperience",
 *     tags={"Year Of Experience"},
 *     security={{"sanctum":{}}},
 *     summary="Update Year of Experience",
 *     description="Update Year of Experience",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"id", "yearOfExperience"},
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="yearOfExperience", type="string", example="5 Years"),
 *
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Year of Experience not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     ),
 * )
 */
public function updateYearsOfExperience(Request $request): \Illuminate\Http\JsonResponse
{
    $update = $this->_yearofexperience->updateYearsOfExperience($request);
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
 *     path="/api/yearsofexperience/delete/{id}",
 *     operationId="deleteYearsOfExperience",
 *     tags={"Year Of Experience"},
 *     security={{"sanctum":{}}},
 *     summary="Delete Year of Experience",
 *     description="Delete Year of Experience",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Year of Experience not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function deleteYearsOfExperience(Request $request): \Illuminate\Http\JsonResponse
{
    $delete = $this->_yearofexperience->deleteYearsOfExperience($request->id);
    if ($delete->status) {
        return response()->json([
            'success' => true,
            'message' => $delete->message
        ], $delete->code);
    } else {
        return response()->json([
            'success' => false,
            'message' => $delete->message,
            'error' => $delete->data
        ], $delete->code);
    }
}

#endregion Year of Experience

}
