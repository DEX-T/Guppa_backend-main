<?php

namespace App\Providers;

use App\Services\Navbar\NavbarService;
use Illuminate\Support\ServiceProvider;
use App\Services\Configuration\FooterService;
use App\Services\WhyChooseUs\WhyChoseUsService;
use App\Domain\Interfaces\Navbar\INavbarService;
use App\Services\Testimonial\TestimonialsService;
use App\Services\Testimonial\TestimonialCardService;
use App\Services\DiscoverTalent\DiscoverTalentService;
use App\Domain\Interfaces\Configuration\IFooterService;
use App\Domain\Interfaces\WhyChooseUs\IWhyChoseUsService;
use App\Domain\Interfaces\Testimonial\ITestimonialsService;
use App\Domain\Interfaces\Testimonial\ITestimonialCardService;
use App\Domain\Interfaces\DiscoverTalent\IDiscoverTalentService;

class LandpageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(INavbarService::class, NavbarService::class);
        $this->app->bind(IWhyChoseUsService::class, WhyChoseUsService::class);
        $this->app->bind(IDiscoverTalentService::class, DiscoverTalentService::class);
        $this->app->bind(ITestimonialsService::class, TestimonialsService::class);
        $this->app->bind(ITestimonialCardService::class, TestimonialCardService::class);
        $this->app->bind(IFooterService::class, FooterService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
