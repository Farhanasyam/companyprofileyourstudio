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

        // Use cached methods for better performance
        $featuredProducts = \App\Models\Product::getFeaturedProducts(6);
        $featuredArticles = \App\Models\Article::getFeaturedArticles(3);
        $upcomingEvents = \App\Models\Event::getUpcomingEvents(3);
        $categories = \App\Models\Category::active()->ordered()->withCount('products')->take(6)->get();
        $heroImages = \App\Models\Gallery::getHeroImages();

        return view('home', compact('featuredProducts', 'featuredArticles', 'upcomingEvents', 'categories', 'heroImages'));
    }

    public function about()
    {
        app('seo')
            ->setTitle('Tentang Kami - ' . \App\Models\Setting::get('company_name', 'YourStudio'))
            ->setDescription('Pelajari lebih lanjut tentang ' . \App\Models\Setting::get('company_name', 'YourStudio') . ' dan visi misi kami')
            ->setType('website');

        $aboutImages = \App\Models\Gallery::getAboutImages();

        return view('about', compact('aboutImages'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        \App\Models\Contact::create($request->all());

        return redirect()->route('contact')
            ->with('success', 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.');
    }
}
