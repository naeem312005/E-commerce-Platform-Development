<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the admin overview dashboard with system metrics.
     */
    public function index(): Response
    {
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

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentProducts' => $recentProducts,
            'categories' => $categories,
        ]);
    }
}
