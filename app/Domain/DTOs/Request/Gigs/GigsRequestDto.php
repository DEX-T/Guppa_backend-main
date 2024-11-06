<?php

namespace App\Domain\DTOs\Request\Gigs;

class GigsRequestDto
{
    public string $name;
    public string $description;

    public function __construct( string $name, string $description)
    {
        $this->name = $name;
        $this->description = $description;

    }
}
