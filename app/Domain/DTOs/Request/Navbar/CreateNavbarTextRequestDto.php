<?php

namespace App\Domain\DTOs\Request\Navbar;

class CreateNavbarTextRequestDto
{
    public int $navbar_id;
    public string $text;
    public string $description;
    
    public function __construct(int $navbar_id, string $text,  string $description)
    {
        $this->navbar_id = $navbar_id;
        $this->text = $text;
        $this->description = $description;
    }
}
