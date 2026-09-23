<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LocaleAwareCaching
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        $locale = App::getLocale();
        $response->header('Vary', 'Accept-Language, Cookie');
        $response->header('X-Locale', $locale);
        // Session-selected languages must be revalidated to prevent stale translated HTML.
        if (Session::has('locale')) {
            $response->headers->set('Cache-Control', 'private, max-age=0, must-revalidate');
        }
        
        return $response;
    }
}
