<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubcategoryRequest;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of subcategories.
     */
    public function index(): View
    {
        try {
            $subcategories = Subcategory::with('category')
                ->withCount('products')
                ->latest()
                ->paginate(15);

            $categories = Category::where('is_active', true)->orderBy('name')->get(['id', 'name']);

            return view('admin.subcategories.index', compact('subcategories', 'categories'));
        } catch (Throwable $e) {
            Log::error('Error loading subcategories list: '.$e->getMessage(), ['exception' => $e]);

            return view('admin.subcategories.index', [
                'subcategories' => Subcategory::whereRaw('1 = 0')->paginate(15),
                'categories' => collect(),
            ])->with('error', 'Unable to retrieve subcategories at this moment.');
        }
    }

    /**
     * Store a newly created subcategory.
     */
    public function store(SubcategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated) {
                $validated['slug'] = Subcategory::generateUniqueSlug($validated['name']);
                Subcategory::create($validated);
            });

            return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory created successfully!');
        } catch (Throwable $e) {
            Log::error('Failed to create subcategory: '.$e->getMessage(), [
                'request' => $request->except(['_token']),
                'exception' => $e,
            ]);

            return back()->withInput()->with('error', 'Failed to create subcategory. Please try again.');
        }
    }

    /**
     * Update the specified subcategory.
     */
    public function update(SubcategoryRequest $request, Subcategory $subcategory): RedirectResponse
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($subcategory, $validated) {
                if ($subcategory->name !== $validated['name']) {
                    $validated['slug'] = Subcategory::generateUniqueSlug($validated['name'], $subcategory->id);
                }

                $subcategory->update($validated);
            });

            return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory updated successfully!');
        } catch (Throwable $e) {
            Log::error('Failed to update subcategory #'.$subcategory->id.': '.$e->getMessage(), [
                'request' => $request->except(['_token']),
                'exception' => $e,
            ]);

            return back()->withInput()->with('error', 'Failed to update subcategory. Please try again.');
        }
    }

    /**
     * Remove the specified subcategory.
     */
    public function destroy(Subcategory $subcategory): RedirectResponse
    {
        try {
            DB::transaction(function () use ($subcategory) {
                $subcategory->delete();
            });

            return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory deleted successfully!');
        } catch (Throwable $e) {
            Log::error('Failed to delete subcategory #'.$subcategory->id.': '.$e->getMessage(), ['exception' => $e]);

            return back()->with('error', 'Failed to delete subcategory. Ensure there are no dependent records blocking this action.');
        }
    }

    /**
     * Get subcategories by category ID for dynamic dependent dropdown.
     */
    public function byCategory(int $categoryId): JsonResponse
    {
        try {
            $subcategories = Subcategory::where('category_id', $categoryId)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'slug']);

            return response()->json($subcategories);
        } catch (Throwable $e) {
            Log::error('Failed to fetch subcategories for category #'.$categoryId.': '.$e->getMessage());

            return response()->json([
                'error' => 'Unable to load subcategories for the selected category.',
            ], 500);
        }
    }

    /**
     * Toggle active status of subcategory.
     */
    public function toggleStatus(Subcategory $subcategory): JsonResponse|RedirectResponse
    {
        try {
            $subcategory->update(['is_active' => ! $subcategory->is_active]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'is_active' => $subcategory->is_active,
                    'message' => 'Subcategory status updated to '.($subcategory->is_active ? 'Active' : 'Inactive'),
                ]);
            }

            return back()->with('success', 'Subcategory status updated successfully!');
        } catch (Throwable $e) {
            Log::error('Failed to toggle subcategory #'.$subcategory->id.' status: '.$e->getMessage(), ['exception' => $e]);

            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to update status.'], 500);
            }

            return back()->with('error', 'Failed to update subcategory status.');
        }
    }
}
