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
        // Hanya en/id: nilai lain akan membuat entri cache baru tanpa batas (dan tidak pernah dibersihkan)
        $locale = $request->get('locale', app()->getLocale());
        if (!in_array($locale, ['en', 'id'], true)) {
            $locale = 'id';
        }
        $cacheKey = 'order_modal_products_' . $locale;

        $products = Cache::remember($cacheKey, 600, function () use ($locale) {
            $en = $locale === 'en';
            $query = Product::active()->with(['category', 'variants'])->orderBySortOrder()->get();

            // Satu pilihan per produk, atau satu pilihan per varian (key "produk:varian") bila produk bervarian
            return $query->flatMap(function ($p) use ($en) {
                $desc = $en
                    ? ($p->short_description_en ?: $p->short_description ?: $p->description_en ?: $p->description)
                    : ($p->short_description ?: $p->description ?: $p->short_description_en);
                $desc = $desc ? \Illuminate\Support\Str::limit(trim(strip_tags((string) $desc)), 400) : '';
                $categoryName = $p->category
                    ? ($en && $p->category->name_en ? $p->category->name_en : $p->category->name)
                    : '';
                $name = $en && $p->name_en ? $p->name_en : $p->name;
                $base = [
                    'id' => $p->id,
                    'variant_id' => null,
                    'key' => (string) $p->id,
                    'name' => $name,
                    'image' => $p->image_url ?: null,
                    'category' => $categoryName,
                    'description' => $desc,
                ];

                if ($p->variants->isEmpty()) {
                    return [$base];
                }

                return $p->variants->map(fn ($v) => array_merge($base, [
                    'variant_id' => $v->id,
                    'key' => $p->id . ':' . $v->id,
                    'name' => $name . ' — ' . $v->display_name,
                    'image' => $v->image_url ?: $base['image'],
                ]))->all();
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
            'metode_pengiriman' => 'required|in:kirim,ambil',
            'alamat' => 'required_if:metode_pengiriman,kirim|nullable|string|max:1000',
            'kecamatan' => 'nullable|string|max:255',
            'kota' => 'required_if:metode_pengiriman,kirim|nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'catatan' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1|max:50',
            'items.*.id' => 'required|integer|exists:products,id',
            'items.*.variant_id' => 'nullable|integer',
            'items.*.qty' => 'required|integer|min:1|max:999',
        ]);

        $productIds = collect($request->items)->pluck('id')->map(fn ($id) => (int) $id)->unique();
        $products = Product::active()->with('variants')->whereIn('id', $productIds)->get()->keyBy('id');

        if ($products->count() !== $productIds->count()) {
            return response()->json(['message' => 'One or more selected products are unavailable.'], 422);
        }

        $items = [];
        foreach ($request->items as $row) {
            $product = $products[(int) $row['id']];
            $variant = null;

            if ($product->variants->isNotEmpty()) {
                // Produk bervarian wajib memilih varian milik produk tersebut
                $variant = $product->variants->firstWhere('id', (int) ($row['variant_id'] ?? 0));
                if (!$variant) {
                    return response()->json(['message' => 'Pilih varian untuk produk "' . $product->name . '".'], 422);
                }
            }

            $items[] = [
                'product_id' => $product->id,
                'product_name' => $product->name . ($variant ? ' — ' . $variant->display_name : ''),
                'variant_id' => $variant?->id,
                'variant_code' => $variant?->code,
                'qty' => (int) $row['qty'],
            ];
        }

        $isDelivery = $request->metode_pengiriman === 'kirim';

        Order::create([
            'nama_pemesan' => $request->nama_pemesan,
            'no_hp' => $request->no_hp,
            'metode_pengiriman' => $request->metode_pengiriman,
            'alamat' => $isDelivery ? $request->alamat : null,
            'kecamatan' => $isDelivery ? $request->kecamatan : null,
            'kota' => $isDelivery ? $request->kota : null,
            'kode_pos' => $isDelivery ? $request->kode_pos : null,
            'catatan' => $request->catatan,
            'items' => $items,
        ]);

        return response()->json(['success' => true]);
    }
}
