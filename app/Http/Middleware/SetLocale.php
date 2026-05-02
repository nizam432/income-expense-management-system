<?php

namespace App\Http\Middleware;

use Closure;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        \Log::info('SetLocale middleware executed | session locale: '.session('locale'));

        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        }
        
        return $next($request);
    }
}
