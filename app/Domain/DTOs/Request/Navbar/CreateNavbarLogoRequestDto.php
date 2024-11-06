<?php

namespace App\Domain\DTOs\Request\Navbar;

class CreateNavbarLogoRequestDto
{
    public int $navbar_id;
    public string $logo_url;
    public function __construct(int $navbar_id, string $logo_url )
    {
        $this->navbar_id = $navbar_id;
        $this->logo_url = $logo_url;
    }
}
