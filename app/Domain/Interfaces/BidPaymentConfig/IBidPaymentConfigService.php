<?php
namespace App\Domain\Interfaces\BidPaymentConfig;

use Illuminate\Http\Request;

interface IBidPaymentConfigService
{

    public function createBidPaymentConfig(Request $request);
    public function getBidPaymentConfig();




}
