<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'products' => \App\Models\Product::count(),
            'categories' => \App\Models\Category::count(),
            'articles' => \App\Models\Article::count(),
            'events' => \App\Models\Event::count(),
            'contacts' => \App\Models\Contact::count(),
            'unread_contacts' => \App\Models\Contact::unread()->count(),
            'galleries' => \App\Models\Gallery::count(),
        ];

        $recent_contacts = \App\Models\Contact::latest()->take(5)->get();
        $recent_articles = \App\Models\Article::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_contacts', 'recent_articles'));
    }
}
