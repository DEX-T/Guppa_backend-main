<?php

 namespace App\Domain\DTOs\Response\FileUpload;

use Illuminate\Http\UploadedFile;

class FileUploadResponseDto
{
    public  $fileName;
    
    public function __construct($fileName){
        $this->fileName = $fileName;
    }

    public function toArray(){
        return [
            'filename' => $this->fileName
        ];
    }

}
