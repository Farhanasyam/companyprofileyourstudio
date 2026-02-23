<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gunakan pagination Bootstrap 5 di admin (tanpa panah SVG besar)
        if (request()->is('admin/*')) {
            Paginator::useBootstrapFive();
        }

        View::composer('admin.layout', function ($view) {
            if (!isset($view->getData()['stats'])) {
                $view->with('stats', [
                    'unread_contacts' => \App\Models\Contact::where('status', 'unread')->count(),
                ]);
            }
        });
    }
}
