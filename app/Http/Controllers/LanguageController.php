<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        $supportedLocales = ['en', 'id'];
        
        if (in_array($locale, $supportedLocales)) {
            // Set session locale
            Session::put('locale', $locale);
            
            // Also set app locale immediately
            App::setLocale($locale);
            
            // Regenerate session ID for security
            Session::regenerate();
            
            // Flash success message
            Session::flash('language_changed', true);
        }
        
        return Redirect::back();
    }
}
