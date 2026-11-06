<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Display all products organized by their respective subcategories.
     */
    public function index(Request $request): View
    {
        $categories = Category::where('is_active', true)
            ->with(['subcategories' => function ($query) {
                $query->where('is_active', true)
                    ->with(['products' => function ($pQuery) {
                        $pQuery->where('is_active', true)->latest();
                    }])
                    ->has('products'); // Only show subcategories with products
            }])
            ->has('subcategories.products')
            ->orderBy('name')
            ->get();

        $totalProducts = Product::where('is_active', true)->count();

        return view('catalog.index', compact('categories', 'totalProducts'));
    }

    /**
     * Display the specified product with SEO-friendly slug.
     */
    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)
            ->with(['category:id,name,slug', 'subcategory:id,name,slug'])
            ->firstOrFail();

        $relatedProducts = Product::where('subcategory_id', $product->subcategory_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        return view('catalog.show', compact('product', 'relatedProducts'));
    }
}
