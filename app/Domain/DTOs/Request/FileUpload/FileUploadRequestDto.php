<?php

namespace App\Domain\DTOs\Request\FileUpload;

use Illuminate\Http\UploadedFile;

class FileUploadRequestDto
{
    public UploadedFile $file;
    public string $disk;
    public  $path;

    public function __construct(UploadedFile $file, string $disk = "public",  $path = null){
        $this->file = $file;
        $this->disk = $disk;
        $this->path = $path;
    }
    // Define your DTO properties and methods here
}
