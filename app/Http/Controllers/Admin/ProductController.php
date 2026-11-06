<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): View
    {
        try {
            $query = Product::with(['category:id,name,slug', 'subcategory:id,name,slug', 'images', 'variants']);

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
        } catch (Throwable $e) {
            Log::error('Error loading products list: '.$e->getMessage(), ['exception' => $e]);

            return view('admin.products.index', [
                'products' => Product::whereRaw('1 = 0')->paginate(10),
                'categories' => collect(),
                'filters' => ['search' => '', 'category_id' => '', 'subcategory_id' => ''],
            ])->with('error', 'Unable to retrieve products at this time.');
        }
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

        try {
            DB::transaction(function () use ($request, $validated) {
                $imagePath = $request->file('image')->store('products', 'public');

                $validated['image'] = $imagePath;
                $validated['slug'] = Product::generateUniqueSlug($validated['name']);
                $validated['sku'] = Product::generateUniqueSku();

                /** @var Product $product */
                $product = Product::create($validated);

                // Handle gallery images
                if ($request->hasFile('gallery')) {
                    foreach ($request->file('gallery') as $index => $galleryFile) {
                        $path = $galleryFile->store('products/gallery', 'public');
                        $product->images()->create([
                            'image' => $path,
                            'sort_order' => $index,
                        ]);
                    }
                }

                // Handle variants
                if ($request->filled('variants') && is_array($request->input('variants'))) {
                    foreach ($request->input('variants') as $idx => $variantData) {
                        if (! empty($variantData['attribute_name']) && ! empty($variantData['attribute_value'])) {
                            $variantImage = null;
                            if ($request->hasFile("variants.{$idx}.image")) {
                                $variantImage = $request->file("variants.{$idx}.image")->store('products/variants', 'public');
                            }

                            $product->variants()->create([
                                'attribute_name' => $variantData['attribute_name'],
                                'attribute_value' => $variantData['attribute_value'],
                                'image' => $variantImage,
                                'sku' => ! empty($variantData['sku']) ? $variantData['sku'] : null,
                                'price' => isset($variantData['price']) && $variantData['price'] !== '' ? $variantData['price'] : null,
                                'stock_quantity' => isset($variantData['stock_quantity']) && $variantData['stock_quantity'] !== '' ? (int) $variantData['stock_quantity'] : 0,
                                'is_active' => ! empty($variantData['is_active']),
                            ]);
                        }
                    }
                }
            });

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
        } catch (Throwable $e) {
            Log::error('Failed to create product: '.$e->getMessage(), [
                'request' => $request->except(['_token', 'image', 'gallery']),
                'exception' => $e,
            ]);

            return back()->withInput()->with('error', 'Failed to create product. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $product->load(['category:id,name', 'subcategory:id,name', 'images', 'variants']);

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

        try {
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

                // Delete requested gallery images
                if ($request->filled('delete_images') && is_array($request->input('delete_images'))) {
                    $imagesToDelete = $product->images()->whereIn('id', $request->input('delete_images'))->get();
                    foreach ($imagesToDelete as $img) {
                        if ($img->image && ! str_starts_with($img->image, 'http') && Storage::disk('public')->exists($img->image)) {
                            Storage::disk('public')->delete($img->image);
                        }
                        $img->delete();
                    }
                }

                // Append new gallery images
                if ($request->hasFile('gallery')) {
                    $currentMaxSort = (int) $product->images()->max('sort_order');
                    foreach ($request->file('gallery') as $index => $galleryFile) {
                        $path = $galleryFile->store('products/gallery', 'public');
                        $product->images()->create([
                            'image' => $path,
                            'sort_order' => $currentMaxSort + $index + 1,
                        ]);
                    }
                }

                // Sync variants
                if ($request->has('variants')) {
                    $incomingVariants = (array) $request->input('variants');
                    $submittedIds = [];

                    foreach ($incomingVariants as $idx => $variantData) {
                        if (empty($variantData['attribute_name']) || empty($variantData['attribute_value'])) {
                            continue;
                        }

                        $variantPayload = [
                            'attribute_name' => $variantData['attribute_name'],
                            'attribute_value' => $variantData['attribute_value'],
                            'sku' => ! empty($variantData['sku']) ? $variantData['sku'] : null,
                            'price' => isset($variantData['price']) && $variantData['price'] !== '' ? $variantData['price'] : null,
                            'stock_quantity' => isset($variantData['stock_quantity']) && $variantData['stock_quantity'] !== '' ? (int) $variantData['stock_quantity'] : 0,
                            'is_active' => ! empty($variantData['is_active']),
                        ];

                        if ($request->hasFile("variants.{$idx}.image")) {
                            $variantPayload['image'] = $request->file("variants.{$idx}.image")->store('products/variants', 'public');
                        }

                        if (! empty($variantData['id'])) {
                            $variant = $product->variants()->find($variantData['id']);
                            if ($variant instanceof ProductVariant) {
                                if (isset($variantPayload['image']) && $variant->image && ! str_starts_with($variant->image, 'http') && Storage::disk('public')->exists($variant->image)) {
                                    Storage::disk('public')->delete($variant->image);
                                }
                                $variant->update($variantPayload);
                                $submittedIds[] = $variant->id;

                                continue;
                            }
                        }

                        $newVariant = $product->variants()->create($variantPayload);
                        $submittedIds[] = $newVariant->id;
                    }

                    // Delete variants that were removed
                    $variantsToDelete = $product->variants()->whereNotIn('id', $submittedIds)->get();
                    foreach ($variantsToDelete as $vDel) {
                        if ($vDel->image && ! str_starts_with($vDel->image, 'http') && Storage::disk('public')->exists($vDel->image)) {
                            Storage::disk('public')->delete($vDel->image);
                        }
                        $vDel->delete();
                    }
                } else {
                    foreach ($product->variants as $vDel) {
                        if ($vDel->image && ! str_starts_with($vDel->image, 'http') && Storage::disk('public')->exists($vDel->image)) {
                            Storage::disk('public')->delete($vDel->image);
                        }
                    }
                    $product->variants()->delete();
                }
            });

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
        } catch (Throwable $e) {
            Log::error('Failed to update product #'.$product->id.': '.$e->getMessage(), [
                'request' => $request->except(['_token', 'image', 'gallery']),
                'exception' => $e,
            ]);

            return back()->withInput()->with('error', 'Failed to update product. Please try again.');
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        try {
            DB::transaction(function () use ($product) {
                if ($product->image && ! str_starts_with($product->image, 'http') && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }

                foreach ($product->images as $img) {
                    if ($img->image && ! str_starts_with($img->image, 'http') && Storage::disk('public')->exists($img->image)) {
                        Storage::disk('public')->delete($img->image);
                    }
                }

                foreach ($product->variants as $variant) {
                    if ($variant->image && ! str_starts_with($variant->image, 'http') && Storage::disk('public')->exists($variant->image)) {
                        Storage::disk('public')->delete($variant->image);
                    }
                }

                $product->delete();
            });

            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
        } catch (Throwable $e) {
            Log::error('Failed to delete product #'.$product->id.': '.$e->getMessage(), ['exception' => $e]);

            return back()->with('error', 'Failed to delete product. Please try again.');
        }
    }

    /**
     * Toggle active status of product.
     */
    public function toggleStatus(Product $product): JsonResponse|RedirectResponse
    {
        try {
            $product->update(['is_active' => ! $product->is_active]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'is_active' => $product->is_active,
                    'message' => 'Product status updated to '.($product->is_active ? 'Published' : 'Draft'),
                ]);
            }

            return back()->with('success', 'Product status updated successfully!');
        } catch (Throwable $e) {
            Log::error('Failed to toggle product #'.$product->id.' status: '.$e->getMessage(), ['exception' => $e]);

            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to update status.'], 500);
            }

            return back()->with('error', 'Failed to update product status.');
        }
    }
}
