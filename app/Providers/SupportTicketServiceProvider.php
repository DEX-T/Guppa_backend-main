<?php

namespace App\Providers;
use App\Domain\Interfaces\SupportTicket\ISupportTicketService;
use App\Services\SupportTicket\SupportTicketService;
use Illuminate\Support\ServiceProvider;

class SupportTicketServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ISupportTicketService::class, SupportTicketService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
