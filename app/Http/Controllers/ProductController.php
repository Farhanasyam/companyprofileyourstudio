<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SettingHelper;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        app('seo')
            ->setTitle(app()->getLocale() === 'en' ? 'Products - ' . SettingHelper::getCompanyName() : 'Produk - ' . SettingHelper::getCompanyName())
            ->setDescription(app()->getLocale() === 'en' ? 'Find various high-quality paint and clay tools' : 'Temukan berbagai produk alat lukis dan clay berkualitas tinggi')
            ->setType('website');

        $query = \App\Models\Product::with('category')->active();

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'name':
                $query->orderBy('name');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12);
        $categories = \App\Models\Category::active()->ordered()->withCount(['products' => function ($q) {
            $q->where('is_active', true);
        }])->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(\App\Models\Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        app('seo')
            ->setTitle($product->localized_meta_title ?: $product->localized_name . ' - ' . SettingHelper::getCompanyName())
            ->setDescription($product->localized_meta_description ?: $product->localized_short_description ?: $product->localized_description)
            ->setKeywords($product->localized_name . ', ' . $product->category->localized_name . ', alat lukis, clay')
            ->setImage($product->image ? $product->image_url : null)
            ->setType('product');

        $relatedProducts = \App\Models\Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function category(\App\Models\Category $category)
    {
        if (!$category->is_active) {
            abort(404);
        }

        app('seo')
            ->setTitle($category->localized_name . ' - ' . SettingHelper::getCompanyName())
            ->setDescription($category->localized_description ?: (app()->getLocale() === 'en' ? 'High quality ' . $category->localized_name . ' products' : 'Produk ' . $category->localized_name . ' berkualitas tinggi'))
            ->setType('website');

        $products = \App\Models\Product::where('category_id', $category->id)
            ->active()
            ->paginate(12);

        return view('products.category', compact('category', 'products'));
    }
}
