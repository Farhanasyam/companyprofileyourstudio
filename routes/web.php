<?php

use Illuminate\Support\Facades\Route;

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

// Language switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Public routes with locale-aware caching
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(['cache.headers:public;max_age=3600', 'locale.cache']);
Route::get('/about', [App\Http\Controllers\HomeController::class, 'about'])->name('about')->middleware(['cache.headers:public;max_age=3600', 'locale.cache']);
Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact')->middleware(['cache.headers:public;max_age=3600', 'locale.cache']);
Route::post('/contact', [App\Http\Controllers\HomeController::class, 'storeContact'])->name('contact.store');

// Order: daftar produk (cached, lazy-load) + simpan order ke DB
Route::get('/order/products', [App\Http\Controllers\OrderController::class, 'products'])->name('order.products');
Route::post('/order', [App\Http\Controllers\OrderController::class, 'store'])->name('order.store');

// Products with locale-aware caching
Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index')->middleware(['cache.headers:public;max_age=1800', 'locale.cache']);
Route::get('/products/{product:slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show')->middleware(['cache.headers:public;max_age=1800', 'locale.cache']);
Route::get('/categories/{category:slug}', [App\Http\Controllers\ProductController::class, 'category'])->name('products.category')->middleware(['cache.headers:public;max_age=1800', 'locale.cache']);

// Articles with locale-aware caching
Route::get('/articles', [App\Http\Controllers\ArticleController::class, 'index'])->name('articles.index')->middleware(['cache.headers:public;max_age=1800', 'locale.cache']);
Route::get('/articles/{article:slug}', [App\Http\Controllers\ArticleController::class, 'show'])->name('articles.show')->middleware(['cache.headers:public;max_age=1800', 'locale.cache']);

// Events with locale-aware caching
Route::get('/events', [App\Http\Controllers\EventController::class, 'index'])->name('events.index')->middleware(['cache.headers:public;max_age=1800', 'locale.cache']);
Route::get('/events/upcoming', [App\Http\Controllers\EventController::class, 'upcoming'])->name('events.upcoming')->middleware(['cache.headers:public;max_age=1800', 'locale.cache']);
Route::get('/events/completed', [App\Http\Controllers\EventController::class, 'completed'])->name('events.completed')->middleware(['cache.headers:public;max_age=1800', 'locale.cache']);
Route::get('/events/{event:slug}', [App\Http\Controllers\EventController::class, 'show'])->name('events.show')->middleware(['cache.headers:public;max_age=1800', 'locale.cache']);

// Language (no caching to ensure session works properly)


// TinyMCE Test Page
Route::get('/tinymce-test', function () {
    return view('tinymce-test');
})->name('tinymce.test');

// SEO Debug Test (no auth required)
Route::get('/seo-debug', function () {
    $seoSettings = \App\Models\Setting::where('group', 'seo')->get();
    return view('admin.settings.seo-debug', compact('seoSettings'));
})->name('seo.debug');

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
    
    // TinyMCE Test Page
    Route::get('/tinymce-test', function () {
        return view('admin.tinymce-test');
    })->name('tinymce.test');
    
    // SEO Test Route (bypass controller)
    Route::get('/seo-test', function () {
        $seoSettings = \App\Models\Setting::where('group', 'seo')->get();
        return view('admin.settings.seo-working', compact('seoSettings'));
    })->name('seo.test');
});
