@extends('layouts.guest')

@section('title', $product->name . ' - Product Details')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Store</a>
        <span>/</span>
        <a href="{{ route('catalog.public') }}" class="hover:text-indigo-600 transition">Catalog</a>
        <span>/</span>
        <span class="text-blue-600">{{ $product->category->name ?? 'Category' }}</span>
        <span>/</span>
        <span class="text-purple-600">{{ $product->subcategory->name ?? 'Subcategory' }}</span>
        <span>/</span>
        <span class="text-slate-900 truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    <!-- Main Product Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 md:p-10 shadow-xs">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
            <!-- Left: High-Res Image -->
            <div class="space-y-4">
                <div class="relative aspect-square w-full rounded-3xl border border-slate-200/80 bg-slate-100 overflow-hidden shadow-xs">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="size-full object-cover">

                    @if($product->discount_percentage > 0)
                        <div class="absolute top-4 left-4 rounded-full bg-red-600 px-3.5 py-1 text-xs font-black text-white shadow-md">
                            {{ $product->discount_percentage }}% OFF
                        </div>
                    @endif

                    <div class="absolute bottom-4 right-4 rounded-xl bg-black/80 backdrop-blur-xs px-3 py-1 font-mono text-xs text-white">
                        {{ $product->sku }}
                    </div>
                </div>

            </div>

            <!-- Right: Details & Pricing -->
            <div class="space-y-6">
                <!-- Badges -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold">
                        {{ $product->category->name ?? 'Unassigned' }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-50 text-purple-700 text-xs font-bold">
                        {{ $product->subcategory->name ?? 'Unassigned' }}
                    </span>
                    @if($product->is_active)
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold">
                            <span class="size-1.5 rounded-full bg-emerald-500"></span> In Stock
                        </span>
                    @endif
                </div>

                <!-- Title -->
                <div>
                    <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                        {{ $product->name }}
                    </h1>
                    <p class="text-xs text-slate-400 mt-1 font-mono">
                        Product SKU: <strong class="text-slate-700">{{ $product->sku }}</strong>
                    </p>
                </div>

                <!-- Price Box -->
                <div class="rounded-2xl bg-slate-50 border border-slate-200/80 p-5">
                    <div class="flex items-baseline gap-3">
                        <span class="text-3xl md:text-4xl font-black text-emerald-600">
                            ৳{{ number_format($product->new_price, 2) }}
                        </span>
                        @if($product->old_price > $product->new_price)
                            <span class="text-lg text-slate-400 line-through">
                                ৳{{ number_format($product->old_price, 2) }}
                            </span>
                        @endif
                    </div>

                    @if($product->discount_percentage > 0)
                        <div class="mt-2 text-xs font-bold text-emerald-700 flex items-center gap-1.5">
                            <span>✨ You save ৳{{ number_format($product->saved_amount, 2) }} ({{ $product->discount_percentage }}% Discount)</span>
                        </div>
                    @endif
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Description</h3>
                    <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                        {{ $product->description }}
                    </p>
                </div>

                <!-- Metadata Details -->
                <div class="pt-4 border-t border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Specifications</h3>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="p-3 rounded-xl bg-slate-50">
                            <span class="text-slate-400 block text-[10px]">Category</span>
                            <span class="font-bold text-slate-800">{{ $product->category->name ?? 'N/A' }}</span>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50">
                            <span class="text-slate-400 block text-[10px]">Subcategory</span>
                            <span class="font-bold text-slate-800">{{ $product->subcategory->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                    @auth
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition shadow-sm shadow-indigo-600/30">
                            Edit Product in Admin
                        </a>
                    @endauth
                    <a href="{{ route('catalog.public') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                        Back to Catalog
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products in Same Subcategory -->
    @if($relatedProducts->isNotEmpty())
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">
                    More in {{ $product->subcategory->name ?? 'This Subcategory' }}
                </h3>
                <a href="{{ route('catalog.public') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                    View All →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
                @foreach($relatedProducts as $rel)
                    <a href="{{ route('products.show', $rel->slug) }}" class="group rounded-2xl border border-slate-200/80 bg-white p-3 shadow-xs hover:shadow-lg transition flex flex-col justify-between">
                        <div class="aspect-square w-full rounded-xl bg-slate-100 overflow-hidden mb-3 relative">
                            <img src="{{ $rel->image_url }}" alt="{{ $rel->name }}" class="size-full object-cover group-hover:scale-105 transition">
                            @if($rel->discount_percentage > 0)
                                <span class="absolute top-2 left-2 rounded-full bg-red-600 px-2 py-0.5 text-[9px] font-black text-white">
                                    -{{ $rel->discount_percentage }}%
                                </span>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 line-clamp-1 group-hover:text-indigo-600 transition">{{ $rel->name }}</h4>
                            <div class="mt-1 flex items-baseline gap-1.5">
                                <span class="text-xs font-black text-emerald-600">৳{{ number_format($rel->new_price, 2) }}</span>
                                @if($rel->old_price > $rel->new_price)
                                    <span class="text-[10px] text-slate-400 line-through">৳{{ number_format($rel->old_price, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

