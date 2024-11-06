<?php

namespace App\Domain\DTOs\Request\WhyChooseUs;

class UpdateWhyChooseUsCardRequestDto
{
    public int $id;
    public int $whychooseus_id;
    public string $picture;
    public string $title;
    public string $description;

    public function __construct(int $whychooseus_id,string $title, string $picture, string $description, int $id)
    {
        $this->whychooseus_id = $whychooseus_id;
        $this->title = $title;
        $this->picture = $picture;
        $this->description = $description;
        $this->id  = $id;

    }


}
