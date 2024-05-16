<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;

class ApiResponseFormat extends Middleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        return $response;
    }

}
