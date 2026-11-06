<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(): View
    {
        try {
            $categories = Category::withCount(['subcategories', 'products'])
                ->latest()
                ->paginate(15);

            return view('admin.categories.index', compact('categories'));
        } catch (Throwable $e) {
            Log::error('Error loading categories list: '.$e->getMessage(), ['exception' => $e]);

            return view('admin.categories.index', [
                'categories' => Category::whereRaw('1 = 0')->paginate(15),
            ])->with('error', 'Unable to retrieve categories at this moment.');
        }
    }

    /**
     * Store a newly created category.
     */
    public function store(CategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated) {
                $validated['slug'] = Category::generateUniqueSlug($validated['name']);
                Category::create($validated);
            });

            return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
        } catch (Throwable $e) {
            Log::error('Failed to create category: '.$e->getMessage(), [
                'request' => $request->except(['_token']),
                'exception' => $e,
            ]);

            return back()->withInput()->with('error', 'Failed to create category. Please try again.');
        }
    }

    /**
     * Update the specified category.
     */
    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($category, $validated) {
                if ($category->name !== $validated['name']) {
                    $validated['slug'] = Category::generateUniqueSlug($validated['name'], $category->id);
                }

                $category->update($validated);
            });

            return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
        } catch (Throwable $e) {
            Log::error('Failed to update category #'.$category->id.': '.$e->getMessage(), [
                'request' => $request->except(['_token']),
                'exception' => $e,
            ]);

            return back()->withInput()->with('error', 'Failed to update category. Please try again.');
        }
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        try {
            DB::transaction(function () use ($category) {
                $category->delete();
            });

            return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
        } catch (Throwable $e) {
            Log::error('Failed to delete category #'.$category->id.': '.$e->getMessage(), ['exception' => $e]);

            return back()->with('error', 'Failed to delete category. Ensure there are no dependent records blocking this action.');
        }
    }
}
