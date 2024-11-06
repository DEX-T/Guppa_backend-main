<?php

namespace App\Policies;

use App\enums\UserRoles;
use App\Models\User;
use Illuminate\Http\Response;

class SuperPolicy
{
    public function IsSuperadmin(User $user){
        dd($user->role);
        return $user->role == UserRoles::SUPERADMIN 
        ? Response::allow()
        : Response::denyWithStatus(401);
    }


    public function IsAdmin(User $user){
        return in_array($user->role, [UserRoles::SUPERADMIN, UserRoles::ADMIN]) 
        ? Response::allow()
        : Response::denyWithStatus(401);
    }
}
