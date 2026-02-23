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
            'items' => 'required|array',
            'items.*.id' => 'required',
            'items.*.name' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $items = collect($request->items)->map(function ($row) {
            return [
                'product_id' => (int) $row['id'],
                'product_name' => $row['name'],
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
