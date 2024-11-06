<?php

namespace App\Domain\DTOs\Response\Navbar;

use App\Domain\Entities\Navbar\NavbarTextEntity;

class NavbarTextResponseDto
{
    public int $id;
    public int $navbar_id;
    public string $text;
    public string $description;
    public string $status;
    public  $date_created;
    public  $date_modified;
    public $navbar;


    public function __construct(NavbarTextEntity $navbarText)
    {
        $this->id = $navbarText->getId();
        $this->navbar_id = $navbarText->getNavbarId();
        $this->text = $navbarText->getText();
        $this->description = $navbarText->getDescription();
        $this->status = $navbarText->getStatus();
        $this->date_created = $navbarText->getCreatedAt();
        $this->date_modified = $navbarText->getUpdatedAt();
        $this->navbar = $navbarText->getNavbar();
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'navbar_id' => $this->navbar_id,
            'text' => $this->text,
            'description' => $this->description,
            'status' => $this->status,
            'date_created' => $this->date_created,
            'date_modified' => $this->date_modified,
            'navbar' => $this->navbar,
        ];
    }
}
