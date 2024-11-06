<?php

use App\Http\Middleware\AuditLog;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsClient;
use App\Http\Middleware\IsClienVerified;
use App\Http\Middleware\IsEmailVerified;
use App\Http\Middleware\IsFreelancer;
use App\Http\Middleware\IsSuperuser;
use App\Http\Middleware\MonitorApiToken;
use App\Http\Middleware\ReloginIfTokenIsValid;
use App\Http\Middleware\TwoFA\TwoFa;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
            '2fa' => TwoFa::class,
            'is_email_verified' => IsEmailVerified::class,
            'is_superuser' => IsSuperuser::class,
            'is_admin' => IsAdmin::class,
            'is_client' => IsClient::class,
            'is_freelancer' => IsFreelancer::class,
            're_login' => ReloginIfTokenIsValid::class,
            'is_client_verified' => IsClienVerified::class,
            'monitor_api_usage' => MonitorApiToken::class,
            'audit_log' => AuditLog::class,

        ]);
        // $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

    $app->singleton(
        Illuminate\Contracts\Debug\ExceptionHandler::class,
        App\Exceptions\Handler::class
    );



