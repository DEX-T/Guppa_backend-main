<?php

namespace App\Domain\DTOs\Request\DiscoverTalent;

class UpdateDiscoverTalentDto
{
    public int $id;
    public string $title;
    public string $description;
    public string $button_text;
    public function __construct(int $id, string $title, string $description, string $button_text)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->button_text = $button_text;
    }


}
