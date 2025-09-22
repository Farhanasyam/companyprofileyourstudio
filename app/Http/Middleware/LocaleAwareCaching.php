<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
        
        // Get current locale
        $locale = App::getLocale();
        
        // Add locale to Vary header for proper caching
        $response->header('Vary', 'Accept-Language, Cookie');
        
        // Add locale-specific cache key
        $response->header('X-Locale', $locale);
        
        return $response;
    }
}
