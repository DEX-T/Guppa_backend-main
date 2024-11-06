<?php

namespace App\Domain\Entities\YearsOfExperience;

use App\Models\GigList;
use App\Models\YearOfExperience;


class YearsOfExperienceEntity
{

    private int $id;
    private string $yearOfExperience;
    private string $status;
    private string $createdAt;
    private string $modifiedAt;


    public function __construct(YearOfExperience $yearOfExperience)
    {
        $this->id = $yearOfExperience->id;
        $this->yearOfExperience = $yearOfExperience->year_of_experience;
        $this->status = $yearOfExperience->status;
        $this->createdAt = $yearOfExperience->created_at;
        $this->modifiedAt = $yearOfExperience->updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

   public function getYearOfExperience(): string{
        return $this->yearOfExperience;
   }

   public function getStatus(): string{
        return $this->status;
   }
   public function getCreatedAt(): string{
        return $this->createdAt;
   }
   public function getModifiedAt(): string{
        return $this->modifiedAt;
   }
}
