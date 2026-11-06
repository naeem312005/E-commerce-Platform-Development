@extends('layouts.app')

@section('title', 'Products Management')
@section('header_title', 'Product Management')

@section('content')
<div class="space-y-6">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Products Inventory</h2>
            <p class="text-xs text-slate-500 mt-0.5">Manage products with images, SEO slugs, category hierarchy, and competitive pricing.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-sm shadow-indigo-600/30">
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add New Product</span>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
            <!-- Search Input -->
            <div class="relative">
                <input
                    type="text"
                    name="search"
                    value="{{ $filters['search'] ?? '' }}"
                    placeholder="Search by name, SKU..."
                    class="w-full rounded-xl border border-slate-300 pl-3 pr-3 py-2 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none"
                >
            </div>

            <!-- Category Filter -->
            <div>
                <select
                    name="category_id"
                    id="filter_category"
                    onchange="this.form.submit()"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none"
                >
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Subcategory Filter -->
            <div>
                <select
                    name="subcategory_id"
                    id="filter_subcategory"
                    onchange="this.form.submit()"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none"
                >
                    <option value="">All Subcategories</option>
                    @if(!empty($filters['category_id']))
                        @php
                            $selectedCat = $categories->firstWhere('id', $filters['category_id']);
                        @endphp
                        @if($selectedCat)
                            @foreach($selectedCat->subcategories as $sub)
                                <option value="{{ $sub->id }}" {{ ($filters['subcategory_id'] ?? '') == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->name }}
                                </option>
                            @endforeach
                        @endif
                    @endif
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-700 transition">
                    Filter
                </button>
                @if(!empty($filters['search']) || !empty($filters['category_id']) || !empty($filters['subcategory_id']))
                    <a href="{{ route('admin.products.index') }}" class="py-2 px-3 rounded-xl border border-slate-200 text-xs font-semibold text-slate-500 hover:bg-slate-50 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table Card -->
    <div class="rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
        @if($products->isEmpty())
            <div class="py-16 text-center">
                <p class="text-sm font-semibold text-slate-700">No products found.</p>
                <p class="text-xs text-slate-400 mt-1 mb-4">Try adjusting your filters or create a new product.</p>
                <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg bg-indigo-600 text-white">
                    + Add Product
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase">
                        <tr>
                            <th class="py-3 px-4">Product</th>
                            <th class="py-3 px-4">Category & Subcategory</th>
                            <th class="py-3 px-4">Price (৳)</th>
                            <th class="py-3 px-4">Stock Status</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($products as $prod)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="relative shrink-0">
                                            <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="size-12 rounded-xl object-cover bg-slate-100 border border-slate-200">
                                            @if($prod->images->count() > 0)
                                                <span class="absolute -bottom-1 -right-1 bg-indigo-600 text-white text-[9px] font-bold px-1 rounded-full shadow-xs" title="{{ $prod->images->count() }} gallery images">
                                                    +{{ $prod->images->count() }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <span class="font-bold text-slate-900 text-xs block truncate max-w-xs">{{ $prod->name }}</span>
                                            <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                                <span class="font-mono text-[10px] bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded">{{ $prod->sku }}</span>
                                                @if($prod->variants->count() > 0)
                                                    <span class="text-[10px] bg-purple-50 text-purple-700 font-semibold px-1.5 py-0.5 rounded">
                                                        {{ $prod->variants->count() }} variants
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded w-fit">
                                            {{ $prod->category->name ?? 'N/A' }}
                                        </span>
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded w-fit">
                                            {{ $prod->subcategory->name ?? 'N/A' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="font-bold text-xs text-emerald-600">৳{{ number_format($prod->new_price, 2) }}</span>
                                        @if($prod->old_price > $prod->new_price)
                                            <span class="text-[10px] text-slate-400 line-through">৳{{ number_format($prod->old_price, 2) }}</span>
                                        @endif
                                    </div>
                                    @if($prod->discount_percentage > 0)
                                        <div class="mt-0.5">
                                            <span class="text-[10px] font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded">
                                                -{{ $prod->discount_percentage }}% OFF
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($prod->stock_status === 'out_of_stock')
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-red-700 bg-red-50 px-2.5 py-0.5 rounded-full">
                                            <span class="size-1.5 rounded-full bg-red-500"></span> Out of Stock (0)
                                        </span>
                                    @elseif($prod->stock_status === 'low_stock')
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-full">
                                            <span class="size-1.5 rounded-full bg-amber-500 animate-pulse"></span> Low Stock ({{ $prod->stock_quantity }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                                            <span class="size-1.5 rounded-full bg-emerald-500"></span> In Stock ({{ $prod->stock_quantity }})
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <button
                                        type="button"
                                        onclick="toggleStatus('{{ route('admin.products.toggle-status', $prod->id) }}', this, 'Active', 'Inactive')"
                                        class="group inline-flex items-center gap-2.5 cursor-pointer focus:outline-none"
                                        title="Click to toggle status"
                                    >
                                        <span class="toggle-track relative inline-block h-5 w-9 shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out {{ $prod->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}">
                                            <span class="toggle-thumb pointer-events-none absolute top-[2px] left-[2px] h-4 w-4 rounded-full bg-white shadow-sm transition-transform duration-200 ease-in-out {{ $prod->is_active ? 'translate-x-full' : 'translate-x-0' }}"></span>
                                        </span>
                                        <span class="toggle-label text-xs font-semibold select-none {{ $prod->is_active ? 'text-emerald-700' : 'text-slate-400' }}">
                                            {{ $prod->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </button>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('products.show', $prod->slug) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 p-1" title="View Details">
                                            View
                                        </a>
                                        <a href="{{ route('admin.products.edit', $prod->id) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 p-1" title="Edit Product">
                                            Edit
                                        </a>
                                        <button
                                            type="button"
                                            onclick="openDeleteModal({{ $prod->id }}, '{{ addslashes($prod->name) }}')"
                                            class="text-xs font-semibold text-red-600 hover:text-red-800 p-1"
                                            title="Delete Product"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $products->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-red-200">
        <div class="flex items-center gap-3 text-red-600 mb-3">
            <div class="rounded-full bg-red-100 p-2.5">
                <svg class="size-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 text-base">Delete Product</h3>
                <p class="text-xs text-slate-500">This action will delete the product and its uploaded media.</p>
            </div>
        </div>

        <p class="text-xs text-slate-700 my-3">Are you sure you want to permanently delete <strong id="delete_prod_name"></strong>?</p>

        <form id="deleteForm" method="POST" class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">Cancel</button>
            <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 text-xs font-bold text-white hover:bg-red-700">Confirm Delete</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openDeleteModal(id, name) {
        document.getElementById('deleteForm').action = '/admin/products/' + id;
        document.getElementById('delete_prod_name').innerText = name;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
