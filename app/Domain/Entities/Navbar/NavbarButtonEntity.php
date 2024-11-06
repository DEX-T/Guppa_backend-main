<?php

namespace App\Domain\Entities\Navbar;

use App\Models\Navbar;
use App\Models\NavbarButton;

class NavbarButtonEntity
{

    private int $id;
    private int $navbar_id;
    private string $button_text;
    private string $button_link;
    private string $status;
    private string $createdAt;
    private string $modifiedAt;
    private $navbar;


    public function __construct(NavbarButton $navbarButton)
    {
        $this->id = $navbarButton->id;
        $this->navbar_id = $navbarButton->navbar_id;
        $this->button_text = $navbarButton->button_text;
        $this->button_link = $navbarButton->button_link;
        $this->status = $navbarButton->status;
        $this->createdAt = $navbarButton->created_at;
        $this->modifiedAt = $navbarButton->updated_at;
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
    public function getButtonText(): string
    {
        return $this->button_text;
    }
    public function getButtonLink(): string
    {
        return $this->button_link;
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
