<?php

namespace App\Services\Category;

use App\Domain\DTOs\ApiResponseDto;
use App\Domain\DTOs\Request\Category\CategoryRequestDto;
use App\Domain\DTOs\Response\Category\CategoryResponseDto;
use App\Domain\Entities\CategoryEntity;
use App\Domain\Interfaces\Category\ICategoryService;
use App\enums\HttpStatusCode;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Skill;
use Carbon\Carbon;

class CategoryService implements ICategoryService
{

    public function upsertCategory(array $data): ApiResponseDto
    {
        try {
            $dto = new CategoryRequestDto($data);
            if ($dto->category_id == 0):
                $category = new Category();
                $category->category = $dto->category;
                $category->created_at = Carbon::now();
                $category->status = "active";
                $message = "Category created";
            else:
                $category = Category::where('id', $dto->category_id)->first();
                $category->category = $dto->category;
                $category->updated_at = Carbon::now();
                $message = "Category updated";
            endif;
            $category->save();
            return new ApiResponseDto(true, $message, HttpStatusCode::OK);
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

    }

    public function deleteCategory(int $catId): ApiResponseDto
    {
        try {
            $category = Category::where('id', $catId)->first();
            if($category != null){
                $category->delete();
                return new ApiResponseDto(true, "Category deleted", HttpStatusCode::OK);

            }
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);

        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function activateCategory(int $catId): ApiResponseDto
    {
        try {
            $category = Category::where('id', $catId)->first();
            if($category != null){
                $category->setStatus("active");
                $category->save();
                return new ApiResponseDto(true, "Category activated", HttpStatusCode::OK);

            }
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);

        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function deactivateCategory(int $catId): ApiResponseDto
    {
        try {
            $category = Category::where('id', $catId)->first();
            if($category != null){
                $category->setStatus("inactive");
                $category->save();
                $skills = Skill::where('category_id', $category->id)->get();
                foreach ($skills as $skill){
                    $skill->forceFill(['status', "inactive"])->save();
                }
                return new ApiResponseDto(true, "Category and its skills deactivated", HttpStatusCode::OK);

            }
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);

        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function getAllCategory(): ApiResponseDto
    {
        try {
            $categories = Category::all();
            if($categories->isNotEmpty()){
                $dto = $categories->map(function ($category){
                    $categoryEntity = new CategoryEntity($category);
                    return new CategoryResponseDto($categoryEntity);
                });
                return new ApiResponseDto(true, "Categories fetched", HttpStatusCode::OK, $dto->toArray());
            }
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);

        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }
    public function getAllCategories(): ApiResponseDto
    {
        try {
            $categories = Category::where('status', 'active')->get();
            if($categories->isNotEmpty()){
                $dto = $categories->map(function ($category){
                    $categoryEntity = new CategoryEntity($category);
                    return new CategoryResponseDto($categoryEntity);
                });
                return new ApiResponseDto(true, "Categories fetched", HttpStatusCode::OK, $dto->toArray());
            }
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);

        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function getCategory(int $catId): ApiResponseDto
    {
        try {
            $category = Category::where('id', $catId)->first();
            if($category != null){
                $categoryEntity = new CategoryEntity($category);
                $dto = new CategoryResponseDto($categoryEntity);
                return new ApiResponseDto(true, "Category fetched", HttpStatusCode::OK, $dto->toArray());
            }
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);

        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }
}
