<?php

namespace App\Providers;

use App\Domain\Interfaces\JobType\IJobTypeService;
use App\Services\JobType\JobTypeService;
use Illuminate\Support\ServiceProvider;

class JobTypeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IJobTypeService::class, JobTypeService::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
