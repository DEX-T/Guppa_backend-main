<?php

namespace App\Domain\DTOs\Response\YearsOfExperience;
use App\Domain\Entities\YearsOfExperience\YearsOfExperienceEntity;
use App\Models\YearOfExperience;

class YearsOfExperienceResponseDto
{
    public int $id;
    public string $yearOfExperience;

    public string $status;

    public string $createdAt;
    public string $modifiedAt;


    public function __construct(YearsOfExperienceEntity $yearOfExperience)
    {
        $this->id = $yearOfExperience->getId();
        $this->yearOfExperience = $yearOfExperience->getYearOfExperience();
        $this->status = $yearOfExperience->getStatus();
        $this->createdAt = $yearOfExperience->getCreatedAt();
        $this->modifiedAt = $yearOfExperience->getModifiedAt();
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'year_of_experience' => $this->yearOfExperience,
            'status' => $this->status,
            'created_at' => $this->createdAt,
            'updated_at' => $this->modifiedAt,
        ];
    }
}
