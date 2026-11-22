<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        app('seo')
            ->setTitle((app()->getLocale() === 'en' ? 'Articles - ' : 'Artikel - ') . \App\Helpers\SettingHelper::getCompanyName())
            ->setDescription((app()->getLocale() === 'en' ? 'Tips and inspiration for your creativity from ' : 'Tips dan inspirasi untuk kreativitas Anda dari ') . \App\Helpers\SettingHelper::getCompanyName())
            ->setType('website');

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
        if (strtolower(trim($article->status ?? '')) !== 'published'
            || ($article->published_at && $article->published_at->isFuture())) {
            abort(404);
        }

        app('seo')
            ->setTitle($article->localized_meta_title ?: $article->localized_title . ' - ' . \App\Helpers\SettingHelper::getCompanyName())
            ->setDescription($article->localized_meta_description ?: $article->localized_excerpt ?: $article->localized_content)
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
