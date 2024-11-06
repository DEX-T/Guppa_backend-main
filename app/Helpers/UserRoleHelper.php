<?php

namespace App\Helpers;

use App\enums\UserRoles;
use App\Models\User;
use App\Domain\Interfaces\Configuration\IConfigurationService;
use App\Models\Ability;

class UserRoleHelper
{

    /**
     * Check if the user is a superadmin.
     *
     * @param User $user
     * @return bool
     */
    public static function isSuperAdmin(User $user)
    {
        return $user->role ===  UserRoles::SUPERADMIN;
    }
    /**
     * Check if the user is an admin.
     *
     * @param User $user
     * @return bool
     */
    public static function isAdmin(User $user)
    {
        return $user->role === UserRoles::ADMIN;
    }

    /**
     * Check if the user is a client.
     *
     * @param User $user
     * @return bool
     */
    public static function isClient(User $user)
    {
        return $user->role === UserRoles::CLIENT;
    }

    /**
     * Check if the user is a freelancer.
     *
     * @param User $user
     * @return bool
     */
    public static function isFreelancer(User $user)
    {
        return $user->role === UserRoles::FREELANCER;
    }


}
