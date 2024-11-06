<?php

namespace App\Domain\DTOs\Response\Skill;

use App\Domain\Entities\SkillEntity;

class SkillResponseDto
{
    public int $skill_id;
    public string $skill;
    public string $status;
    public $created_at;
    public $updated_at;
    public $category;

    function __construct(SkillEntity $skill)
    {
        $this->skill_id = $skill->getSkillId();
        $this->skill = $skill->getSkill();
        $this->status = $skill->getStatus();
        $this->created_at = $skill->getCreatedAt();
        $this->updated_at = $skill->getUpdatedAt();
        $this->category = $skill->getCategory();
    }


    public function toArray(): array
    {
        return [
            'skill_id' => $this->skill_id,
            'skill' => $this->skill,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'category' => $this->category
        ];
    }

}
