<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Fallback: serve storage files via PHP jika symlink tidak didukung di hosting
Route::get('/storage/{path}', function (string $path) {
    $path = str_replace(['..', "\0"], '', $path);
    $fullPath = storage_path('app/public/' . $path);
    if (!File::isFile($fullPath)) {
        abort(404);
    }
    $mime = File::mimeType($fullPath) ?: 'application/octet-stream';
    // Nama file upload selalu unik, jadi aman di-cache lama oleh browser
    return response()->file($fullPath, [
        'Content-Type' => $mime,
        'Cache-Control' => 'public, max-age=31536000, immutable',
    ]);
})->where('path', '.*')->name('storage.fallback')
    // File statis tidak butuh session/cookie/CSRF: lebih cepat & tidak membuat file session per gambar
    ->withoutMiddleware($staticFileMiddleware = [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \App\Http\Middleware\SetLocale::class,
    ]);

// /favicon.ico: file public/favicon.ico (logo toko) dilayani langsung oleh web server. Route ini cadangan
// bila file itu tidak ada: sajikan favicon/logo toko yang sama dengan tag <link>.
Route::get('/favicon.ico', function () {
    ['file' => $file, 'type' => $type] = \App\Helpers\FaviconHelper::resolve();
    abort_unless(is_file($file), 404);
    return response()->file($file, ['Content-Type' => $type, 'Cache-Control' => 'public, max-age=86400']);
})->name('favicon')->withoutMiddleware($staticFileMiddleware);

// Language switcher - set session then redirect (no cache so next load uses new locale)
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);

        return redirect()->back()
            ->withCookie(cookie('app_locale', $locale, 60 * 24 * 365))
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'Pragma' => 'no-cache',
            ]);
    }

    return redirect()->back()->withHeaders([
        'Cache-Control' => 'no-store, no-cache, must-revalidate',
        'Pragma' => 'no-cache',
    ]);
})->name('lang.switch');

// Cache halaman HTML: browser selalu revalidasi (ETag -> 304 bila tidak berubah).
// Jangan "public; max_age" karena HTML berisi token CSRF, pesan flash & error validasi
// (mis. setelah kirim form kontak) yang akan hilang jika halaman diambil dari cache.
$pageCache = ['cache.headers:private;max_age=0;must_revalidate;etag', 'locale.cache'];

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware($pageCache);
Route::get('/about', [App\Http\Controllers\HomeController::class, 'about'])->name('about')->middleware($pageCache);
Route::get('/gallery', [App\Http\Controllers\HomeController::class, 'gallery'])->name('gallery')->middleware($pageCache);
Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact')->middleware($pageCache);
Route::post('/contact', [App\Http\Controllers\HomeController::class, 'storeContact'])->name('contact.store')->middleware('throttle:10,1');

// Order: daftar produk (cached, lazy-load) + simpan order ke DB
Route::get('/order/products', [App\Http\Controllers\OrderController::class, 'products'])->name('order.products');
Route::post('/order', [App\Http\Controllers\OrderController::class, 'store'])->name('order.store')->middleware('throttle:10,1');

// Products
// Kategori "Tanah Liat" sudah diganti "Clay": URL lama dialihkan permanen (link lama & hasil Google tetap jalan)
Route::redirect('/categories/tanah-liat', '/categories/clay', 301);
Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index')->middleware($pageCache);
Route::get('/products/{product:slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show')->middleware($pageCache);
Route::get('/categories/{category:slug}', [App\Http\Controllers\ProductController::class, 'category'])->name('products.category')->middleware($pageCache);

// Articles
Route::get('/articles', [App\Http\Controllers\ArticleController::class, 'index'])->name('articles.index')->middleware($pageCache);
Route::get('/articles/{article:slug}', [App\Http\Controllers\ArticleController::class, 'show'])->name('articles.show');

// Events
Route::get('/events', [App\Http\Controllers\EventController::class, 'index'])->name('events.index')->middleware($pageCache);
Route::get('/events/upcoming', [App\Http\Controllers\EventController::class, 'upcoming'])->name('events.upcoming')->middleware($pageCache);
Route::get('/events/completed', [App\Http\Controllers\EventController::class, 'completed'])->name('events.completed')->middleware($pageCache);
Route::get('/events/{event:slug}', [App\Http\Controllers\EventController::class, 'show'])->name('events.show')->middleware($pageCache);

// SEO: sitemap & robots dibuat dinamis agar URL selalu sesuai APP_URL dan data terbaru
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [App\Http\Controllers\SitemapController::class, 'robots'])->name('robots');


// Auth routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Products (using ID for admin)
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class)->parameters([
        'products' => 'product:id'
    ]);
    
    // Categories (using ID for admin)
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)->parameters([
        'categories' => 'category:id'
    ]);
    
    // Articles (using ID for admin)
    Route::resource('articles', App\Http\Controllers\Admin\ArticleController::class)->parameters([
        'articles' => 'article:id'
    ]);
    
    // Contacts (using ID for admin)
    Route::resource('contacts', App\Http\Controllers\Admin\ContactController::class)->parameters([
        'contacts' => 'contact:id'
    ]);
    
    // Orders (riwayat pemesanan dari form WA)
    Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    
    // Settings - SEO routes must come BEFORE resource routes
    Route::get('settings/seo', [App\Http\Controllers\Admin\SettingController::class, 'seo'])->name('settings.seo');
    Route::post('settings/seo', [App\Http\Controllers\Admin\SettingController::class, 'updateSeo'])->name('settings.seo.update');
    Route::post('settings/bulk-update', [App\Http\Controllers\Admin\SettingController::class, 'updateBulk'])->name('settings.bulk-update');
    
    // Settings (using ID for admin)
    Route::resource('settings', App\Http\Controllers\Admin\SettingController::class)->parameters([
        'settings' => 'setting:id'
    ]);
    
    // Contact reply
    Route::post('contacts/{contact}/reply', [App\Http\Controllers\Admin\ContactController::class, 'reply'])->name('contacts.reply');
    
    // Galleries (using ID for admin)
    Route::resource('galleries', App\Http\Controllers\Admin\GalleryController::class)->parameters([
        'galleries' => 'gallery:id'
    ]);
    
    // Admin Profile Management
    Route::get('/profile/edit', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    
    // Events (using ID for admin)
    Route::resource('events', App\Http\Controllers\Admin\EventController::class)->parameters([
        'events' => 'event:id'
    ]);
    
    // About Us (using ID for admin)
    Route::resource('about-us', App\Http\Controllers\Admin\AboutUsController::class)->parameters([
        'about-us' => 'aboutUs:id'
    ]);
    Route::patch('about-us/{aboutUs}/toggle', [App\Http\Controllers\Admin\AboutUsController::class, 'toggle'])->name('about-us.toggle');
});
