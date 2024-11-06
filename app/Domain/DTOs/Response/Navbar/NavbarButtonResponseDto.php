<?php

namespace App\Domain\DTOs\Response\Navbar;

use App\Domain\Entities\Navbar\NavbarButtonEntity;

class NavbarButtonResponseDto
{
    public int $id;
    public int $navbar_id;
    public string $button_text;
    public string $button_link;
    public string $status;
    public string $date_created;
    public string $date_modified;
    public $navbar;

    public function __construct(NavbarButtonEntity $navbarButtonEntity)
    {
        $this->id = $navbarButtonEntity->getId();
        $this->navbar_id = $navbarButtonEntity->getNavbarId();
        $this->button_text = $navbarButtonEntity->getButtonText();
        $this->button_link = $navbarButtonEntity->getButtonLink();
        $this->button_link = $navbarButtonEntity->getButtonLink();
        $this->status = $navbarButtonEntity->getStatus();
        $this->date_created = $navbarButtonEntity->createdAt();
        $this->date_modified = $navbarButtonEntity->modifiedAt();
        $this->navbar = $navbarButtonEntity->getNavbar();

    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'navbar_id' => $this->navbar_id,
            'button_text' => $this->button_text,
            'button_link' => $this->button_link,
            'status' => $this->status,
            'date_created' => $this->date_created,
            'date_modified' => $this->date_modified,
            'navbar' => $this->navbar
        ];
    }
}
