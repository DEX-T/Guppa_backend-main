<?php

namespace App\Domain\Entities;

use App\Models\Category;
use App\Models\Skill;

class SkillEntity
{
    private int $skill_id;
    private string $skill;
    private string $status;
    private $created_at;
    private $updated_at;
    private $category;

    function __construct(Skill $skill)
    {
        $this->skill_id = $skill->id;
        $this->skill = $skill->skill;
        $this->status = $skill->status;
        $this->created_at = $skill->created_at;
        $this->updated_at = $skill->updated_at;
        $this->category = $this->getCategoryDetails($skill->category_id);
    }

    public function getCategory(): array
    {
        return $this->category;
    }
    public function getCategoryDetails($categoryId): array
    {
        $category = Category::findOrFail($categoryId);
        return [
            'category_id' => $category->id,
           'category' => $category->category
        ];
    }
    public function getSkillId(){
        return $this->skill_id;
    }

    public function getSkill(){
        return $this->skill;
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
