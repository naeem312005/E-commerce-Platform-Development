<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): View
    {
        $query = Product::with(['category:id,name,slug', 'subcategory:id,name,slug']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('subcategory_id')) {
            $query->where('subcategory_id', $request->input('subcategory_id'));
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        $categories = Category::with(['subcategories' => function ($q) {
            $q->where('is_active', true)->orderBy('name');
        }])
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $filters = [
            'search' => $request->input('search', ''),
            'category_id' => $request->input('category_id', ''),
            'subcategory_id' => $request->input('subcategory_id', ''),
        ];

        return view('admin.products.index', compact('products', 'categories', 'filters'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = Category::with(['subcategories' => function ($q) {
            $q->where('is_active', true)->orderBy('name');
        }])
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated) {
            $imagePath = $request->file('image')->store('products', 'public');

            $validated['image'] = $imagePath;
            $validated['slug'] = Product::generateUniqueSlug($validated['name']);
            $validated['sku'] = Product::generateUniqueSku();

            Product::create($validated);
        });

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $product->load(['category:id,name', 'subcategory:id,name']);

        $categories = Category::with(['subcategories' => function ($q) {
            $q->where('is_active', true)->orderBy('name');
        }])
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.products.edit', compact('categories', 'product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated, $product) {
            if ($request->hasFile('image')) {
                // Delete old local image
                if ($product->image && ! str_starts_with($product->image, 'http') && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $validated['image'] = $request->file('image')->store('products', 'public');
            } else {
                unset($validated['image']);
            }

            if ($product->name !== $validated['name']) {
                $validated['slug'] = Product::generateUniqueSlug($validated['name'], $product->id);
            }

            $product->update($validated);
        });

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image && ! str_starts_with($product->image, 'http') && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
}
