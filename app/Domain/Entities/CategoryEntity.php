<?php

namespace App\Domain\Entities;

use App\Models\Category;

class CategoryEntity
{
    private int $category_id;
    private string $category;
    private string $status;
    private $created_at;
    private $updated_at;

    function __construct(Category $category)
    {
        $this->category_id = $category->id;
        $this->category = $category->category;
        $this->status = $category->status;
        $this->created_at = $category->created_at;
        $this->updated_at = $category->updated_at;
    }

    public function getCategoryId(){
        return $this->category_id;
    }

    public function getCategory(){
        return $this->category;
    }

    public function getStatus(){
        return $this->status;
    }

    public function getCreatedAt(){
        return $this->created_at;
    }

    public function getUpdatedAt(){
        return $this->updated_at;
    }

}
