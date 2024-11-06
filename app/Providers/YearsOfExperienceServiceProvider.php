<?php

namespace App\Providers;

use App\Domain\Interfaces\YearsOfExperience\IYearsOfExperienceService;
use App\Services\YearsOfExperience\YearsOfExperienceService;
use Illuminate\Support\ServiceProvider;

class YearsOfExperienceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IYearsOfExperienceService::class, YearsOfExperienceService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
