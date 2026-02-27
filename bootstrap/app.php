<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust all reverse proxies so Laravel reads X-Forwarded-Proto
        // and generates https:// URLs. Without this, assets load as http://
        // which browsers block as mixed content on the HTTPS portal page.
        // - BVSS LORD | 2026-02-27
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'account.level' => \App\Http\Middleware\CheckAccountLevel::class,
            'restrict.ip' => \App\Http\Middleware\RestrictToIpAddresses::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
