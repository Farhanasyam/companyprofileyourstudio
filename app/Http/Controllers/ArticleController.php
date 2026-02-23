<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        app('seo')
            ->setTitle('Artikel - ' . \App\Models\Setting::get('company_name', 'YourStudio'))
            ->setDescription('Tips dan inspirasi untuk kreativitas Anda dari ' . \App\Models\Setting::get('company_name', 'YourStudio'))
            ->setType('website');

        // Pastikan artikel published yang belum punya published_at punya tanggal (untuk urutan & tampilan)
        \App\Models\Article::where('status', 'published')
            ->whereNull('published_at')
            ->update(['published_at' => now()]);

        $articles = \App\Models\Article::published()
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(9);
        $featuredArticles = \App\Models\Article::published()
            ->featured()
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return view('articles.index', compact('articles', 'featuredArticles'));
    }

    public function show(\App\Models\Article $article)
    {
        if (strtolower(trim($article->status ?? '')) !== 'published') {
            abort(404);
        }

        app('seo')
            ->setTitle($article->meta_title ?: $article->title . ' - ' . \App\Models\Setting::get('company_name', 'YourStudio'))
            ->setDescription($article->meta_description ?: $article->excerpt ?: strip_tags($article->content))
            ->setKeywords($article->tags ? implode(', ', $article->tags) : '')
            ->setImage($article->featured_image ? $article->featured_image_url : null)
            ->setType('article');

        // Increment views
        $article->increment('views');

        $relatedArticles = \App\Models\Article::published()
            ->where('id', '!=', $article->id)
            ->where(function($query) use ($article) {
                if ($article->tags) {
                    foreach ($article->tags as $tag) {
                        $query->orWhereJsonContains('tags', $tag);
                    }
                }
            })
            ->take(3)
            ->get();

        return view('articles.show', compact('article', 'relatedArticles'));
    }
}
