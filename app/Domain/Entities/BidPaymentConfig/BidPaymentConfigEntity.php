<?php

namespace App\Domain\Entities\BidPaymentConfig;

use App\Models\BidPaymentConfig;

class BidPaymentConfigEntity
{

    private int $id;
    private int $amount;
    private string $createdAt;
    private  $modifiedAt;

    public function __construct(BidPaymentConfig $config)
    {
        $this->id = $config->id;
        $this->amount = $config->amount;
        $this->createdAt = $config->created_at;
        $this->modifiedAt = $config->updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

   public function getAmount(): int{
        return $this->amount;
   }

   public function getCreatedAt()
   {
        return $this->createdAt;
   }
   public function getModifiedAt()
   {
        return $this->modifiedAt;
   }
}
