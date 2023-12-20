<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (explode('/', $request->path())[0] == 'api') {
            throw new AuthenticationException('Unauthenticated');
        }
        return $request->expectsJson() ? null : route('login');
    }
}
