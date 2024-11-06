<?php

namespace App\Domain\DTOs\Request\WhyChooseUs;

class UpdateWhyChooseUsDto
{
    public int $id;
    public string $heading;
    public string $description;
    public function __construct(int $id, string $heading, string $description)
    {
        $this->id = $id;
        $this->heading = $heading;
        $this->description = $description;
    }

}
