<?php

namespace App\Domain\Entities\Navbar;

use App\Models\Navbar;
use App\Models\NavbarText;

class NavbarTextEntity
{

    private int $id;
    private int $navbar_id;
    private string $text;
    private string $description;
    private string $status;
    private  $created_at;
    private  $updated_at;
    private $navbar;

    public function __construct(NavbarText $navbarText)
    {
        $this->id = $navbarText->id;
        $this->navbar_id = $navbarText->navbar_id;
        $this->text = $navbarText->text;
        $this->description = $navbarText->description;
        $this->status = $navbarText->status;
        $this->created_at = $navbarText->created_at;
        $this->updated_at = $navbarText->updated_at;
        $this->navbar = $this->grabNavbar();
    }

    public function getNavbar(){
        return $this->navbar;
    }

    public function grabNavbar(){
        $main = Navbar::findOrFail($this->navbar_id);
        if($main != null){
            return [
                'id' => $main->id,
                'type' => $main->type,
            ];
        }else{
            return null;
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNavbarId(): int
    {
        return $this->navbar_id;
    }
    public function getText(): string
    {
        return $this->text;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
