<?php

namespace App\Domain\Interfaces\File;

use App\Domain\DTOs\Request\FileUpload\FileDeleteRequestDto;
use Illuminate\Http\UploadedFile;
use App\Domain\DTOs\Request\FileUpload\FileUploadRequestDto;
use App\Domain\DTOs\Response\FileUpload\FileUploadResponseDto;

interface IFileService
{
    public function upload(FileUploadRequestDto $fileUploadRequestDto);
    public function generateFilename(UploadedFile $file);

    public function deleteFile(FileDeleteRequestDto $fileDeleteRequestDto);

}
