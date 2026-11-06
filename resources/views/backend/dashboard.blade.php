@extends('backend.app')

@section('title', 'Admin Dashboard')
@section('header_title', 'Overview Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Categories -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Categories</span>
                <div class="rounded-xl bg-blue-50 p-2.5 text-blue-600">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-900">{{ $stats['total_categories'] }}</span>
                <a href="{{ route('admin.categories.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-0.5">
                    Manage →
                </a>
            </div>
        </div>

        <!-- Subcategories -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Subcategories</span>
                <div class="rounded-xl bg-purple-50 p-2.5 text-purple-600">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-900">{{ $stats['total_subcategories'] }}</span>
                <a href="{{ route('admin.subcategories.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-0.5">
                    Manage →
                </a>
            </div>
        </div>

        <!-- Products -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Products</span>
                <div class="rounded-xl bg-emerald-50 p-2.5 text-emerald-600">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-900">{{ $stats['total_products'] }}</span>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-0.5">
                    Manage →
                </a>
            </div>
        </div>

        <!-- Published in Store -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Active In Store</span>
                <div class="rounded-xl bg-amber-50 p-2.5 text-amber-600">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-900">{{ $stats['active_products'] }}</span>
                <span class="text-xs text-slate-400 font-medium">Live Products</span>
            </div>
        </div>
    </div>

    <!-- Main 2-Column Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Products Table (2 Cols) -->
        <div class="lg:col-span-2 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Recent Products</h3>
                    <p class="text-xs text-slate-500">Recently added inventory items with current pricing.</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                    View All
                </a>
            </div>

            @if($recentProducts->isEmpty())
                <div class="py-12 text-center">
                    <p class="text-sm text-slate-500 mb-3">No products created yet.</p>
                    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-indigo-600 text-white">
                        + Add First Product
                    </a>
                </div>
            @else
                <div class="overflow-x-auto mt-2">
                    <table class="w-full min-w-[480px] text-left text-sm">
                        <thead class="text-[11px] font-bold text-slate-400 uppercase border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-2">Product</th>
                                <th class="py-3 px-2">Category</th>
                                <th class="py-3 px-2">Price (৳)</th>
                                <th class="py-3 px-2 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentProducts as $prod)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-3 px-2">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="size-10 rounded-lg object-cover bg-slate-100 border border-slate-200 shrink-0">
                                            <div class="min-w-0">
                                                <p class="font-bold text-slate-800 text-xs truncate max-w-xs">{{ $prod->name }}</p>
                                                <span class="font-mono text-[10px] text-slate-400">{{ $prod->sku }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-2">
                                        <span class="inline-block text-[11px] font-medium text-slate-600 bg-slate-100 px-2 py-0.5 rounded">
                                            {{ $prod->subcategory->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-2">
                                        <div class="flex items-baseline gap-1.5">
                                            <span class="font-bold text-xs text-emerald-600">৳{{ number_format($prod->new_price, 2) }}</span>
                                            @if($prod->old_price > $prod->new_price)
                                                <span class="text-[10px] text-slate-400 line-through">৳{{ number_format($prod->old_price, 2) }}</span>
                                            @endif
                                        </div>
                                        @if($prod->discount_percentage > 0)
                                            <span class="text-[10px] font-bold text-red-600 bg-red-50 px-1 rounded">
                                                -{{ $prod->discount_percentage }}%
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-2 text-right">
                                        <a href="{{ route('products.show', $prod->slug) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Categories Summary (1 Col) -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Top Categories</h3>
                        <p class="text-xs text-slate-500">Active product groups</p>
                    </div>
                    <a href="{{ route('admin.categories.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                        Manage
                    </a>
                </div>

                <div class="mt-4 space-y-3">
                    @foreach($categories as $cat)
                        <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition">
                            <div class="flex items-center gap-3">
                                <span class="size-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                </span>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">{{ $cat->name }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $cat->subcategories_count }} Subcategories</p>
                                </div>
                            </div>
                            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-bold text-slate-700">
                                {{ $cat->products_count }} Products
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.categories.index') }}" class="flex w-full items-center justify-center gap-1.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                    + Add New Category
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

