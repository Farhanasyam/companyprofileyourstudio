<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('products')->ordered()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'name_en' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'description_en' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'sort_order' => 'nullable|integer|min:0',
            ]);

            $data = $request->only(['name', 'name_en', 'description', 'description_en', 'sort_order']);
            
            \Log::info('Category store request data', [
                'request_data' => $request->all(),
                'has_is_active' => $request->has('is_active'),
                'is_active_value' => $request->input('is_active')
            ]);
            
            // Generate unique slug
            $baseSlug = Str::slug($request->name);
            $slug = $baseSlug;
            $counter = 1;
            while (Category::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
            
            $data['is_active'] = $request->has('is_active') ? true : false;
            $data['sort_order'] = $request->sort_order ?? 0;

            if ($request->hasFile('image')) {
                $data['image'] = ImageHelper::upload($request->file('image'), ImageHelper::getFolder('category'));
            }

            $category = Category::create($data);

            \Log::info('Category created successfully', ['category_id' => $category->id, 'name' => $category->name]);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Kategori berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            \Log::error('Error creating category', ['error' => $e->getMessage(), 'request_data' => $request->all()]);
            
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan kategori. Silakan periksa data lalu coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load('products');
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['name', 'name_en', 'description', 'description_en', 'sort_order']);
        
        // Generate unique slug (only if name changed)
        if ($request->name !== $category->name) {
            $baseSlug = Str::slug($request->name);
            $slug = $baseSlug;
            $counter = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }
        
        $data['is_active'] = $request->has('is_active') ? true : false;

        if ($request->hasFile('image')) {
            $data['image'] = ImageHelper::upload($request->file('image'), ImageHelper::getFolder('category'), $category->image);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Tidak dapat menghapus kategori yang memiliki produk!');
        }

        // Delete image if exists
        if ($category->image) {
            ImageHelper::delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
