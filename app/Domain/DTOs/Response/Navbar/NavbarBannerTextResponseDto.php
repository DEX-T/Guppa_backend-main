<?php

namespace App\Domain\DTOs\Response\Navbar;

use App\Domain\Entities\Navbar\NavbarTextEntity;

class NavbarBannerTextResponseDto
{
    public string $text;
    public string $description;


    public function __construct(NavbarTextEntity $navbarText)
    {
        $this->text = $navbarText->getText();
        $this->description = $navbarText->getDescription();
    }


    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'description' => $this->description
        ];
    }
}
