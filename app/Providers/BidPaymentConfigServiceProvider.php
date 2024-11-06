<?php

namespace App\Providers;

use App\Domain\Interfaces\BidPaymentConfig\IBidPaymentConfigService;
use App\Services\BidPaymentConfig\BidPaymentConfigService;
use Illuminate\Support\ServiceProvider;

class BidPaymentConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

             $this->app->bind(IBidPaymentConfigService::class, BidPaymentConfigService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
