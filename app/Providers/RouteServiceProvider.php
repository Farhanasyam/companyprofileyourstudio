<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Limiter bernama dengan kunci sendiri-sendiri. "throttle:10,1" biasa memakai satu kunci (domain|IP)
        // untuk SEMUA route, sehingga panggilan /api/countdown (60/jam) ikut menghabiskan jatah order/kontak
        // dan pembeli bisa terkena "Too Many Attempts" saat kirim pesanan hingga 1 jam.
        RateLimiter::for('order', fn (Request $request) => Limit::perMinute(10)->by('order|' . $request->ip()));
        RateLimiter::for('contact', fn (Request $request) => Limit::perMinute(10)->by('contact|' . $request->ip()));
        RateLimiter::for('wilayah', fn (Request $request) => Limit::perMinute(120)->by('wilayah|' . $request->ip()));
        RateLimiter::for('countdown', fn (Request $request) => Limit::perHour(60)->by('countdown|' . $request->ip()));

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
