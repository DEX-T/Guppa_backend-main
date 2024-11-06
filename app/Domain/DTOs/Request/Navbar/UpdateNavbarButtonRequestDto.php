<?php

namespace App\Domain\DTOs\Request\Navbar;

class UpdateNavbarButtonRequestDto
{
    public int $id;
    public int $navbar_id;
    public string $button_text;
    public string $button_link;

    public function __construct(int $id, int $navbar_id, string $button_text, string $button_link)
    {
        $this->id = $id;
        $this->navbar_id = $navbar_id;
        $this->button_text = $button_text;
        $this->button_link = $button_link;
    }

   
}
