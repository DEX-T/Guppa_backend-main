<?php

namespace App\Domain\DTOs\Response\Navbar;

use App\Domain\Entities\Navbar\NavbarMenuEntity;

class NavbarMenuResponseDto
{
    public int $id;
    public int $navbar_id;
    public string $menu_text;
    public string $menu_link;
    public string $status;
    public string $date_created;
    public string $date_modified;
    public  $navbar;


    public function __construct(NavbarMenuEntity $navbarMenuEntity)
    {
        $this->id = $navbarMenuEntity->getId();
        $this->navbar_id = $navbarMenuEntity->getNavbarId();
        $this->menu_text = $navbarMenuEntity->getMenuText();
        $this->menu_link = $navbarMenuEntity->getMenuLink();
        $this->status = $navbarMenuEntity->getStatus();
        $this->date_created = $navbarMenuEntity->createdAt();
        $this->date_modified = $navbarMenuEntity->modifiedAt();
        $this->navbar = $navbarMenuEntity->getNavbar();
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'navbar_id' => $this->navbar_id,
            'menu_text' => $this->menu_text,
            'menu_link' => $this->menu_link,
            'status' => $this->status,
            'date_created' => $this->date_created,
            'date_modified' => $this->date_modified,
            'navbar' => $this->navbar,
        ];
    }
}
