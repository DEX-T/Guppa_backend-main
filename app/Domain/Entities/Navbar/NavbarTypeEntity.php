<?php

namespace App\Domain\Entities\Navbar;

use App\Models\Navbar;
use App\Models\NavbarLogo;

class NavbarTypeEntity
{
    private int $id;
    private string $type;
    private string $dateCreated;
    private string $dateModified;
    private $NavbarMenus;
    private $NavbarButtons;
    private $NavbarLogo;
    private $NavbarText;

    //
    public function __construct($navbar)
    {
        $this->id = $navbar->id;
        $this->type = $navbar->type;
        $this->dateCreated = $navbar->created_at;
        $this->dateModified = $navbar->updated_at;
        $this->NavbarMenus = $navbar->nav_menus != null ? $navbar->nav_menus->map(function ($navbar) {
            return [
                'id' => $navbar->id,
                'menu_text' => $navbar->menu_text,
                'menu_link' => $navbar->menu_link,
                'status' => $navbar->status
            ];
        })->toArray() : null;
        $this->NavbarButtons = $navbar->nav_buttons != null ? $navbar->nav_buttons->map(function ($navbar) {
            return [
                'id' => $navbar->id,
                'button_text' => $navbar->button_text,
                'button_link' => $navbar->button_link,
                'status' => $navbar->status
            ];
        })->toArray(): null;
        $this->NavbarLogo = $this->getNavLogo($this->id);
        $this->NavbarText = $navbar->nav_texts != null ? $navbar->nav_texts->map(function ($navbar) {
            return [
                'id' => $navbar->id,
                'text' => $navbar->text
            ];
        })->toArray() : null;
    }

    public function getNavLogo($id){
        $logo = NavbarLogo::where('navbar_id', $id)->first();
         if($logo){
            return [
                'id' => $logo->id,
                'logo_url' => asset("storage/app/public/uploads/".$logo->logo_url)
                // 'logo_url' => asset("storage/app/public/uploads/".$logo->logo_url)
            ];
        }else{
            return null;
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }
    public function getDateCreated(): string
    {
        return $this->dateCreated;
    }
    public function getDateModified(): string
    {
        return $this->dateModified;
    }
    public function getMenus()
    {
        return $this->NavbarMenus;
    }
    public function getButtons()
    {
        return $this->NavbarButtons;
    }
    public function getLogo()
    {
        return $this->NavbarLogo;
    }
    public function getText()
    {
        return $this->NavbarText;
    }
}
