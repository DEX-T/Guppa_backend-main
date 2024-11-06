<?php

namespace App\Http\Middleware\TwoFA;

use App\enums\HttpStatusCode;
use App\Models\TwoFaTracker;
use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TwoFa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check() && Auth::user()->is_2fa_enabled){
            $user = User::find(auth()->user()->id);
           $twofa = TwoFaTracker::where('user_id', $user->id)->first();
            if(!$twofa->is_verified){
                return response()->json([
                    'success' =>false,
                    'message' => '2FA is enabled for this account, please verify your 2FA',
                    'url' => "http://localhost:3000/prompt"
                ], HttpStatusCode::TWO_FA_REQUIRED);
              
            }
            
        }
        return $next($request);
    } 
    
}
