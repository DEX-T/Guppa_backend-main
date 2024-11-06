<?php

namespace App\Http\Controllers\File;

use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Domain\DTOs\ApiResponseDto;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Domain\Interfaces\File\IFileService;
use App\Domain\DTOs\Request\FileUpload\FileDeleteRequestDto;
use App\Domain\DTOs\Request\FileUpload\FileUploadRequestDto;
use App\Domain\DTOs\Response\FileUpload\FileUploadResponseDto;


class FileController extends Controller
{
    private IFileService $_fileService;

    function __construct(IFileService $fileService)
    {
        $this->_fileService = $fileService;
    }

    /**
     * @OA\Post(
     *     path="/api/file/upload",
     *     operationId="uploadFile",
     *     tags={"File"},
     *     summary="Upload new file",
     *     description="Uploads new file to directory and return path for DB",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *      *          required={"file"},
     *                 @OA\Property(property="file", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *     ),
     * )
     */
    public function uploadFile(Request $request)
    { 
        
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' =>  "Validation Error",
                'error' => $validator->errors()->toArray()
            ], HttpStatusCode::VALIDATION_ERROR);
            
        }

        $file = $request->file('file');
        $fileDto = new FileUploadRequestDto($file);
        $path = $this->_fileService->upload($fileDto);
        // Return the uploaded file path
        if($path != null){
            $fileResponseDto = new FileUploadResponseDto($path);
            return response()->json([
                'success' => true,
                'message' => "File Uploaded successfully",
                'data' => $fileResponseDto->toArray()
            ], HttpStatusCode::OK);
        }
        return response()->json([
            'success' => $path->status,
            'message' => $path->message,
        ], $path->code);
    }


    
    /**
     * @OA\Get(
     *     path="/api/file/download-file",
     *     operationId="downloadFile",
     *     tags={"File"},
     *     summary="download new file",
     *     description="downloads new file",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"filePath"},
     *             @OA\Property(property="filePath", type="string", example="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *     ),
     * )
     */

    public function downloadFile(Request $request)
    {
        // Get the file path from the request
        Log::info('Checking file request: ', [$request]);
        $filePath = $request->json('filePath');
        Log::info("file path", [$filePath]);
        Log::info('Checking file path 1: ' . "uploads/" . $filePath);
        if (!Storage::disk('public')->exists("uploads/".$filePath)) {
            Log::info("file path", [$filePath]);
            Log::info('Checking file path: ' . "uploads/" . $filePath);
            return response()->json(['error' => 'File not found'], 404);
        }
        // Get the full path to the file
        $fullPath = Storage::disk('public')->path("uploads/".$filePath);
        Log::info("file path 3", [$fullPath]);
        // Return the file as a download response
        return response()->download($fullPath);
    }

     /**
     * @OA\Delete(
     *     path="/api/file/delete",
     *     operationId="deleteFile",
     *     tags={"File"},
     *     summary="Delete",
     *     description="delete file from directory and database",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"path"},
     *             @OA\Property(property="path", type="string", format="string", example="0000/file.png")
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     *
     * )
     */
    public function deleteFile(Request $request)
    {
        $path = $request->input('path');
        $fileDto = new FileDeleteRequestDto($path);
        $deleted = $this->_fileService->deleteFile($fileDto);

        if ($deleted->status) {
            return response()->json([
                'success' => $deleted->status,
                'message' => $deleted->message,
            ], $deleted->code);
        } else {
            return response()->json([
                'success' => $deleted->status,
                'message' => $deleted->message,
            ], $deleted->code);
        }
    }

}
