<?php

namespace App\Domain\Interfaces\Category;

use App\Http\Requests\CategoryRequest;

interface ICategoryService
{
    public function upsertCategory(array $data);
    public function getAllCategory();
    public function getCategory(int $catId);
    public function getAllCategories();
    public function deleteCategory(int $catId);
    public function activateCategory(int $catId);
    public function deactivateCategory(int $catId);
}
