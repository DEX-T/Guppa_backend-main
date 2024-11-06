<?php

namespace App\Domain\DTOs\Request\JobType;

class JobTypeRequestDto
{
    public string $type;
    public string $description;

    public function __construct( string $type, string $description)
    {
        $this->type = $type;
        $this->description = $description;

    }
}
