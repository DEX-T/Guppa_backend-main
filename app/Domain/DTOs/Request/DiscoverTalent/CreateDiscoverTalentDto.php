<?php

namespace App\Domain\DTOs\Request\DiscoverTalent;

class CreateDiscoverTalentDto
{
    public string $title;
    public string $description;
    public string $button_text;


    public function __construct(string $title, string $description, string $button_text)
    {
        $this->title = $title;
        $this->description = $description;
        $this->button_text = $button_text;
    }


}
