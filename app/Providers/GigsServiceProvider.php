<?php

namespace App\Providers;

use App\Domain\Interfaces\Gigs\IGigsService;
use App\Services\Gigs\GigsService;
use Illuminate\Support\ServiceProvider;

class GigsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IGigsService::class, GigsService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
