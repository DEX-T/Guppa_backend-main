<?php

namespace App\Http\Middleware;

use Closure;
use App\enums\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IsSuperuser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       if(Auth::check()){
            $user = Auth::user();
            Log::info("user role ", [$user->role]);
            if(!in_array($user->role, [UserRoles::SUPERADMIN, UserRoles::ADMIN])) {
                Log::info("user role not superuser or admin", [$user->role]);
                abort(response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to perform this action, please contact admin'
                ], \Illuminate\Http\Response::HTTP_UNAUTHORIZED));
            }

       }
        return $next($request);
    }
}
