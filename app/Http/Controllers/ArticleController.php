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

        $articles = \App\Models\Article::published()->latest('published_at')->paginate(9);
        $featuredArticles = \App\Models\Article::published()->featured()->take(3)->get();

        return view('articles.index', compact('articles', 'featuredArticles'));
    }

    public function show(\App\Models\Article $article)
    {
        if ($article->status !== 'published') {
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
