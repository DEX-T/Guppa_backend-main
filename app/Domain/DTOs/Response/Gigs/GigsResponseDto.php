<?php

namespace App\Domain\DTOs\Response\Gigs;

use App\Domain\Entities\Gigs\GigsEntity;
use App\Models\GigList;

class GigsResponseDto
{
    public int $id;

    public string $name;

    public string $description;

    public string $status;

    public string $createdAt;
    public string $modifiedAt;


    public function __construct(GigsEntity $gigList)
    {
        $this->id = $gigList->getId();
        $this->name = $gigList->getName();
        $this->description = $gigList->getDescription();
        $this->status = $gigList->getStatus();
        $this->createdAt = $gigList->getCreatedAt();
        $this->modifiedAt = $gigList->getModifiedAt();
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->createdAt,
            'updated_at' => $this->modifiedAt,
        ];
    }
}
