<?php

namespace App\Domain\DTOs\Request\Navbar;

class UpdateNavbarTextRequestDto
{
    public int $id;
    public int $navbar_id;
    public string $text;
    public string $description;

    public function __construct(int $id, int $navbar_id, string $text, string $description){
        $this->id = $id;
        $this->navbar_id = $navbar_id;
        $this->text = $text;
        $this->description = $description;
    }
    
        
 }
