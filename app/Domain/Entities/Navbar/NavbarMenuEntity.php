<?php

namespace App\Domain\Entities\Navbar;

use App\Models\Navbar;
use App\Models\NavbarMenu;

class NavbarMenuEntity
{

    private int $id;
    private int $navbar_id;
    private string $menu_text;
    private string $menu_link;
    private string $status;
    private string $createdAt;
    private string $modifiedAt;
    private $navbar;

    public function __construct(NavbarMenu $navbarMenu)
    {
        $this->id = $navbarMenu->id;
        $this->navbar_id = $navbarMenu->navbar_id;
        $this->menu_text = $navbarMenu->menu_text;
        $this->menu_link = $navbarMenu->menu_link;
        $this->status = $navbarMenu->status;
        $this->createdAt = $navbarMenu->created_at;
        $this->modifiedAt = $navbarMenu->updated_at;
        $this->navbar = $this->grabNavbar();
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
    public function getNavbar(){
        return $this->navbar;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNavbarId(): int
    {
        return $this->navbar_id;
    }
    public function getMenuText(): string
    {
        return $this->menu_text;
    }
    public function getMenuLink(): string
    {
        return $this->menu_link;
    }
    public function getStatus(): string
    {
        return $this->status;
    }
    public function createdAt(): string
    {
        return $this->createdAt;
    }
    public function modifiedAt(): string
    {
        return $this->modifiedAt;
    }
}
