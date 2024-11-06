<?php

namespace App\Domain\DTOs\Response\Navbar;

use App\Domain\Entities\Navbar\NavbarTypeEntity;

class NavbarTypeResponseDto

{
    public int $id;
    public string $type;
    public string $date_created;
    public string $date_modified;
   



    public function __construct(NavbarTypeEntity $navbarTypeEntity)
    {
        $this->id = $navbarTypeEntity->getId();
        $this->type = $navbarTypeEntity->getType();
        $this->date_created = $navbarTypeEntity->getDateCreated();
        $this->date_modified = $navbarTypeEntity->getDateModified();
    }
    // Define your DTO properties and methods here

    public function toArray()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'date_created' => $this->date_created,
            'date_modified' => $this->date_modified
        ];
    }

}
