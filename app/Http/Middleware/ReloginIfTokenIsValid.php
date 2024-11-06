<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ReloginIfTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token  = $request->bearerToken();
    if(!Auth::check()){
        if($token){
            Log::info("Auth token trying to relogin ");
            $accessToken = PersonalAccessToken::findToken($token);
            if($accessToken && !$accessToken->isExpired()){
                Log::info("Access token exists and has not expired");
                $user = $accessToken->tokenable_id;
                Auth::login($user);
                Log::info("User token relogged in");
                return response()->json(['message' => 'Token refreshed'], Response::HTTP_OK);
            }
            return response()->json(['message' => 'Unauthorized please login'], Response::HTTP_UNAUTHORIZED);
        }else{
            return response()->json(['message' => 'Unauthorized please login'], Response::HTTP_UNAUTHORIZED);
        }
     }
     Log::info("No Auth tokenNo re login");
     return $next($request);

    }
}
