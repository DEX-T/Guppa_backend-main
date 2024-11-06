<?php

namespace App\Providers;


use App\Services\Account\UserService;
use Illuminate\Support\ServiceProvider;
use App\Services\Authentication\AuthService;
use App\Domain\Interfaces\Account\IUserService;
use App\Domain\Interfaces\Authentication\IAuthService;
use App\Domain\Interfaces\Onboarding\IFreelancerOnboardingService;
use App\Services\Onboarding\IFreelancerOnboardingService\FreelancerOnboardingService;

class UserRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(IFreelancerOnboardingService::class, FreelancerOnboardingService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
