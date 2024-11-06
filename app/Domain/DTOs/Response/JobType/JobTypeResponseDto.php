<?php

namespace App\Domain\DTOs\Response\JobType;

use App\Domain\Entities\JobType\JobTypeEntity;
use App\Domain\Entities\JobType\SupportTicketEntity;


class JobTypeResponseDto
{
    public int $id;

    public string $type;

    public string $description;

    public string $status;

    public string $createdAt;
    public string $modifiedAt;


    public function __construct(JobTypeEntity $jobTypeEntity)
    {
        $this->id = $jobTypeEntity->getId();
        $this->type = $jobTypeEntity->getType();
        $this->description = $jobTypeEntity->getDescription();
        $this->status = $jobTypeEntity->getStatus();
        $this->createdAt = $jobTypeEntity->getCreatedAt();
        $this->modifiedAt = $jobTypeEntity->getModifiedAt();
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->createdAt,
            'updated_at' => $this->modifiedAt,
        ];
    }
}
