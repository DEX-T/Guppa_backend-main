<?php

namespace App\Domain\DTOs\Response\Navbar;

use App\Domain\Entities\Navbar\NavbarTypeEntity;

class FullNavsResponseDto

{
    public int $id;
    public string $type;
    public $NavbarMenus;
    public $NavbarButtons;
    public $NavbarLogo;
    public $NavbarText;



    public function __construct(NavbarTypeEntity $navbarTypeEntity)
    {
        $this->id = $navbarTypeEntity->getId();
        $this->type = $navbarTypeEntity->getType();
        $this->NavbarMenus = $navbarTypeEntity->getMenus();
        $this->NavbarButtons = $navbarTypeEntity->getButtons();
        $this->NavbarLogo = $navbarTypeEntity->getLogo();
        $this->NavbarText = $navbarTypeEntity->getText();
    }
    // Define your DTO properties and methods here

    public function toArray()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'navbar_menus' => $this->NavbarMenus,
            'navbar_buttons' => $this->NavbarButtons,
            'navbar_logo' => $this->NavbarLogo,
            'navbar_text' => $this->NavbarText,
        ];
    }

}
