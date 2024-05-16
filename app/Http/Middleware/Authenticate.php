<?php

namespace App\Http\Middleware;

use App\Exceptions\AuthenticateException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticateException(
            'customized auth exception.',
            1
        );
    }
}
