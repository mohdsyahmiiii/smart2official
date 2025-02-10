<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StudentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->isStudent()) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Unauthorized access');
    }
} 