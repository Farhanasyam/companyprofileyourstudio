<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::with('user')->latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug logging
        \Log::info('Article Store Request', [
            'request_data' => $request->all(),
            'is_featured_raw' => $request->input('is_featured'),
            'is_featured_has' => $request->has('is_featured'),
            'user_id' => auth()->id(),
        ]);

        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'excerpt' => 'nullable|string',
                'content' => 'required|string',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'required|in:draft,published',
                'is_featured' => 'nullable|in:on,off,1,0,true,false',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string',
                'tags' => 'nullable|string',
                'published_at' => 'nullable|date',
            ]);

        $data = $request->all();
        
        // Generate unique slug
        $baseSlug = Str::slug($request->title);
        $slug = $baseSlug;
        $counter = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        $data['slug'] = $slug;
        
        $data['user_id'] = auth()->id();
        $data['is_featured'] = $request->has('is_featured');

        if ($request->has('tags')) {
            $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        if ($request->status === 'published') {
            $data['published_at'] = $request->filled('published_at')
                ? \Carbon\Carbon::parse($request->published_at)
                : now();
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = ImageHelper::upload($request->file('featured_image'), ImageHelper::getFolder('article'));
        }

            Article::create($data);

            if (($data['status'] ?? '') === 'published') {
                $this->clearArticleCaches();
            }

            \Log::info('Article Created Successfully', [
                'article_data' => $data,
            ]);

            return redirect()->route('admin.articles.index')
                ->with('success', 'Artikel berhasil ditambahkan!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Article Store Validation Error', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
            ]);
            throw $e; // Re-throw validation exception
        } catch (\Exception $e) {
            \Log::error('Article Store Error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan artikel: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return view('admin.articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        // Debug logging
        \Log::info('Article Update Request', [
            'article_id' => $article->id,
            'request_data' => $request->all(),
            'is_featured_raw' => $request->input('is_featured'),
            'is_featured_has' => $request->has('is_featured'),
            'user_id' => auth()->id(),
        ]);

        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'excerpt' => 'nullable|string',
                'content' => 'required|string',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'required|in:draft,published',
                'is_featured' => 'nullable|in:on,off,1,0,true,false',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string',
                'tags' => 'nullable|string',
                'published_at' => 'nullable|date',
            ]);

        $data = $request->all();
        
        // Generate unique slug (only if title changed)
        if ($request->title !== $article->title) {
            $baseSlug = Str::slug($request->title);
            $slug = $baseSlug;
            $counter = 1;
            while (Article::where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }
        
        $data['is_featured'] = $request->has('is_featured');

        if ($request->has('tags')) {
            $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        if ($request->status === 'published' && !$article->published_at) {
            $data['published_at'] = $request->filled('published_at')
                ? \Carbon\Carbon::parse($request->published_at)
                : now();
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = ImageHelper::upload($request->file('featured_image'), ImageHelper::getFolder('article'), $article->featured_image);
        }

            $article->update($data);

            if (($data['status'] ?? '') === 'published') {
                $this->clearArticleCaches();
            }

            \Log::info('Article Updated Successfully', [
                'article_id' => $article->id,
                'updated_data' => $data,
            ]);

            return redirect()->route('admin.articles.index')
                ->with('success', 'Artikel berhasil diperbarui!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Article Update Validation Error', [
                'article_id' => $article->id,
                'errors' => $e->errors(),
                'request_data' => $request->all(),
            ]);
            throw $e; // Re-throw validation exception
        } catch (\Exception $e) {
            \Log::error('Article Update Error', [
                'article_id' => $article->id,
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui artikel: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        // Delete image if exists
        if ($article->featured_image) {
            ImageHelper::delete($article->featured_image);
        }

        $article->delete();
        $this->clearArticleCaches();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus!');
    }

    /** Bersihkan cache artikel agar halaman publik langsung menampilkan data terbaru */
    private function clearArticleCaches(): void
    {
        foreach ([3, 6, 9] as $limit) {
            Cache::forget("latest_articles_{$limit}");
            Cache::forget("featured_articles_{$limit}");
        }
    }
}
