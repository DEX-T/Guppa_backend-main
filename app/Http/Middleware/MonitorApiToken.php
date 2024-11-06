<?php

namespace App\Http\Middleware;

use App\Models\APILog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MonitorApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Start time
        $startTime = microtime(true);

        // Get the response
        $response = $next($request);

        // Calculate the duration
        $duration = microtime(true) - $startTime;

        // Get the user ID, or 'guest' if not authenticated
        $userId = Auth::check() ? Auth::id() : 'guest';
        $token = $request->bearerToken() ?? "guest";
        $agent = $request->header('user_agent');
        // Log the API usage
        Log::info('API Request', [
            'user_id' => $userId,
            'token' => $token,
            'method' => $request->getMethod(),
            'url' => $request->fullUrl(),
            'status' => $response->getStatusCode(),
            'duration' => $duration,
            'ip_address' => $request->ip(),
            'user_agent' => $agent,

        ]);
        // Optionally, store this data in the database
        APILog::create([
            'user_id' => $userId,
            'token' => $token,
            'method' => $request->getMethod(),
            'url' => $request->fullUrl(),
            'status' => $response->getStatusCode(),
            'duration' => $duration,
            'ip_address' => $request->ip(),
            'user_agent' => $agent,
            'created_at' => now(),
        ]);

        return $response;

    }

}
