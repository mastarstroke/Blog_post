<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckJwtSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('jwt')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}

