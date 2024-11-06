<?php

namespace App\Policies;

use App\Models\User;
use App\enums\UserRoles;
use App\Models\Bid;
use App\Models\FreelancerPortfolio;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny_user(User $user)
    {
        if(in_array($user->role, [UserRoles::SUPERADMIN, UserRoles::ADMIN])){
            Log::info('Inside policy: with allow' . $user->role);
            return Response::allow();
        }else{
            Log::info('Inside policy: with deny' . $user->role);
            return Response::denyWithStatus(401);
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view_user(User $user)
    {
        // Example: Allow if the user has 'view_user' permission or if they are viewing their own profile
        return in_array($user->role, [UserRoles::ADMIN, UserRoles::SUPERADMIN])
        ? Response::allow()
        : Response::denyWithStatus(401, "You do not have permission to perform this action");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view_profile(User $user, User $model)
    {
        // Example: Allow if the user has 'view_user' permission or if they are viewing their own profile
        return in_array($user->role, [UserRoles::ADMIN, UserRoles::SUPERADMIN]) || $user->id == $model->id
        ? Response::allow()
        : Response::denyWithStatus(401, "You do not have permission to perform this action");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create_user(User $user)
    {
      return $user->role == UserRoles::SUPERADMIN
        ? Response::allow()
        : Response::denyWithStatus(401, "You do not have permission to perform this action");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update_user(User $user, User $model)
    {
        return in_array($user->role, [UserRoles::SUPERADMIN]) || $user->id === $model->id
        ? Response::allow()
        : Response::denyWithStatus(401, "You do not have permission to perform this action");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete_user(User $user, User $model)
    {
        return in_array($user->role, [UserRoles::SUPERADMIN])
        ? Response::allow()
        : Response::denyWithStatus(401, "You do not have permission to perform this action");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore_user(User $user, User $model)
    {
        return $user->role == UserRoles::SUPERADMIN
        ? Response::allow()
        : Response::denyWithStatus(401, "You do not have permission to perform this action");
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete_user(User $user, User $model)
    {
        return $user->role == UserRoles::SUPERADMIN
        ? Response::allow()
        : Response::denyWithStatus(401, "You do not have permission to perform this action");
    }


    public function get_bid(User $user){
        return $user->role == UserRoles::FREELANCER
        ? Response::allow()
        : Response::denyWithStatus(401, "You do not have permission to perform this action");
    }

    public function upsert_portfolio(User $user){
        return $user->role == UserRoles::FREELANCER
        ? Response::allow()
        : Response::denyWithStatus(401, "You do not have permission to perform this action");
    }

    public function delete_portfolio(User $user, FreelancerPortfolio $portfolio){
        return $user->role == UserRoles::FREELANCER || $user->id == $portfolio->user_id
        ? Response::allow()
        : Response::denyWithStatus(401, "You do not have permission to perform this action");
    }
    // Determine if user can update profile
}
