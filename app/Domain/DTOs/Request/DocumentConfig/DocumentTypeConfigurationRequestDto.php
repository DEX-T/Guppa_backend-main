<?php

namespace App\Domain\DTOs\Request\DocumentConfig;

class DocumentTypeConfigurationRequestDto
{
    public string $name;
    public $description;

    function __construct(array $validated)
    {
        $this->name = $validated['name'];
        $this->description = $validated['description'];
    }
}
