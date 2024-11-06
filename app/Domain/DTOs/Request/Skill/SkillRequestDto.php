<?php

namespace App\Domain\DTOs\Request\Skill;

class SkillRequestDto
{
    public int $skill_id = 0;
    public string $skill;
    public int $category_id;


    function __construct($validated)
    {
        $this->skill_id = $validated['skill_id'];
        $this->skill = $validated['skill'];
        $this->category_id = $validated['category_id'];

    }

}
