<?php

namespace App\Domain\Interfaces\DocumentConfig;

use App\Http\Requests\DocumentTypeConfigurationRequest;

interface IDocumentConfigService
{
    public function addDocumentType(array $data);
    public function removeDocumentType(int $id);
    public function getAllDocumentTypes();
}
