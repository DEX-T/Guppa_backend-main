<?php

namespace App\Services\DocumentConfig;

use App\Domain\DTOs\ApiResponseDto;
use App\Domain\DTOs\Request\DocumentConfig\DocumentTypeConfigurationRequestDto;
use App\Domain\DTOs\Response\DocumentConfig\DocumentTypeConfigurationResponseDto;
use App\Domain\Entities\DocumentTypeEntity;
use App\Domain\Interfaces\DocumentConfig\IDocumentConfigService;
use App\enums\HttpStatusCode;
use App\Http\Requests\DocumentTypeConfigurationRequest;
use App\Models\DocumentTypeConfiguration;
use Carbon\Carbon;

class DocumentConfigService implements IDocumentConfigService
{

    public function addDocumentType($data): ApiResponseDto
    {
        try {
            $dto = new DocumentTypeConfigurationRequestDto($data);
            DocumentTypeConfiguration::create([
                'name' => $dto->name,
                'description' => $dto->description,
                'created_at' => Carbon::now()
            ]);
            return new ApiResponseDto(true, "Document type created", HttpStatusCode::OK);
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function removeDocumentType(int $id): ApiResponseDto
    {
        try {
            $documentType = DocumentTypeConfiguration::where('id', $id)->first();
            if ($documentType) {
                $documentType->delete();
                return new ApiResponseDto(true, "Document type deleted", HttpStatusCode::OK);
            }
            return new ApiResponseDto(false, "Document type Not Found", HttpStatusCode::NOT_FOUND);

        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function getAllDocumentTypes(): ApiResponseDto
    {
        try {
            $docTypes = DocumentTypeConfiguration::all();
            if($docTypes->isNotEmpty()){
                $dto = $docTypes->map(function ($docType){
                    $docTypeEntity = new DocumentTypeEntity($docType);
                    return new DocumentTypeConfigurationResponseDto($docTypeEntity);
                });
                return new ApiResponseDto(true, "Document type fetched", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(true, "Document types Not Found", HttpStatusCode::NOT_FOUND);

            }
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }
}
