<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Set SEO for homepage
        app('seo')
            ->setTitle(\App\Models\Setting::get('meta_title', 'YourStudio - Toko Alat Lukis dan Clay Terpercaya'))
            ->setDescription(\App\Models\Setting::get('meta_description', 'Toko alat lukis dan clay terpercaya dengan kualitas terbaik'))
            ->setType('website');

        // Beranda: 3 kategori, 3 produk, 3 event, 3 artikel
        $categories = \App\Models\Category::active()->ordered()->withCount('products')->take(3)->get();
        $featuredProducts = \App\Models\Product::getFeaturedProducts(3);
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
            ->setTitle('Galeri - ' . \App\Models\Setting::get('company_name', 'YourStudio'))
            ->setDescription('Lihat galeri karya dan aktivitas ' . \App\Models\Setting::get('company_name', 'YourStudio'))
            ->setType('website');

        return view('gallery', compact('galleryImages'));
    }

    public function contact()
    {
        app('seo')
            ->setTitle('Kontak - ' . \App\Models\Setting::get('company_name', 'YourStudio'))
            ->setDescription('Hubungi kami untuk informasi produk dan layanan terbaik')
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
