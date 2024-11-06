<?php

namespace App\Http\Middleware;

use App\Helpers\UserRoleHelper;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class AuditLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        if(Auth::check()) {
            $user = User::findOrFail(Auth::id());
            $headers = $request->header();
            Log::info('Audit Request', [
                'action' => explode('@', $request->route()->getAction()["controller"])[1],
                'user_id' => Auth::id(),
                'target_id' => $request->getClientIp(),
                'target_type' => "controller",
                'visited_route' => $request->getPathInfo(),
                'controller_method' => explode('@', $request->route()->getAction()["controller"])[1],
                'details' => json_encode($request->all()),
                'request_headers' => [
                    'user-agent' => $headers['user-agent'],
                    'referer'    => $headers['referer'],
                ],
            ]);
            // Check if the request is an admin action
            Log::info("User role ", [UserRoleHelper::isSuperAdmin($user)]);

            if (UserRoleHelper::isSuperAdmin($user)) {
                \App\Models\AuditLog::create([
                    'action' => explode('@', $request->route()->getAction()["controller"])[1],
                    'user_id' => Auth::id(),
                    'visited_route' => $request->getPathInfo(),
                    'controller_method' => explode('@', $request->route()->getAction()["controller"])[1],
                    'details' => json_encode($request->all()),
                    'request_headers' => json_encode([
                        'user-agent' => $headers['user-agent'],
                        'referer'    => $headers['referer']
                    ])
                ]);
            }
        }
        return $response;
    }



}
