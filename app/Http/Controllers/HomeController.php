<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Set SEO for homepage
        app('seo')
            ->setTitle(\App\Helpers\SettingHelper::getMetaTitle())
            ->setDescription(\App\Helpers\SettingHelper::getMetaDescription())
            ->setType('website');

        // Beranda: 6 kategori (kartu ringkas), 3 produk, 3 event, 3 artikel
        $categories = \App\Models\Category::active()->ordered()
            ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
            ->take(6)->get();
        $featuredProducts = \App\Models\Product::getFeaturedProducts(3);
        if ($featuredProducts->isEmpty()) {
            // Belum ada produk ditandai "Unggulan": tampilkan Paket Bundling (lalu produk lain) agar beranda tidak kosong
            $featuredProducts = \App\Models\Product::with('category')->withCount('variants')->active()
                ->orderByRaw("category_id = (select id from categories where slug = 'paket-bundling' limit 1) desc")
                ->orderBy('sort_order')
                ->take(3)
                ->get();
        }
        $upcomingEvents = \App\Models\Event::getUpcomingEvents(3);
        $featuredArticles = \App\Models\Article::getFeaturedArticles(3);
        $heroImages = \App\Models\Gallery::getHeroImages();

        return view('home', compact('featuredProducts', 'featuredArticles', 'upcomingEvents', 'categories', 'heroImages'));
    }

    public function about()
    {
        $locale = app()->getLocale();
        
        app('seo')
            ->setTitle(__('common.about_us') . ' - ' . \App\Models\Setting::get('company_name', 'YourStudio'))
            ->setDescription(__('common.learn_more_about') . ' ' . \App\Models\Setting::get('company_name', 'YourStudio') . ' ' . __('common.our_company'))
            ->setType('website');

        $aboutImages = \App\Models\Gallery::getAboutImages();
        $aboutSections = \App\Models\AboutUs::getAllSections();

        return view('about', compact('aboutImages', 'aboutSections', 'locale'));
    }

    public function gallery()
    {
        $galleryImages = \App\Models\Gallery::getGalleryImages();

        app('seo')
            ->setTitle(__('common.gallery') . ' - ' . \App\Models\Setting::get('company_name', 'YourStudio'))
            ->setDescription(__('common.gallery_meta_description', ['company' => \App\Models\Setting::get('company_name', 'YourStudio')]))
            ->setType('website');

        return view('gallery', compact('galleryImages'));
    }

    public function contact()
    {
        app('seo')
            ->setTitle(__('common.contact') . ' - ' . \App\Models\Setting::get('company_name', 'YourStudio'))
            ->setDescription(__('common.contact_meta_description'))
            ->setType('website');

        return view('contact');
    }

    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Hanya field tervalidasi; status/admin_reply tidak boleh diisi pengunjung
        \App\Models\Contact::create($validated);

        return redirect()->route('contact')
            ->with('success', 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.');
    }
}
