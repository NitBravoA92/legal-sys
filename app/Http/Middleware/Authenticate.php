<?php

namespace App\Http\Middleware;

use App\Models\AppNotifications;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $backLogin = '/' . explode('/', $request->path())[0] . '/login';
            return url($backLogin);
        }
    }
}
