<?php

namespace App\Domain\DTOs\Response\DocumentConfig;

use App\Domain\Entities\DocumentTypeEntity;
use Carbon\Carbon;

class DocumentTypeConfigurationResponseDto
{
    public int $id;
    public string $name;
    public  $description;
    public Carbon $created_at;

    function __construct(DocumentTypeEntity $docType)
    {
        $this->id = $docType->id;
        $this->name = $docType->name;
        $this->description = $docType->description;
        $this->created_at = $docType->created_at;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at
        ];
    }
}
