<?php

namespace App\Policies;

use App\enums\UserRoles;
use App\Models\Navbar;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class NavbarPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny_navbar(?User $user)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view_navbar(User $user, ?Navbar $navbar)
    {
        return  in_array($user->role, [UserRoles::ADMIN, UserRoles::SUPERADMIN]) 
        ? Response::allow() 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create_navbar(User $user)
    {
        Log::info("user ", [$user->role]);
        return  in_array($user->role, [UserRoles::SUPERADMIN, UserRoles::ADMIN]) 
        ? Response::allow() 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update_navbar(User $user, ?Navbar $navbar)
    {
        return  in_array($user->role, [UserRoles::ADMIN, UserRoles::SUPERADMIN]) 
        ? Response::allow() 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete_navbar(User $user, ?Navbar $navbar)
    {
        return  in_array($user->role, [UserRoles::ADMIN, UserRoles::SUPERADMIN]) 
        ? Response::allow() 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore_navbar(User $user, ?Navbar $navbar)
    {
        return  in_array($user->role, [UserRoles::ADMIN, UserRoles::SUPERADMIN]) 
        ? Response::allow() 
        : Response::denyWithStatus(401);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete_navbar(User $user, ?Navbar $navbar)
    {
        return  in_array($user->role, [UserRoles::ADMIN, UserRoles::SUPERADMIN]) 
        ? Response::allow() 
        : Response::denyWithStatus(401);
    }
}
