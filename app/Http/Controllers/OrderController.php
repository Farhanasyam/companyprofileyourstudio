<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    /**
     * Daftar produk untuk dropdown order (cached, lazy-load saat modal dibuka).
     * Mengurangi query di setiap page load agar web sangat cepat.
     */
    public function products(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        $cacheKey = 'order_modal_products_' . $locale;

        $products = Cache::remember($cacheKey, 600, function () use ($locale) {
            $query = Product::active()->with('category')->orderBySortOrder()->get();
            return $query->map(function ($p) use ($locale) {
                $shortDesc = $locale === 'en'
                    ? ($p->short_description_en ?: $p->short_description)
                    : ($p->short_description ?: $p->short_description_en);
                $shortDesc = $shortDesc ? \Illuminate\Support\Str::limit(strip_tags((string) $shortDesc), 400) : '';
                $categoryName = $p->category
                    ? ($locale === 'en' && $p->category->name_en ? $p->category->name_en : $p->category->name)
                    : '';
                return [
                    'id' => $p->id,
                    'name' => $locale === 'en' && $p->name_en ? $p->name_en : $p->name,
                    'image' => $p->image_url ?: null,
                    'category' => $categoryName,
                    'description' => $shortDesc,
                ];
            })->values();
        });

        return response()->json($products);
    }

    /**
     * Simpan order ke database lalu kembalikan data untuk redirect WA.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemesan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:50',
            'catatan' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1|max:50',
            'items.*.id' => 'required|integer|exists:products,id',
            'items.*.qty' => 'required|integer|min:1|max:999',
        ]);

        $productIds = collect($request->items)->pluck('id')->map(fn ($id) => (int) $id)->unique();
        $products = Product::active()->whereIn('id', $productIds)->get()->keyBy('id');

        if ($products->count() !== $productIds->count()) {
            return response()->json(['message' => 'One or more selected products are unavailable.'], 422);
        }

        $items = collect($request->items)->map(function ($row) use ($products) {
            $product = $products[(int) $row['id']];

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'qty' => (int) $row['qty'],
            ];
        })->toArray();

        Order::create([
            'nama_pemesan' => $request->nama_pemesan,
            'no_hp' => $request->no_hp,
            'catatan' => $request->catatan,
            'items' => $items,
        ]);

        return response()->json(['success' => true]);
    }
}
