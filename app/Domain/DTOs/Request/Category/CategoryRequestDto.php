<?php

namespace App\Domain\DTOs\Request\Category;

use App\Models\Category;

class CategoryRequestDto
{
    public int $category_id = 0;
    public string $category;

    function __construct($validated)
    {
        $this->category_id = $validated['category_id'];
        $this->category = $validated['category'];

    }

}
