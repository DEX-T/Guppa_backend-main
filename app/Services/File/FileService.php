<?php

namespace App\Services\File;

use App\Domain\DTOs\ApiResponseDto;
use App\Domain\DTOs\Request\FileUpload\FileDeleteRequestDto;
use App\Domain\DTOs\Request\FileUpload\FileUploadRequestDto;
use App\Domain\Interfaces\File\IFileService;
use App\enums\HttpStatusCode;
use App\Models\AllUploadedFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class FileService implements IFileService
{

    public function upload(FileUploadRequestDto $requestDto)
    {
        try{
            $filename = $this->generateFilename($requestDto->file);
            $savePath = "public/uploads". '/' . $filename;
            $dbPath = $filename;
             
            // Log the directory path for debugging
            Log::info('Directory Path: ' . $savePath);
            if($requestDto->file->storeAs($savePath)){
                // Log the directory path for debugging
              Log::info('File Uploaded: ' . $savePath);
                AllUploadedFile::create([
                    'file' => $dbPath,
                    'from' => 'file controller'
                ]);
            }
           return $dbPath;
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error :" . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function generateFilename(UploadedFile $file): ApiResponseDto|string
    {
        try{
            $extension = $file->getClientOriginalExtension();
            $filename = "guppa_".Str::random(32). '.'. $extension;
            return $filename;
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error :" . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteFile(FileDeleteRequestDto $requestDto): ApiResponseDto
    {
      try {
           $dbFold = explode('/', $requestDto->fileName);
            $directoryPath = "uploads/" .$dbFold[0];

            // Log the directory path for debugging
            Log::info('Directory Path: ' . $directoryPath);

            if(Storage::disk("public")->exists($directoryPath)){
                // Log the existence of the directory
                Log::info('Directory exists: ' . $directoryPath);
                if(File::deleteDirectory(Storage::disk("public")->path($directoryPath))){
                 // Log successful deletion
                    Log::info('Directory deleted: ' . $directoryPath);
                    AllUploadedFile::where('file', $requestDto->fileName)->delete();
                }else{
                 // Log successful deletion
                     Log::info('Directory deleted: ' . $directoryPath);
                }
                return new ApiResponseDto(true, "File deleted!", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error deleting file!", HttpStatusCode::NOT_FOUND);
            }
      } catch (\Exception $e) {
        return new ApiResponseDto(false, "Server Error :" . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
      }
    }
}
