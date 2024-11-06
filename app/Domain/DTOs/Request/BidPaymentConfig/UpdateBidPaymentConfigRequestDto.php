<?php

namespace App\Domain\DTOs\Request\BidPaymentConfig;

class UpdateBidPaymentConfigRequestDto
{
    public int $id;
    public int $amount;
    public function __construct(int $id, string $amount)
    {
        $this->id = $id;
        $this->amount = $amount;

    }

}
