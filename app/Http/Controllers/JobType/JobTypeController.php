<?php

namespace App\Http\Controllers\JobType;

use App\Domain\Interfaces\JobType\IJobTypeService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JobTypeController extends Controller
{
    public IJobTypeService $_jobtype;

    public function __construct(IJobTypeService $_jobtype)
    {
        $this->_jobtype = $_jobtype;
    }


#region gigList

/**
 * @OA\Post(
 *     path="/api/jobtype/create",
 *     operationId="createJobType",
 *     tags={"Job Type"},
 *     security={{"sanctum":{}}},
 *     summary="Create new JobType",
 *     description="Create a new JobType",
 *      @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"type", "description"},
 *             @OA\Property(property="type", type="string", example="Computer Repair"),
 *             @OA\Property(property="description", type="string", example="JobType Experience"),
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
public function createJobType(Request $request): \Illuminate\Http\JsonResponse
{
    $create = $this->_jobtype->createJobType($request);
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
 *     path="/api/jobtype/getAll",
 *     operationId="getAllJobType",
 *     tags={"Job Type"},
 *     summary="Get list of all Job Type",
 *     description="Returns list of All Job Type",
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No JobType found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getAllJobType(): \Illuminate\Http\JsonResponse
{
    $jobtype = $this->_jobtype->getAllJobType();
    return response()->json([
        'success' => $jobtype->status,
        'message' => $jobtype->message,
        'data' => $jobtype->data
    ]);
}

/**
 * @OA\Get(
 *     path="/api/jobtype/getById/{id}",
 *     operationId="getJobTypeById",
 *     tags={"Job Type"},
 *     summary="Get Job Type information",
 *     description="Returns Job Type data",
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
 *         description="Job Type not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getJobTypebyId(Request $request): \Illuminate\Http\JsonResponse
{
    $jobtype = $this->_jobtype->getJobTypebyId($request->id);
    if ($jobtype->status) {
        return response()->json([
            'success' => $jobtype->status,
            'message' => $jobtype->message,
            'data' => $jobtype->data
        ], $jobtype->code);
    } else {
        return response()->json([
            'success' => $jobtype->status,
            'message' => $jobtype->message,
            'data' => $jobtype->data
        ], $jobtype->code);
    }
}

/**
 * @OA\Put(
 *     path="/api/jobtype/update",
 *     operationId="updateJobType",
 *     tags={"Job Type"},
 *     security={{"sanctum":{}}},
 *     summary="Update Job Type",
 *     description="Update Job Type",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"id", "type", "description"},
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="type", type="string", example="Abraham"),
 *             @OA\Property(property="description", type="string", example="Description"),
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
 *         description="JobType not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     ),
 * )
 */
public function updateJobType(Request $request): \Illuminate\Http\JsonResponse
{
    $update = $this->_jobtype->updateJobType($request);
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
 *     path="/api/jobtype/delete/{id}",
 *     operationId="deleteJobType",
 *     tags={"Job Type"},
 *     security={{"sanctum":{}}},
 *     summary="Delete JobType",
 *     description="Delete Giglist",
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
 *         description="JobType not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function deleteJobType(Request $request): \Illuminate\Http\JsonResponse
{
    $delete = $this->_jobtype->deleteJobType($request->id);
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

#endregion JobType

}
