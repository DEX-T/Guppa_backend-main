<?php

namespace App\Http\Controllers\DiscoverTalent;

use App\Domain\Interfaces\DiscoverTalent\IDiscoverTalentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class DiscoverTalentController extends Controller
{
    private  IDiscoverTalentService $_discoverTalentService;

    public function __construct(IDiscoverTalentService $discoverTalentService)
    {
        $this->_discoverTalentService = $discoverTalentService;
    }

    /**
     * @OA\Post(
     *     path="/api/discoverTalent/create-talent",
     *     operationId="createDiscoverTalent",
     *     tags={"DiscoverTalent"},
     *     security={{"sanctum":{}}},
     *     summary="Create Discover Talent",
     *     description="Create a new Discover Talent",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"title", "description", "button_text"},
     *             @OA\Property(property="title", type="string", example="Discover Title"),
     *             @OA\Property(property="description", type="string", example="This is a description"),
     *             @OA\Property(property="button_text", type="string", example="Click Here")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Discover Talent created successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error creating Discover Talent",
     *     )
     * )
     */
    public function createDiscover(Request $request)
    {
        $status =  $this->_discoverTalentService->createDiscover($request);
        if ($status->status) {
            return response()->json([
                'success' => true,
                'message' => $status->message
            ], $status->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $status->message,
                'error' => $status->data
            ], $status->code);


        }
    }

    /**
     * @OA\Get(
     *     path="/api/discoverTalent/discover-talents",
     *     operationId="getAllDiscoverTalent",
     *     tags={"DiscoverTalent"},
     *     security={{"sanctum":{}}},
     *     summary="Get All Discover Talents",
     *     description="Get all Discover Talents",
     *     @OA\Response(
     *         response=200,
     *         description="Successful"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No Discover Talents found",
     *     )
     * )
     */
    public function getAllDiscover()
    {
        $status =  $this->_discoverTalentService->GetAllDiscover();

        return response()->json([
            'success' => $status->status,
            'message' => $status->message,
            'data' => $status->data
        ], $status->code);

    }

    /**
     * @OA\Get(
     *     path="/api/discoverTalent/discover-talent-with-bg",
     *     operationId="getAllDiscoverTalentWithBg",
     *     tags={"DiscoverTalent"},
     *     security={{"sanctum":{}}},
     *     summary="Get  Discover Talent with bg",
     *     description="Get  Discover Talents",
     *     @OA\Response(
     *         response=200,
     *         description="Successful"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No Discover Talents found",
     *     )
     * )
     */
    public function getDiscoverTalentWithBg()
    {
        $status =  $this->_discoverTalentService->getDiscoverTalent();

        return response()->json([
            'success' => $status->status,
            'message' => $status->message,
            'data' => $status->data
        ], $status->code);

    }

    /**
     * @OA\Get(
     *     path="/api/discoverTalent/discover-talent/{id}",
     *     operationId="getDiscoverTalentById",
     *     tags={"DiscoverTalent"},
     *     security={{"sanctum":{}}},
     *     summary="Get Discover Talent by ID",
     *     description="Get a Discover Talent by its ID",
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
     *         description="Discover Talent not found",
     *     )
     * )
     */
    public function getDiscoverById(int $id)
    {
        $status = $this->_discoverTalentService->getDiscoverById($id);
        if ($status->status) {
            return response()->json([
                'success' => true,
                'message' => $status->message
            ], $status->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $status->message,
                'error' => $status->data
            ], $status->code);


        }
    }

    /**
     * @OA\Put(
     *     path="/api/discoverTalent/discover-talent/update",
     *     operationId="updateDiscoverTalent",
     *     tags={"DiscoverTalent"},
     *     security={{"sanctum":{}}},
     *     summary="Update Discover Talent",
     *     description="Update an existing Discover Talent",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"id", "title", "description", "button_text"},
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Updated Title"),
     *             @OA\Property(property="description", type="string", example="Updated description"),
     *             @OA\Property(property="button_text", type="string", example="Updated Button Text")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Discover Talent updated successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Discover Talent not found",
     *     )
     * )
     */
    public function updateDiscover(Request $request)
    {
        $update = $this->_discoverTalentService->updateDiscover($request);
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
     *     path="/api/discoverTalent/discover-talent/delete/{id}",
     *     operationId="deleteDiscoverTalent",
     *     tags={"DiscoverTalent"},
     *     security={{"sanctum":{}}},
     *     summary="Delete Discover Talent",
     *     description="Delete a Discover Talent by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Discover Talent deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Discover Talent not found"
     *     )
     * )
     */
    public function deleteDiscover(int $id)
    {
        $status = $this->_discoverTalentService->deleteDiscover($id);
        if ($status->status) {
            return response()->json([
                'success' => true,
                'message' => $status->message
            ], $status->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $status->message,
                'error' => $status->data
            ], $status->code);


        }
    }

    /**
     * @OA\Post(
     *     path="/api/discoverTalent/background/create-background",
     *     operationId="createDiscoverTalentBackground",
     *     tags={"DiscoverTalent"},
     *      security={{"sanctum":{}}},
     *     summary="Create Discover Talent Background",
     *     description="Create a new Discover Talent Background",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"discover_id", "image_url"},
     *             @OA\Property(property="discover_id", type="integer", example=1),
     *             @OA\Property(property="image_url", type="string", example="000/image.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Discover Talent Background created successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error creating Discover Talent Background",
     *     )
     * )
     */
    public function createDiscoverBackground(Request $request)
    {
        $create =  $this->_discoverTalentService->createDiscoverBackground($request);
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
     *     path="/api/discoverTalent/backgrounds",
     *     operationId="getAllDiscoverTalentBackground",
     *     tags={"DiscoverTalent"},
     *     security={{"sanctum":{}}},
     *     summary="Get All Discover Talent Backgrounds",
     *     description="Get all Discover Talent Backgrounds",
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No Discover Talent Backgrounds found",
     *     )
     * )
     */
    public function getAllDiscoverBackground()
    {
        $status =  $this->_discoverTalentService->GetAllDiscoverBackground();
        if ($status->status) {
            return response()->json([
                'success' => true,
                'message' => $status->message,
                'data' => $status->data
            ], $status->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $status->message,
                'error' => $status->data
            ], $status->code);


        }
    }

    /**
     * @OA\Get(
     *     path="/api/discoverTalent/background/{id}",
     *     operationId="getDiscoverTalentBackgroundById",
     *     tags={"DiscoverTalent"},
     *     security={{"sanctum":{}}},
     *     summary="Get Discover Talent Background by ID",
     *     description="Get a Discover Talent Background by its ID",
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
     *         description="Discover Talent Background not found",
     *     )
     * )
     */
    public function getDiscoverBackgroundById(int $id)
    {
        $status = $this->_discoverTalentService->getDiscoverBackgroundById($id);
        if ($status->status) {
            return response()->json([
                'success' => true,
                'message' => $status->message
            ], $status->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $status->message,
                'error' => $status->data
            ], $status->code);


        }

    }

    /**
     * @OA\Put(
     *     path="/api/discoverTalent/background/update",
     *     operationId="updateDiscoverTalentBackground",
     *     tags={"DiscoverTalent"},
     *     security={{"sanctum":{}}},
     *     summary="Update Discover Talent Background",
     *     description="Update an existing Discover Talent Background",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"id", "discover_id", "image_url"},
     *             @OA\Property(property="id", type="integer", example=0),
     *             @OA\Property(property="discover_id", type="integer", example=0),
     *             @OA\Property(property="image_url", type="string", example="0000/new_image.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Discover Talent Background updated successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Discover Talent Background not found",
     *     )
     * )
     */
    public function updateDiscoverBackground(Request $request)
    {
        $status =  $this->_discoverTalentService->updateDiscoverBackground($request);
        if ($status->status) {
            return response()->json([
                'success' => true,
                'message' => $status->message
            ], $status->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $status->message,
                'error' => $status->data
            ], $status->code);


        }

    }

    /**
     * @OA\Delete(
     *     path="/api/discoverTalent/background/delete/{id}",
     *     operationId="deleteDiscoverTalentBackground",
     *     tags={"DiscoverTalent"},
     *     security={{"sanctum":{}}},
     *     summary="Delete Discover Talent Background",
     *     description="Delete a Discover Talent Background by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Discover Talent Background deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Discover Talent Background not found",
     *     )
     * )
     */
    public function deleteDiscoverBackground(int $id)
    {
        $status =  $this->_discoverTalentService->deleteDiscoverBackground($id);
        if ($status->status) {
            return response()->json([
                'success' => true,
                'message' => $status->message
            ], $status->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $status->message,
                'error' => $status->data
            ], $status->code);

        }

    }
}
