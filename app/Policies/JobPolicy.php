<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MyJob;
use App\enums\UserRoles;
use App\Models\GuppaJob;
use App\Models\AppliedJob;
use App\Helpers\UserRoleHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\Response;

class JobPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if (UserRoleHelper::isSuperAdmin($user)) {
            return true;
        }
    
        return null;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny_job(User $user)
    {
        if (in_array($user->role, [UserRoles::SUPERADMIN, UserRoles::ADMIN])) {
            Log::info('Inside policy: with allow ' . $user->role);
            return Response::allow("authorized", 200);
        } else {
            Log::info('Inside policy: with deny' . $user->role);
            return Response::denyWithStatus(401, "You are not authorized to perform this action");
        }
    }

     /**
     * Determine whether the user can view the model.
     */
    public function view_job(User $user)
    {
        return in_array($user->role, [
            UserRoles::FREELANCER,
             UserRoles::CLIENT, 
             UserRoles::ADMIN, 
             UserRoles::SUPERADMIN])
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }


    /**
     * Determine whether the user can view any models.
     */
    public function viewAny_appliedJob(User $user)
    {
        return in_array($user->role, [UserRoles::ADMIN, UserRoles::SUPERADMIN])
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny_freelancer_appliedJobs(User $user)
    {
        return in_array($user->role, [UserRoles::FREELANCER])
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny_client_appliedJob(User $user)
    {
        return in_array($user->role, [UserRoles::CLIENT])
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view_AppliedJob(User $user)
    {
        return in_array($user->role, [
             UserRoles::CLIENT, 
             UserRoles::ADMIN, 
             UserRoles::SUPERADMIN,
             ])
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view_freelancer_AppliedJob(User $user, AppliedJob $appliedJob)
    {
        return $user->role == UserRoles::FREELANCER && $appliedJob->user_id == $user->id
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

   /**
     * Determine whether the user can view the model.
     */
    public function view_my_jobs(User $user)
    {
        return $user->role == UserRoles::CLIENT
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }


    /**
     * Determine whether the user can view the model.
     */
    public function view_recommended_jobs(User $user)
    {
        return $user->role == UserRoles::FREELANCER
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view_contracts(User $user)
    {
        return $user->role === UserRoles::FREELANCER
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view_client_contracts(User $user)
    {
        return $user->role == UserRoles::CLIENT
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view_contract(User $user, MyJob $contract)
    {
        return $user->role == UserRoles::FREELANCER && $user->id == $contract->user_id
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view_client_contract(User $user, MyJob $contract)
    {
        return $user->role == UserRoles::CLIENT && $user->id == $contract->client_id
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }


    /**
     * Determine whether the user can view the model.
     */
    public function can_apply(User $user)
    {
        return $user->role == UserRoles::FREELANCER
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function update_status(User $user, MyJob $contract)
    {
        return in_array($user->role, [UserRoles::FREELANCER, UserRoles::CLIENT]) 
        && $user->id == $contract->user_id 
        || $user->id == $contract->client_id
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function update_progress(User $user, MyJob $contract)
    {
        return $user->role == UserRoles::FREELANCER 
        && $user->id == $contract->user_id 
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show_job(User $user, GuppaJob $guppaJob)
    {
        return in_array($user->role, [
             UserRoles::CLIENT, 
             UserRoles::SUPERADMIN]) || $user->id == $guppaJob->user_id
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create_job(User $user)
    {
        return $user->role == UserRoles::CLIENT || $user->role == UserRoles::SUPERADMIN
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update_job(User $user, GuppaJob $guppaJob)
    {
        return $user->role == UserRoles::CLIENT || $guppaJob->user_id == $user->id
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete_job(User $user, GuppaJob $guppaJob)
    {
        return $user->role == UserRoles::CLIENT || $guppaJob->user_id == $user->id
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function approve_job(User $user, GuppaJob $job)
    {
        return $user->role == UserRoles::CLIENT
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function reject_job(User $user)
    {
        return $user->role == UserRoles::CLIENT 
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore_job(User $user, GuppaJob $guppaJob)
    {
        return in_array($user->role, [
            UserRoles::ADMIN, 
            UserRoles::SUPERADMIN])
       ? Response::allow(200) 
       : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete_job(User $user, GuppaJob $guppaJob)
    {
        return in_array($user->role, [
             UserRoles::ADMIN, 
             UserRoles::SUPERADMIN])
        ? Response::allow(200) 
        : Response::denyWithStatus(401);
    }
}
