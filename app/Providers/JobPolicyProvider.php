<?php

namespace App\Providers;

use App\Models\GuppaJob;
use App\Policies\JobPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class JobPolicyProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::policy(GuppaJob::class, JobPolicy::class);
        Gate::define('viewAny_job', [JobPolicy::class, 'viewAny_job']);
        Gate::define('view_job', [JobPolicy::class, 'view_job']);
        Gate::define('viewAny_appliedJob', [JobPolicy::class, 'viewAny_appliedJob']);
        Gate::define('viewAny_freelancer_appliedJobs', [JobPolicy::class, 'viewAny_freelancer_appliedJobs']);
        Gate::define('viewAny_client_appliedJob', [JobPolicy::class, 'viewAny_client_appliedJob']);
        Gate::define('view_AppliedJob', [JobPolicy::class, 'view_AppliedJob']);
        Gate::define('view_freelancer_AppliedJob', [JobPolicy::class, 'view_freelancer_AppliedJob']);
        Gate::define('view_my_jobs', [JobPolicy::class, 'view_my_jobs']);
        Gate::define('view_recommended_jobs', [JobPolicy::class, 'view_recommended_jobs']);
        Gate::define('view_contracts', [JobPolicy::class, 'view_contracts']);
        Gate::define('view_client_contracts', [JobPolicy::class, 'view_client_contracts']);
        Gate::define('view_contract', [JobPolicy::class, 'view_contract']);
        Gate::define('view_client_contract', [JobPolicy::class, 'view_client_contract']);
        Gate::define('can_apply', [JobPolicy::class, 'can_apply']);
        Gate::define('update_status', [JobPolicy::class, 'update_status']);
        Gate::define('update_progress', [JobPolicy::class, 'update_progress']);
        Gate::define('show_job', [JobPolicy::class, 'show_job']);
        Gate::define('create_job', [JobPolicy::class, 'create_job']);
        Gate::define('update_job', [JobPolicy::class, 'update_job']);
        Gate::define('delete_job', [JobPolicy::class, 'delete_job']);
        Gate::define('approve_job', [JobPolicy::class, 'approve_job']);
        Gate::define('reject_job', [JobPolicy::class, 'reject_job']);
        Gate::define('restore_job', [JobPolicy::class, 'restore_job']);
        Gate::define('forceDelete_job', [JobPolicy::class, 'forceDelete_job']);
    }
}
