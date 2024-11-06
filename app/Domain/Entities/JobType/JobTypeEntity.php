<?php

namespace App\Domain\Entities\JobType;

use App\Models\JobTypeList;


class JobTypeEntity
{

    private int $id;
    private string $type;
    private string $description;
    private string $status;
    private string $createdAt;
    private string $modifiedAt;


    public function __construct(JobTypeList $jobTypeList)
    {
        $this->id = $jobTypeList->id;
        $this->type = $jobTypeList->type;
        $this->description = $jobTypeList->description;
        $this->status = $jobTypeList->status;
        $this->createdAt = $jobTypeList->created_at;
        $this->modifiedAt = $jobTypeList->updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

   public function getType(): string{
        return $this->type;
   }
   public function getDescription(): string{
        return $this->description;
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
