<?php

namespace App\Domain\DTOs\Request\BidPaymentConfig;

class BidPaymentConfigRequestDto
{
    public float $amount;

    public function __construct(float $amount)
    {
        $this->amount = $amount;
    }
}
