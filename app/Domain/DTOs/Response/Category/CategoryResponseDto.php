<?php

namespace App\Domain\DTOs\Response\Category;

use App\Domain\Entities\CategoryEntity;
use App\Models\Category;

class CategoryResponseDto
{
    public int $category_id;
    public string $category;
    public string $status;
    public $created_at;
    public $updated_at;

    function __construct(CategoryEntity $category)
    {
        $this->category_id = $category->getCategoryId();
        $this->category = $category->getCategory();
        $this->status = $category->getStatus();
        $this->created_at = $category->getCreatedAt();
        $this->updated_at = $category->getUpdatedAt();
    }


    public function toArray(): array
    {
        return [
            'category_id' => $this->category_id,
            'category' => $this->category,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

}
