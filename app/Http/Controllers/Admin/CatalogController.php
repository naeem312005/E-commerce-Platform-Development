<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CatalogController extends Controller
{
    /**
     * Display all products organized by their respective subcategories.
     */
    public function index(Request $request): Response
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

        return Inertia::render('admin/catalog/Index', [
            'categories' => $categories,
            'totalProducts' => Product::where('is_active', true)->count(),
        ]);
    }

    /**
     * Display the specified product with SEO-friendly slug.
     */
    public function show(string $slug): Response
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

        return Inertia::render('admin/catalog/Show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
