<?php

namespace App\Http\Controllers\Category;

use App\Domain\Interfaces\Category\ICategoryService;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;

class CategoryController
{
    private  ICategoryService $_categoryService;

    function __construct(ICategoryService $categoryService)
    {
        $this->_categoryService = $categoryService;
    }

    /**
     * @OA\Post(
     *     path="/api/category/upsert-category",
     *     operationId="upsertCategory",
     *     tags={"Category"},
     *     security={{"sanctum":{}}},
     *     summary="Create or update a category",
     *     description="Creates a new category or updates an existing one based on the provided data.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"category_id", "category"},
     *             @OA\Property(property="category_id", type="integer", example=0),
     *             @OA\Property(property="category", type="string", example="Electronics")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function upsertCategory(CategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $response = $this->_categoryService->upsertCategory($data);
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message
        ], $response->code);
    }

    /**
     * @OA\Delete(
     *     path="/api/category/delete/{id}",
     *     operationId="deleteCategory",
     *     tags={"Category"},
     *     security={{"sanctum":{}}},
     *     summary="Delete a category",
     *     description="Deletes a category by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the category to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function deleteCategory($id): \Illuminate\Http\JsonResponse
    {
        $response = $this->_categoryService->deleteCategory($id);
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message
        ], $response->code);
    }

    /**
     * @OA\Put(
     *     path="/api/category/activate/{id}",
     *     operationId="activateCategory",
     *     tags={"Category"},
     *     security={{"sanctum":{}}},
     *     summary="Activate a category",
     *     description="Activates a category by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the category to activate",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category activated",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function activateCategory($id): \Illuminate\Http\JsonResponse
    {
        $response = $this->_categoryService->activateCategory($id);
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message
        ], $response->code);
    }

    /**
     * @OA\Put(
     *     path="/api/category/deactivate/{id}",
     *     operationId="deactivateCategory",
     *     tags={"Category"},
     *     security={{"sanctum":{}}},
     *     summary="Deactivate a category",
     *     description="Deactivates a category by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the category to deactivate",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deactivated",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function deactivateCategory($id): \Illuminate\Http\JsonResponse
    {
        $response = $this->_categoryService->deactivateCategory($id);
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message
        ], $response->code);
    }


    /**
     * @OA\Get(
     *     path="/api/category/get_category/{id}",
     *     operationId="getCategoryById",
     *     tags={"Category"},
     *     security={{"sanctum":{}}},
     *     summary="Get a category by ID",
     *     description="Fetches a category by its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the category to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category retrieved",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getCategoryById(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->_categoryService->getCategory($request->id);
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message,
            'data' => $response->data
        ], $response->code);
    }

    /**
     * @OA\Get(
     *     path="/api/category/categories",
     *     operationId="getAllCategories",
     *     tags={"Category"},
     *     security={{"sanctum":{}}},
     *     summary="Get all categories",
     *     description="Fetches all categories.",
     *     @OA\Response(
     *         response=200,
     *         description="Categories retrieved"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getAllCategories(): \Illuminate\Http\JsonResponse
    {
        $response = $this->_categoryService->getAllCategories();
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message,
            'data' => $response->data
        ], $response->code);
    }


    /**
     * @OA\Get(
     *     path="/api/category/get_categories_admin",
     *     operationId="getAllCategoriesForAdmin",
     *     tags={"Category"},
     *     security={{"sanctum":{}}},
     *     summary="Get all categories for admin",
     *     description="Fetches all categories admin.",
     *     @OA\Response(
     *         response=200,
     *         description="Categories retrieved",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getAllCategoriesAdmin(): \Illuminate\Http\JsonResponse
    {
        $response = $this->_categoryService->getAllCategory();
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message,
            'data' => $response->data
        ], $response->code);
    }
}
