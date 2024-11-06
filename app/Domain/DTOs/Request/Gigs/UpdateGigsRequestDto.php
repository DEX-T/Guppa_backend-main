<?php

namespace App\Domain\DTOs\Request\Gigs;

class UpdateGigsRequestDto
{
    public int $id;
    public string $name;
    public string $description;

    public function __construct(int $id, string $name, string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;

    }




}
