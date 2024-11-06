<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

use Symfony\Component\HttpFoundation\Response;

class CustomEnsureFrontendRequestsAreStateful extends EnsureFrontendRequestsAreStateful
{

    public function handle($request,  $next): Response
    {
        if (! $request->expectsJson() && ! $request->hasSession()) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }
        return parent::handle($request, $next);
    }
}
