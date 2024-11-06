<?php

namespace App\Domain\DTOs\Response\Navbar;

use App\Domain\Entities\Navbar\NavbarLogoEntity;

class NavbarLogoResponseDto
{
    public int $id;
    public int $navbar_id;
    public string $logo_url;


    public function __construct(NavbarLogoEntity $navbarLogo)
    {
        $this->id = $navbarLogo->getId();
        $this->navbar_id = $navbarLogo->getNavbarId();
        $this->logo_url = $navbarLogo->getLogoUrl();
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'navbar_id' => $this->navbar_id,
            'logo_url' => $this->logo_url,
        ];
    }
}
