<?php

namespace App\Providers;

use App\Models\GuppaJob;
use App\Policies\JobPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register()
    {
       
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
     

    }
}