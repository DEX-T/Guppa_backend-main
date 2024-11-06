<?php

namespace App\Domain\Entities;

use App\Models\DocumentTypeConfiguration;
use Carbon\Carbon;

class DocumentTypeEntity
{
    public int $id;
    public string $name;
    public  $description;
    public Carbon $created_at;

    function __construct(DocumentTypeConfiguration $docType)
    {
        $this->id = $docType->id;
        $this->name = $docType->name;
        $this->description = $docType->description;
        $this->created_at = $docType->created_at;

    }

}
