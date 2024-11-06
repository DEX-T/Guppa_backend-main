<?php

namespace App\Http\Middleware;

use Closure;
use App\enums\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsFreelancer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user->role != UserRoles::FREELANCER) {
            abort(response()->json([
                'success' => false,
                'message' => 'You are not authorized to perform this action'
            ], \Illuminate\Http\Response::HTTP_UNAUTHORIZED));
        }
        return $next($request);
    }
}