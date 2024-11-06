<?php

namespace App\Domain\Interfaces\Configuration;
use App\Models\Footer;
use Illuminate\Http\Request;


interface IFooterService
{   public function getFooters();
    public function getFooter($id);
    public function createFooter(Request $requestdto);
    public function updateFooter(Request $request, $id);
    public function deleteFooter($id);
    // Define your service interface 
    
    //Copyright region

    public function getAllFooterCopyrights();
    public function getFooterCopyright($id);
    public function createFooterCopyright(Request $request);
    public function updateFooterCopyright(Request $request, $id);
    public function deleteFooterCopyright($id);


    //social media region

    public function getAllFooterSocialMedia();
    public function getFooterSocialMedia($id);
    public function createFooterSocialMedia(Request $request);
    public function updateFooterSocialMedia(Request $request, $id);
    public function deleteFooterSocialMedia($id);
    public function activateFooterSocialMedia($id);
    public function deactivateFooterSocialMedia($id);
    public function getFooterSocialMediaFE();
}