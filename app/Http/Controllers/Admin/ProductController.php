<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'description_en' => 'nullable|string',
            'short_description' => 'nullable|string',
            'short_description_en' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv,webm|max:51200', // 50MB max
            'shopee_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = ImageHelper::upload($request->file('image'), ImageHelper::getFolder('product'));
        }

        // Handle video upload
        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            $videoPath = $videoFile->store('products/videos', 'public');
            $data['video'] = $videoPath;
        }
        
        // Generate unique slug
        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        $data['slug'] = $slug;
        
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Debug logging
        \Log::info('Product Update Request', [
            'product_id' => $product->id,
            'request_data' => $request->all(),
            'is_featured_raw' => $request->input('is_featured'),
            'is_active_raw' => $request->input('is_active'),
            'is_featured_has' => $request->has('is_featured'),
            'is_active_has' => $request->has('is_active'),
            'user_id' => auth()->id(),
        ]);
        
        try {
             $request->validate([
             'name' => 'required|string|max:255',
             'name_en' => 'nullable|string|max:255',
             'category_id' => 'required|exists:categories,id',
             'description' => 'required|string',
             'description_en' => 'nullable|string',
             'short_description' => 'nullable|string',
             'short_description_en' => 'nullable|string',
             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
             'video' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv,webm|max:51200', // 50MB max
             'shopee_url' => 'nullable|url',
             'tiktok_url' => 'nullable|url',
         ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = ImageHelper::upload($request->file('image'), ImageHelper::getFolder('product'), $product->image);
        }

        // Handle video upload
        if ($request->hasFile('video')) {
            // Delete old video if exists
            if ($product->video && \Storage::disk('public')->exists($product->video)) {
                \Storage::disk('public')->delete($product->video);
            }
            $videoFile = $request->file('video');
            $videoPath = $videoFile->store('products/videos', 'public');
            $data['video'] = $videoPath;
        }
        
        // Generate unique slug (only if name changed)
        if ($request->name !== $product->name) {
            $baseSlug = Str::slug($request->name);
            $slug = $baseSlug;
            $counter = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }
        
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');

        $product->update($data);

        \Log::info('Product Updated Successfully', [
            'product_id' => $product->id,
            'updated_data' => $data,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Product Update Validation Error', [
                'product_id' => $product->id,
                'errors' => $e->errors(),
                'request_data' => $request->all(),
            ]);
            throw $e; // Re-throw validation exception
        } catch (\Exception $e) {
            \Log::error('Product Update Error', [
                'product_id' => $product->id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui produk: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image) {
            ImageHelper::delete($product->image);
        }

        // Delete video if exists
        if ($product->video && \Storage::disk('public')->exists($product->video)) {
            \Storage::disk('public')->delete($product->video);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
