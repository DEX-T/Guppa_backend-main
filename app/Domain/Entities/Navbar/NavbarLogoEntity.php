<?php
namespace App\Domain\Entities\Navbar;

use App\Models\NavbarLogo;

class NavbarLogoEntity
{

    private int $id;
    private int $navbar_id;
    private string $logo_url;


    public function __construct(NavbarLogo $navbarLogo)
    {
        $this->id = $navbarLogo->id;
        $this->navbar_id = $navbarLogo->navbar_id;
        $this->logo_url = $navbarLogo->logo_url;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNavbarId(): int
    {
        return $this->navbar_id;
    }

    public function getLogoUrl(): string
    {
        return asset("storage/app/public/uploads/".$this->logo_url);
    }
}
