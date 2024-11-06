<?php

namespace App\Domain\DTOs\Request\Navbar;

class UpdateNavbarTypeDto
{
    public int $id;
    public string $type;

    public function __construct(string $type, int $id){
        $this->id = $id;
        $this->type = $type;
    }
   

    
}
