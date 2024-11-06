<?php

namespace App\Providers;

use App\Models\Chat;
use App\Models\Navbar;
use App\Policies\ChatPolicy;
use App\Policies\NavbarPolicy;
use App\Services\Skill\SkillService;
use Illuminate\Support\Facades\Gate;
use App\Services\Reports\ReportService;
use Illuminate\Support\ServiceProvider;
use App\Services\Monitor\MonitorService;
use App\Services\Category\CategoryService;
use App\Services\Settings\SettingsService;
use App\Services\Analytics\AnalyticsService;
use App\Services\Dashboard\DashboardService;
use App\Services\Messaging\MessagingService;
use App\Domain\Interfaces\Skill\ISkillService;
use App\Services\Reviews\RateFreelancerService;
use App\Domain\Interfaces\Reports\IReportService;
use App\Services\Invites\InviteFreelancerService;
use App\Services\Transactions\TransactionService;
use App\Domain\Interfaces\Monitor\IMonitorService;
use App\Services\Notification\NotificationService;
use App\Services\Verification\VerificationService;
use App\Domain\Interfaces\Category\ICategoryService;
use App\Domain\Interfaces\Settings\ISettingsService;
use App\Domain\Interfaces\Analytics\IAnalyticsService;
use App\Domain\Interfaces\Dashboard\IDashboardService;
use App\Domain\Interfaces\Messaging\IMessagingService;
use App\Services\DocumentConfig\DocumentConfigService;
use App\Domain\Interfaces\Reviews\IRateFreelancerService;
use App\Http\Controllers\Settings\NotificationController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Domain\Interfaces\Invites\IInviteFreelancerService;
use App\Domain\Interfaces\Transactions\ITransactionService;
use App\Domain\Interfaces\Notification\INotificationService;
use App\Domain\Interfaces\Verification\IVerificationService;
use App\Domain\Interfaces\DocumentConfig\IDocumentConfigService;

class GeneralServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ITransactionService::class, TransactionService::class);
        $this->app->bind(IMessagingService::class, MessagingService::class);
        $this->app->bind(ISettingsService::class, SettingsService::class);
        $this->app->bind(IInviteFreelancerService::class, InviteFreelancerService::class);
        $this->app->bind(IRateFreelancerService::class, RateFreelancerService::class);
        $this->app->bind(IDocumentConfigService::class, DocumentConfigService::class);
        $this->app->bind(IVerificationService::class, VerificationService::class);
        $this->app->bind(ICategoryService::class, CategoryService::class);
        $this->app->bind(ISkillService::class, SkillService::class);
        $this->app->bind(IAnalyticsService::class, AnalyticsService::class);
        $this->app->bind(IReportService::class, ReportService::class);
        $this->app->bind(INotificationService::class, NotificationService::class);
        $this->app->bind(IMonitorService::class, MonitorService::class);
        $this->app->bind(IDashboardService::class, DashboardService::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::policy(Chat::class, ChatPolicy::class);
        Gate::define('view_chat', [ChatPolicy::class, 'view_chat']);
        Gate::define('delete_chat', [ChatPolicy::class, 'delete_chat']);
        Gate::define('delete_message', [ChatPolicy::class, 'delete_message']);

        Gate::policy(Navbar::class, NavbarPolicy::class);

        Gate::define('view_navbar', [NavbarPolicy::class, 'view_navbar']);
        Gate::define('create_navbar', [NavbarPolicy::class, 'create_navbar']);
        Gate::define('update_navbar', [NavbarPolicy::class, 'update_navbar']);
        Gate::define('delete_navbar', [NavbarPolicy::class, 'delete_navbar']);
        Gate::define('restore_navbar', [NavbarPolicy::class, 'restore_navbar']);
        Gate::define('forceDelete_navbar', [NavbarPolicy::class, 'forceDelete_navbar']);
     }
}
