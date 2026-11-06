<?php

use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SubcategoryController;
use Illuminate\Support\Facades\Route;

// Home route displays the Product Catalog Showcase
Route::get('/', [CatalogController::class, 'index'])->name('home');

// Authenticated Management Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        // Category Management
        Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
        Route::patch('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');

        // Subcategory Management
        Route::resource('subcategories', SubcategoryController::class)->except(['create', 'show', 'edit']);
        Route::patch('subcategories/{subcategory}/toggle-status', [SubcategoryController::class, 'toggleStatus'])->name('subcategories.toggle-status');
        Route::get('subcategories/by-category/{category}', [SubcategoryController::class, 'byCategory'])->name('subcategories.by-category');

        // Product Management
        Route::resource('products', ProductController::class);
        Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');

        // Product Catalog Showcase
        Route::get('catalog', [CatalogController::class, 'index'])->name('catalog');

        // Admin Profile & Account Settings
        Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::put('profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});

// Product Details with SEO-friendly slug
Route::get('/products/{slug}', [CatalogController::class, 'show'])->name('products.show');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.public');
