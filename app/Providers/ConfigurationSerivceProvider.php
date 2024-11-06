<?php

namespace App\Providers;

use App\Domain\Interfaces\Configuration\IConfigurationService;
use App\Domain\Interfaces\Job\IGuppaJobService;
use App\Services\Configuration\ConfigurationService;
use App\Services\Job\GuppaJobService;
use Illuminate\Support\ServiceProvider;

class ConfigurationSerivceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IConfigurationService::class, ConfigurationService::class);
        $this->app->bind(IGuppaJobService::class, GuppaJobService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
