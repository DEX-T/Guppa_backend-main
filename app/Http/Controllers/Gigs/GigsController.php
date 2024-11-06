<?php

namespace App\Http\Controllers\Gigs;

use App\Domain\Interfaces\Gigs\IGigsService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GigsController extends Controller
{
    public IGigsService $_gigsService;

    public function __construct(IGigsService $_gigsService)
    {
        $this->_gigsService = $_gigsService;
    }


#region gigList

/**
 * @OA\Post(
 *     path="/api/gigs/create-gigs",
 *     operationId="createGigs",
 *     tags={"Gigs"},
 *     security={{"sanctum":{}}},
 *     summary="Create new Gigs",
 *     description="Create a new Gigs",
 *      @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "description"},
 *             @OA\Property(property="name", type="string", example="Abraham"),
 *             @OA\Property(property="description", type="string", example="Gigs Experience"),
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
public function createGigs(Request $request): \Illuminate\Http\JsonResponse
{
    $create = $this->_gigsService->createGigs($request);
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
 *     path="/api/gigs/getGigsList",
 *     operationId="getGigsList",
 *     tags={"Gigs"},
 *     summary="Get list of Gigs",
 *     description="Returns list of Gigs",
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No Gigs found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getAllGigs(): \Illuminate\Http\JsonResponse
{
    $gigslist = $this->_gigsService->getAllGigs();
    return response()->json([
        'success' => $gigslist->status,
        'message' => $gigslist->message,
        'data' => $gigslist->data
    ]);
}

/**
 * @OA\Get(
 *     path="/api/gigs/getGig/{id}",
 *     operationId="getGigById",
 *     tags={"Gigs"},
 *     summary="Get Gig information",
 *     description="Returns Gig data",
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
 *         description="Gig not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getGigById(Request $request): \Illuminate\Http\JsonResponse
{
    $menuDto = $this->_gigsService->getGigsbyId($request->id);
    if ($menuDto->status) {
        return response()->json([
            'success' => $menuDto->status,
            'message' => $menuDto->message,
            'data' => $menuDto->data
        ], $menuDto->code);
    } else {
        return response()->json([
            'success' => $menuDto->status,
            'message' => $menuDto->message,
            'data' => $menuDto->data
        ], $menuDto->code);
    }
}

/**
 * @OA\Put(
 *     path="/api/gigs/giglist/update",
 *     operationId="updateGigList",
 *     tags={"Gigs"},
 *     security={{"sanctum":{}}},
 *     summary="Update Gig List",
 *     description="Update Gig List",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"id", "name", "description"},
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Abraham"),
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
 *         description="GigList not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     ),
 * )
 */
public function updateGigList(Request $request): \Illuminate\Http\JsonResponse
{
    $update = $this->_gigsService->updateGigs($request);
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
 *     path="/api/gigs/giglist/delete/{id}",
 *     operationId="deleteGigList",
 *     tags={"Gigs"},
 *     security={{"sanctum":{}}},
 *     summary="Delete GigList",
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
 *         description="GigList not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function deleteGigList(Request $request): \Illuminate\Http\JsonResponse
{
    $delete = $this->_gigsService->deleteGigs($request->id);
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

#endregion GigList

}
