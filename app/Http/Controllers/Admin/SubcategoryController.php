<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubcategoryRequest;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of subcategories.
     */
    public function index(): View
    {
        $subcategories = Subcategory::with('category')
            ->withCount('products')
            ->latest()
            ->paginate(15);

        $categories = Category::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('admin.subcategories.index', compact('subcategories', 'categories'));
    }

    /**
     * Store a newly created subcategory.
     */
    public function store(SubcategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Subcategory::generateUniqueSlug($validated['name']);

        Subcategory::create($validated);

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory created successfully!');
    }

    /**
     * Update the specified subcategory.
     */
    public function update(SubcategoryRequest $request, Subcategory $subcategory): RedirectResponse
    {
        $validated = $request->validated();

        if ($subcategory->name !== $validated['name']) {
            $validated['slug'] = Subcategory::generateUniqueSlug($validated['name'], $subcategory->id);
        }

        $subcategory->update($validated);

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory updated successfully!');
    }

    /**
     * Remove the specified subcategory.
     */
    public function destroy(Subcategory $subcategory): RedirectResponse
    {
        $subcategory->delete();

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory deleted successfully!');
    }

    /**
     * Get subcategories by category ID for dynamic dependent dropdown.
     */
    public function byCategory(int $categoryId): JsonResponse
    {
        $subcategories = Subcategory::where('category_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return response()->json($subcategories);
    }
}
