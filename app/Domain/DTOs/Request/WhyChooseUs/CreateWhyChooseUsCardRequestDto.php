<?php

namespace App\Domain\DTOs\Request\WhyChooseUs;

class CreateWhyChooseUsCardRequestDto
{
    public int $whychooseus_id;
    public string $picture;
    public string $title;
    public string $description;
    public function __construct(int $whychooseus_id, string $picture, string $title, string $description)
    {
        $this->whychooseus_id = $whychooseus_id;
        $this->picture = $picture;
        $this->title = $title;
        $this->description = $description;
    }
}
