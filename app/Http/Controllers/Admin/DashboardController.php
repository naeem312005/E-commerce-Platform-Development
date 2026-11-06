<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DashboardController extends Controller
{
    /**
     * Display the admin overview dashboard with system metrics.
     */
    public function index(): View
    {
        try {
            $stats = [
                'total_categories' => Category::count(),
                'total_subcategories' => Subcategory::count(),
                'total_products' => Product::count(),
                'active_products' => Product::where('is_active', true)->count(),
            ];

            $recentProducts = Product::with(['category:id,name', 'subcategory:id,name'])
                ->latest()
                ->take(6)
                ->get();

            $categories = Category::withCount(['subcategories', 'products'])
                ->latest()
                ->take(5)
                ->get();

            return view('backend.dashboard', compact('stats', 'recentProducts', 'categories'));
        } catch (Throwable $e) {
            Log::error('Error loading dashboard metrics: '.$e->getMessage(), ['exception' => $e]);

            $stats = [
                'total_categories' => 0,
                'total_subcategories' => 0,
                'total_products' => 0,
                'active_products' => 0,
            ];

            return view('backend.dashboard', [
                'stats' => $stats,
                'recentProducts' => collect(),
                'categories' => collect(),
            ])->with('error', 'Unable to retrieve dashboard metrics at this time.');
        }
    }
}
