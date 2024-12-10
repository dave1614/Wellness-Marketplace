<?php

use App\Http\Middleware\AdminOrMerchantMiddleware;
use App\Http\Middleware\MainAdminMiddleware;
use App\Http\Middleware\MerchantMiddleware;
use App\Http\Middleware\ShopperMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'admin' => MainAdminMiddleware::class,
            'merchant' => MerchantMiddleware::class,
            'admin_or_merchant' => AdminOrMerchantMiddleware::class,
            'shopper' => ShopperMiddleware::class
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->respond(function (Response $response) {
        //     return $response->getStatusCode();
        //     if ($response->getStatusCode() === 894) {
        //         return response()->view('errors.894', [], 500);
        //     }

        //     return $response;
        // });
    })->create();
