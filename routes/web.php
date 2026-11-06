<?php

use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubcategoryController;
use Illuminate\Support\Facades\Route;

// Home route displays the Product Catalog Showcase (Requirement 4)
Route::get('/', [CatalogController::class, 'index'])->name('home');

// Authenticated Management Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        // Category Management
        Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);

        // Subcategory Management
        Route::resource('subcategories', SubcategoryController::class)->except(['create', 'show', 'edit']);
        Route::get('subcategories/by-category/{category}', [SubcategoryController::class, 'byCategory'])->name('subcategories.by-category');

        // Product Management
        Route::resource('products', ProductController::class);

        // Product Catalog Showcase (Requirement 4: Organized by subcategories)
        Route::get('catalog', [CatalogController::class, 'index'])->name('catalog');
    });
});

// Product Details with SEO-friendly slug
Route::get('/products/{slug}', [CatalogController::class, 'show'])->name('products.show');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.public');

require __DIR__.'/settings.php';
