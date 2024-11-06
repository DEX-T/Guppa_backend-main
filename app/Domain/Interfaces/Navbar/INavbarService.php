<?php
namespace App\Domain\Interfaces\Navbar;

use Illuminate\Http\Request;

interface INavbarService
{
    public function createNavType(Request $request);
    public function getAllNavType();
    public function getFullNavs();
    public function getNavTypeById(int $id);
    public function updateNavType(Request $request);
    public function deleteNavType(int $id);


    public function createNavMenu(Request $request);
    public function getAllNavMenu();
    public function getNavMenuById(int $id);
    public function updateNavMenu(Request $request);
    public function deleteNavMenu(int $id);


    public function createNavText(Request $request);
    public function getAllNavText();
    public function getNavTextById(int $id);
    public function getBannerText();
    public function updateNavText(Request $request);
    public function deleteNavText(int $id);
    public function activateNavText(int $id);


    public function createNavButton(Request $request);
    public function getAllNavButton();
    public function getNavButtonById(int $id);
    public function updateNavButton(Request $request);
    public function deleteNavButton(int $id);

    public function upsertLogo(Request $request);
    public function getNavLogo();
    public function deleteNavLogo(int $id);

}
