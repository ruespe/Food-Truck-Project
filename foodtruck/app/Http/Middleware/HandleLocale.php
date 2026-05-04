<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->cookie('foodtruck_locale');

        if (in_array($locale, ['es', 'ca', 'en'], true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
