<?php

namespace App\Domain\Entities\Gigs;

use App\Models\GigList;


class GigsEntity
{

    private int $id;
    private string $name;
    private string $description;
    private string $status;
    private string $createdAt;
    private string $modifiedAt;


    public function __construct(GigList $gigList)
    {
        $this->id = $gigList->id;
        $this->name = $gigList->name;
        $this->description = $gigList->description;
        $this->status = $gigList->status;
        $this->createdAt = $gigList->created_at;
        $this->modifiedAt = $gigList->updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

   public function getName(): string{
        return $this->name;
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
