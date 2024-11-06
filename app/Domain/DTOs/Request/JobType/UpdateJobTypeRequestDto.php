<?php

namespace App\Domain\DTOs\Request\JobType;

class UpdateJobTypeRequestDto
{
    public int $id;
    public string $type;
    public string $description;

    public function __construct(int $id, string $type, string $description)
    {
        $this->id = $id;
        $this->type = $type;
        $this->description = $description;

    }




}
