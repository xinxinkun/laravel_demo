<?php

use App\Exceptions\AuthenticateException;
use App\Http\Exceptions\AuthException;
use App\Http\Middleware\ApiResponseFormat;
use App\Http\Middleware\Authenticate;
use App\Http\Responses\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(ApiResponseFormat::class);
        $middleware->alias([
            'auth' => Authenticate::class
        ]);
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $e, Request $request) {
            return response(new ApiResponse(
                $e->validator->errors()->toArray(),
                400,
                'Validation failed'
            ));
        });

        $exceptions->render(function (AuthException $e, Request $request) {
            $message = json_encode($e->getMessage(), JSON_UNESCAPED_UNICODE);
            Log::info($message);
            return response(new ApiResponse(
                null,
                $e->getCode(),
                $message,
            ));
        });
    })->create();
