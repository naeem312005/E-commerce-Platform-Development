@extends('layouts.guest')

@section('title', 'Product Catalog Showcase')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
    <!-- Hero Showcase Header -->
    <div class="rounded-3xl border border-slate-200/80 bg-gradient-to-r from-indigo-50 via-white to-purple-50 p-6 md:p-10 shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wider mb-3">
                    ✨ Assignment Requirement 4
                </span>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                    Products Showcase by Subcategory
                </h1>
                <p class="text-sm text-slate-600 mt-2 max-w-2xl leading-relaxed">
                    All products organized by their respective subcategories, SEO-friendly slugs, dynamic pricing discounts, and instant details navigation.
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <div class="rounded-2xl border border-slate-200/80 bg-white/90 backdrop-blur-xs px-5 py-3 text-center shadow-xs">
                    <span class="block text-2xl font-black text-slate-900">{{ $totalProducts }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Live Products</span>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white/90 backdrop-blur-xs px-5 py-3 text-center shadow-xs">
                    <span class="block text-2xl font-black text-purple-600">
                        {{ $categories->sum(fn($c) => $c->subcategories->count()) }}
                    </span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Subcategories</span>
                </div>
            </div>
        </div>

        <!-- Quick Filter Tabs -->
        <div class="mt-8 pt-4 border-t border-slate-200/60 flex flex-wrap items-center gap-2">
            <span class="text-xs font-bold text-slate-400 mr-2 uppercase tracking-wider">Browse:</span>
            <button onclick="filterCategory('all')" id="tab_all" class="cat-tab px-4 py-1.5 rounded-full text-xs font-bold bg-indigo-600 text-white shadow-xs transition">
                All Categories
            </button>
            @foreach($categories as $cat)
                <button onclick="filterCategory({{ $cat->id }})" id="tab_{{ $cat->id }}" class="cat-tab px-4 py-1.5 rounded-full text-xs font-bold bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 transition">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Empty State -->
    @if($categories->isEmpty())
        <div class="py-20 text-center rounded-3xl border-2 border-dashed border-slate-200 bg-white p-8">
            <p class="text-base font-bold text-slate-700">No products are currently available in the catalog.</p>
            <p class="text-xs text-slate-400 mt-1 mb-4">Please log in to the admin panel and add products under active subcategories.</p>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl bg-indigo-600 text-white">
                Add Products in Admin
            </a>
        </div>
    @else
        <!-- Categories & Subcategories Sections -->
        <div class="space-y-14">
            @foreach($categories as $cat)
                <section id="cat_section_{{ $cat->id }}" class="cat-section space-y-8">
                    <!-- Category Header -->
                    <div class="flex items-center justify-between border-b-2 border-slate-200 pb-3">
                        <div class="flex items-center gap-3">
                            <span class="size-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-600/20">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            </span>
                            <div>
                                <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ $cat->name }}</h2>
                                <p class="text-xs text-slate-500 font-mono">Slug: /{{ $cat->slug }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                            {{ $cat->subcategories->count() }} Subcategories
                        </span>
                    </div>

                    <!-- Subcategories Under This Category -->
                    <div class="space-y-8">
                        @foreach($cat->subcategories as $sub)
                            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-xs space-y-5">
                                <!-- Subcategory Banner -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 gap-2">
                                    <div class="flex items-center gap-2.5">
                                        <span class="size-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs">
                                            #
                                        </span>
                                        <div>
                                            <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                                                {{ $sub->name }}
                                                <span class="text-[11px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full">
                                                    {{ $sub->products->count() }} {{ Str::plural('Product', $sub->products->count()) }}
                                                </span>
                                            </h3>
                                            @if($sub->description)
                                                <p class="text-xs text-slate-500 mt-0.5">{{ $sub->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-[11px] font-mono text-slate-400">
                                        sub-slug: {{ $sub->slug }}
                                    </span>
                                </div>

                                <!-- Product Grid for this Subcategory -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                                    @foreach($sub->products as $product)
                                        <div class="group flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white overflow-hidden shadow-xs hover:shadow-xl hover:-translate-y-1 transition duration-200">
                                            <!-- Product Image Container -->
                                            <div class="relative aspect-square w-full overflow-hidden bg-slate-100">
                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="size-full object-cover group-hover:scale-105 transition duration-300">

                                                <!-- Discount Pill -->
                                                @if($product->discount_percentage > 0)
                                                    <span class="absolute top-2.5 left-2.5 rounded-full bg-red-600 px-2.5 py-0.5 text-[10px] font-black text-white shadow-xs">
                                                        -{{ $product->discount_percentage }}% OFF
                                                    </span>
                                                @endif

                                                <!-- SKU Badge -->
                                                <span class="absolute bottom-2.5 right-2.5 rounded bg-black/75 backdrop-blur-xs px-2 py-0.5 text-[10px] font-mono text-white">
                                                    {{ $product->sku }}
                                                </span>
                                            </div>

                                            <!-- Content & Pricing -->
                                            <div class="p-4 flex flex-col flex-1 justify-between gap-3">
                                                <div>
                                                    <h4 class="font-bold text-slate-900 text-sm line-clamp-1 group-hover:text-indigo-600 transition leading-snug">
                                                        {{ $product->name }}
                                                    </h4>
                                                    <p class="text-xs text-slate-500 line-clamp-2 mt-1">
                                                        {{ $product->description }}
                                                    </p>
                                                </div>

                                                <div class="pt-3 border-t border-slate-100">
                                                    <div class="flex items-baseline justify-between mb-3">
                                                        <div class="flex items-baseline gap-1.5">
                                                            <span class="text-lg font-black text-emerald-600">
                                                                ৳{{ number_format($product->new_price, 2) }}
                                                            </span>
                                                            @if($product->old_price > $product->new_price)
                                                                <span class="text-xs text-slate-400 line-through">
                                                                    ৳{{ number_format($product->old_price, 2) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- SEO Slug Link -->
                                                    <a href="{{ route('products.show', $product->slug) }}" class="flex w-full items-center justify-center gap-1.5 py-2.5 rounded-xl bg-slate-100 hover:bg-indigo-600 hover:text-white text-xs font-bold text-slate-700 transition">
                                                        <span>View Details</span>
                                                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script>
    function filterCategory(catId) {
        const sections = document.querySelectorAll('.cat-section');
        const tabs = document.querySelectorAll('.cat-tab');

        tabs.forEach(t => {
            t.classList.remove('bg-indigo-600', 'text-white');
            t.classList.add('bg-white', 'text-slate-600');
        });

        if (catId === 'all') {
            document.getElementById('tab_all').classList.add('bg-indigo-600', 'text-white');
            document.getElementById('tab_all').classList.remove('bg-white', 'text-slate-600');
            sections.forEach(s => s.classList.remove('hidden'));
        } else {
            const activeTab = document.getElementById('tab_' + catId);
            if (activeTab) {
                activeTab.classList.add('bg-indigo-600', 'text-white');
                activeTab.classList.remove('bg-white', 'text-slate-600');
            }
            sections.forEach(s => {
                if (s.id === 'cat_section_' + catId) {
                    s.classList.remove('hidden');
                } else {
                    s.classList.add('hidden');
                }
            });
        }
    }
</script>
@endpush
@endsection
