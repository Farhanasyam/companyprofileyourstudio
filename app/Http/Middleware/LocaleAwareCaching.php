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
        
        $locale = App::getLocale();
        $response->header('Vary', 'Accept-Language, Cookie');
        $response->header('X-Locale', $locale);
        // Prevent browser from serving cached page after language switch (session changes but cookie id is same)
        $response->headers->set('Cache-Control', 'private, max-age=0, must-revalidate');
        
        return $response;
    }
}
