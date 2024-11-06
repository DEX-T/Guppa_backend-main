<?php

namespace App\Http\Controllers\WhyChooseUs;

use App\Domain\Interfaces\WhyChooseUs\IWhyChooseUsService;
use App\Domain\Interfaces\WhyChooseUs\IWhyChoseUsService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WhyChooseUsController extends Controller
{
    public IWhyChoseUsService $_whyChooseUs;

    public function __construct(IWhyChoseUsService $whyChooseUsService)
    {
        $this->_whyChooseUs = $whyChooseUsService;
    }

    /**
     * @OA\Post(
     *     path="/api/whychooseus/create",
     *     operationId="createWhyChooseUs",
     *     tags={"WhyChooseUs"},
     *     summary="Create a new Why Choose Us item",
     *     description="Creates a new Why Choose Us item",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"heading", "description"},
     *             @OA\Property(property="heading", type="string", example="Why Choose Us Heading"),
     *             @OA\Property(property="description", type="string", example="Why Choose Us Description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Why Choose Us created successfully",
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function create(Request $request)
    {
        $create = $this->_whyChooseUs->create($request);
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
     *     path="/api/whychooseus/whychooseus",
     *     operationId="getAllWhyChooseUs",
     *     tags={"WhyChooseUs"},
     *     summary="Get all Why Choose Us items",
     *     description="Returns all Why Choose Us items",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function getAll()
    {
        $get = $this->_whyChooseUs->GetAll();
        return response()->json([
            'success' => $get->status,
            'message' => $get->message,
            'data' => $get->data
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/whychooseus/why-choose-us",
     *     operationId="getAllWhyChooseUsFE",
     *     tags={"WhyChooseUs"},
     *     summary="Get all Why Choose Us items for landing page",
     *     description="Returns all Why Choose Us items",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     * )
     */
    public function getAllFE()
    {
        $get = $this->_whyChooseUs->GetAllFE();
        return response()->json([
            'success' => $get->status,
            'message' => $get->message,
            'data' => $get->data
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/whychooseus/whychooseus/{id}",
     *     operationId="getWhyChooseUsById",
     *     tags={"WhyChooseUs"},
     *     summary="Get Why Choose Us by ID",
     *     description="Returns Why Choose Us data by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Why Choose Us not found"
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function getById(int $id)
    {
        $get = $this->_whyChooseUs->getById($id);
        return response()->json([
            'success' => $get->status,
            'message' => $get->message,
            'data' => $get->data
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/whychooseus/update",
     *     operationId="updateWhyChooseUs",
     *     tags={"WhyChooseUs"},
     *     summary="Update Why Choose Us",
     *     description="Update Why Choose Us item",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "heading", "description"},
     *             @OA\Property(property="id", type="integer", example="0"),
     *             @OA\Property(property="heading", type="string", example="Updated Why Choose Us Heading"),
     *             @OA\Property(property="description", type="string", example="Updated Why Choose Us Description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Why Choose Us updated successfully",
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function update(Request $request)
    {
        $update = $this->_whyChooseUs->update($request);
        return response()->json([
            'success' => $update->status,
            'message' => $update->message,
            'data' => $update->data
        ]);
    }


    /**
     * @OA\Delete(
     *     path="/api/whychooseus/delete/{id}",
     *     operationId="deleteWhyChooseUs",
     *     tags={"WhyChooseUs"},
     *     summary="Delete Why Choose Us",
     *     description="Delete Why Choose Us item",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Why Choose Us deleted successfully",
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function delete(int $id)
    {
        $delete = $this->_whyChooseUs->delete($id);
        return response()->json([
            'success' => $delete->status,
            'message' => $delete->message,
            'data' => $delete->data
        ]);
    }



    /**
     * @OA\Post(
     *     path="/api/whychooseus/card/create",
     *     operationId="createWhyChooseUsCard",
     *     tags={"WhyChooseUs"},
     *     summary="Create a new Why Choose Us Card",
     *     description="Creates a new Why Choose Us Card",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"guppa_id", "picture", "title", "description"},
     *             @OA\Property(property="guppa_id", type="integer", example="0"),
     *             @OA\Property(property="picture", type="string", example="0000/picture.jpg"),
     *             @OA\Property(property="title", type="string", example="Card Title"),
     *             @OA\Property(property="description", type="string", example="Card Description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Why Choose Us Card created successfully",
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function create_card(Request $request)
    {
        $update = $this->_whyChooseUs->createCard($request);
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
     * @OA\Get(
     *     path="/api/whychooseus/cards",
     *     operationId="getAllWhyChooseUsCard",
     *     tags={"WhyChooseUs"},
     *     summary="Get all Why Choose Us card items",
     *     description="Returns all Why Choose Us card items",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function getAllCards()
    {
        $get = $this->_whyChooseUs->GetAllCard();
        return response()->json([
            'success' => $get->status,
            'message' => $get->message,
            'data' => $get->data
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/whychooseus/card/{id}",
     *     operationId="getWhyChooseUsCardById",
     *     tags={"WhyChooseUs"},
     *     summary="Get Why Choose Us card by ID",
     *     description="Returns Why Choose Us card data by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Why Choose Us card not found"
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function getCardById(int $id)
    {
        $get = $this->_whyChooseUs->getCardById($id);
        return response()->json([
            'success' => $get->status,
            'message' => $get->message,
            'data' => $get->data
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/whychooseus/card/update",
     *     operationId="updateWhyChooseUsCard",
     *     tags={"WhyChooseUs"},
     *     summary="Update Why Choose Us Card",
     *     description="Update Why Choose Us Card",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "guppa_id", "picture", "title", "description"},
     *             @OA\Property(property="id", type="integer", example="1"),
     *             @OA\Property(property="guppa_id", type="integer", example="1"),
     *             @OA\Property(property="picture", type="string", example="path/to/updated_picture.jpg"),
     *             @OA\Property(property="title", type="string", example="Updated Card Title"),
     *             @OA\Property(property="description", type="string", example="Updated Card Description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Why Choose Us Card updated successfully",
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function update_card(Request $request)
    {
        $update = $this->_whyChooseUs->updateCard($request);
        return response()->json([
            'success' => $update->status,
            'message' => $update->message,
            'data' => $update->data
        ]);
    }


    /**
     * @OA\Delete(
     *     path="/api/whychooseus/card/delete/{id}",
     *     operationId="deleteWhyChooseUsCard",
     *     tags={"WhyChooseUs"},
     *     summary="Delete Why Choose Us",
     *     description="Delete Why Choose Us item",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Why Choose Us deleted successfully",
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function delete_card(int $id)
    {
        $delete = $this->_whyChooseUs->deleteCard($id);
        return response()->json([
            'success' => $delete->status,
            'message' => $delete->message,
            'data' => $delete->data
        ]);
    }


}
