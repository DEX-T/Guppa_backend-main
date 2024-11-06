<?php

namespace App\Http\Middleware;

use App\Domain\DTOs\ApiResponseDto;
use App\Helpers\GeneralHelper;
use App\Helpers\UserRoleHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IsClienVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        Log::info("User role ", [$user->role]);
        Log::info("User is verified ", [GeneralHelper::isClientVerified($user)]);
        if (UserRoleHelper::isClient($user) && !GeneralHelper::isClientVerified($user)){
            abort(response()->json([
                'success' => false,
                'message' => 'You are not verified yet, you can not perform this action!'
            ], \Illuminate\Http\Response::HTTP_UNAUTHORIZED));
        }
        return $next($request);
    }
}
