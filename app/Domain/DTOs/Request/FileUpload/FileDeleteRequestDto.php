<?php

 namespace App\Domain\DTOs\Request\FileUpload;

class FileDeleteRequestDto
{
    public string $fileName;
    
    public function __construct(string $fileName){
        $this->fileName = $fileName;
    }

    // Define your DTO properties and methods here
}