<?php

namespace App\Domain\DTOs\Request\WhyChooseUs;

class CreateWhyChooseUsDto
{
    public string $heading;
    public string $description;


    public function __construct(string $heading, string $description)
    {
        $this->heading = $heading;
        $this->description = $description;
    }

}
