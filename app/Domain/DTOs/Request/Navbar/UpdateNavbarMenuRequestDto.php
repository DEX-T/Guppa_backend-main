<?php

namespace App\Domain\DTOs\Request\Navbar;

class UpdateNavbarMenuRequestDto
{
    public int $id;
    public int $navbar_id;
    public string $menu_text;
    public string $menu_link;

    public function __construct(int $id, int $navbar_id, string $menu_text, string $menu_link)
    {
        $this->id = $id;
        $this->navbar_id = $navbar_id;
        $this->menu_text = $menu_text;
        $this->menu_link = $menu_link;
    }

 

    
}
