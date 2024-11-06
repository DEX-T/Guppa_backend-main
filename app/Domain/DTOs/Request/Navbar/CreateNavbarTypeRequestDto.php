<?php

namespace App\Domain\DTOs\Request\Navbar;

class CreateNavbarTypeRequestDto
{
    public string $type;


    public function __construct(string $type)
    {
        $this->type = $type;
    }
}
