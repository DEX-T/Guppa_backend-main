<?php

 namespace App\Domain\DTOs\Request\ProfilePhoto;

class ProfileUploadRequestDto
{
    public string $image_path;

    public function __construct(string $image_path){
        $this->image_path = $image_path;
    }
    // Define your DTO properties and methods here
}