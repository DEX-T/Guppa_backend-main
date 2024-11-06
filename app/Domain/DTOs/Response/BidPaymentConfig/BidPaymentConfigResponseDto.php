<?php

namespace App\Domain\DTOs\Response\BidPaymentConfig;
use App\Domain\Entities\BidPaymentConfig\BidPaymentConfigEntity;


class BidPaymentConfigResponseDto
{
    public int $id;
    public int $amount;
    public string $createdAt;
    public  $modifiedAt;


    public function __construct(BidPaymentConfigEntity $bidPaymentConfigEntity)
    {
        $this->id = $bidPaymentConfigEntity->getId();
        $this->amount = $bidPaymentConfigEntity->getAmount();
        $this->createdAt = $bidPaymentConfigEntity->getCreatedAt();
        $this->modifiedAt = $bidPaymentConfigEntity->getModifiedAt();
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'created_at' => $this->createdAt,
            'updated_at' => $this->modifiedAt,
        ];
    }
}
