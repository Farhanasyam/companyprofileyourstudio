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
        ] + $this->variantRules());
        $this->ensureUniqueVariantCodes($request);

        $data = $request->only([
            'name', 'name_en', 'category_id', 'description', 'description_en', 'short_description',
            'short_description_en', 'shopee_url', 'tiktok_url',
        ]);

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
        
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        $product = Product::create($data);
        $this->syncVariants($request, $product);

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
         ] + $this->variantRules());
        $this->ensureUniqueVariantCodes($request);

        $data = $request->only([
            'name', 'name_en', 'category_id', 'description', 'description_en', 'short_description',
            'short_description_en', 'shopee_url', 'tiktok_url',
        ]);
        
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
        
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        $product->update($data);
        $this->syncVariants($request, $product);

        \Log::info('Product Updated Successfully', [
            'product_id' => $product->id,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Product Update Validation Error', [
                'product_id' => $product->id,
                'errors' => $e->errors(),
            ]);
            throw $e; // Re-throw validation exception
        } catch (\Exception $e) {
            \Log::error('Product Update Error', [
                'product_id' => $product->id,
                'error_message' => $e->getMessage(),
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui produk. Silakan coba lagi atau periksa data yang diisi.')
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

    /** Aturan validasi varian (form: variants[key][id|code|name|image|remove_image|_delete]) */
    private function variantRules(): array
    {
        return [
            'variants' => 'nullable|array|max:100',
            'variants.*.id' => 'nullable|integer',
            'variants.*._delete' => 'nullable|in:0,1',
            'variants.*.code' => 'required_unless:variants.*._delete,1|nullable|string|max:100',
            'variants.*.name' => 'nullable|string|max:255',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'variants.*.remove_image' => 'nullable|in:1',
        ];
    }

    /** Kode varian dalam satu produk harus unik (tidak peka huruf besar/kecil) */
    private function ensureUniqueVariantCodes(Request $request): void
    {
        $seen = [];
        foreach ((array) $request->input('variants', []) as $row) {
            if (($row['_delete'] ?? '0') === '1') {
                continue;
            }
            $code = mb_strtolower(trim((string) ($row['code'] ?? '')));
            if ($code === '') {
                continue;
            }
            if (isset($seen[$code])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'variants' => 'Kode varian "' . trim($row['code']) . '" dipakai lebih dari sekali.',
                ]);
            }
            $seen[$code] = true;
        }
    }

    /**
     * Simpan varian sesuai form: urutan baris = sort_order, varian yang ditandai/ tidak dikirim dihapus,
     * gambar baru menggantikan yang lama.
     */
    private function syncVariants(Request $request, Product $product): void
    {
        $rows = (array) $request->input('variants', []);
        $files = (array) $request->file('variants', []);
        $existing = $product->variants()->get()->keyBy('id');
        $keptIds = [];
        $order = 0;

        foreach ($rows as $key => $row) {
            $variant = !empty($row['id']) ? $existing->get((int) $row['id']) : null;
            $code = trim((string) ($row['code'] ?? ''));

            if (($row['_delete'] ?? '0') === '1' || $code === '') {
                continue; // varian lama yang tidak masuk $keptIds akan dihapus di bawah
            }

            $variant ??= $product->variants()->make();
            $variant->fill([
                'code' => $code,
                'name' => trim((string) ($row['name'] ?? '')) ?: null,
                'sort_order' => ++$order,
            ]);

            if (!empty($row['remove_image']) && $variant->image) {
                ImageHelper::delete($variant->image);
                $variant->image = null;
            }
            $file = $files[$key]['image'] ?? null;
            if ($file) {
                $variant->image = ImageHelper::upload($file, ImageHelper::getFolder('product') . '/variants', $variant->image);
            }

            $variant->save();
            $keptIds[] = $variant->id;
        }

        $existing->except($keptIds)->each->delete();
    }
}
