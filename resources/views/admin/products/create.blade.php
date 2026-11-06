@extends('layouts.app')

@section('title', 'Add New Product')
@section('header_title', 'Create Product')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Link -->
    <div>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition">
            ← Back to Products
        </a>
    </div>

    <!-- Form Container -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 md:p-8 shadow-xs">
        <div class="pb-6 border-b border-slate-100">
            <h2 class="text-xl font-extrabold text-slate-900">Add New Product</h2>
            <p class="text-xs text-slate-500 mt-0.5">Define categorization, media, pricing, and product details.</p>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf

            <!-- 1. Category & Subcategory Hierarchy -->
            <div class="rounded-xl bg-slate-50 border border-slate-200/80 p-5 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">1. Categorization & Hierarchy</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Category Select -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Parent Category <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="category_id"
                            name="category_id"
                            required
                            onchange="onCategoryChange()"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none bg-white"
                        >
                            <option value="" disabled selected>-- Select Parent Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ $cat->subcategories->count() }} subcategories)
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dynamic Subcategory Select -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                            <span>Subcategory <span class="text-red-500">*</span></span>
                            <span id="subcat_count_label" class="text-[10px] text-slate-400 font-normal"></span>
                        </label>
                        <select
                            id="subcategory_id"
                            name="subcategory_id"
                            required
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none bg-white disabled:opacity-50"
                        >
                            <option value="" disabled selected>-- Select a category first --</option>
                        </select>
                        @error('subcategory_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 2. Product Information -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">2. Basic Information</h3>

                <!-- Product Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        placeholder="e.g. Apple MacBook Pro 16-inch M3 Max"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none"
                    >
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Product Description <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        name="description"
                        rows="4"
                        required
                        placeholder="Detailed technical features, build material, warranty..."
                        class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none resize-y"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- 3. Image Upload with Live Preview -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">3. Product Image <span class="text-red-500">*</span></h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                    <div class="sm:col-span-2 relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 p-6 text-center hover:bg-slate-50 transition cursor-pointer">
                        <input
                            type="file"
                            id="image_input"
                            name="image"
                            accept="image/*"
                            required
                            onchange="previewImage(event)"
                            class="absolute inset-0 size-full opacity-0 cursor-pointer"
                        >
                        <svg class="size-8 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs font-semibold text-slate-700">Click or drag image to upload</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">JPG, PNG, WEBP up to 5MB</p>
                    </div>

                    <!-- Live Image Preview Box -->
                    <div class="flex flex-col items-center justify-center">
                        <div id="preview_box" class="size-28 rounded-2xl border border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden shadow-xs">
                            <span id="preview_placeholder" class="text-[10px] text-slate-400 font-medium">No image</span>
                            <img id="preview_img" class="hidden size-full object-cover" alt="Preview">
                        </div>
                        <span class="text-[11px] text-slate-400 mt-1">Live Preview</span>
                    </div>
                </div>
                @error('image')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- 4. Pricing & Discount Calculation -->
            <div class="rounded-xl bg-slate-50 border border-slate-200/80 p-5 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">4. Pricing (৳ BDT)</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Old Price -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Original / Old Price (৳) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-xs font-bold text-slate-400">৳</span>
                            <input
                                type="number"
                                id="old_price"
                                name="old_price"
                                step="0.01"
                                min="0"
                                value="{{ old('old_price') }}"
                                required
                                oninput="calculateDiscount()"
                                placeholder="150000"
                                class="w-full rounded-xl border border-slate-300 pl-8 pr-3 py-2 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none bg-white"
                            >
                        </div>
                        @error('old_price')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Price -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Current / New Price (৳) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-xs font-bold text-slate-400">৳</span>
                            <input
                                type="number"
                                id="new_price"
                                name="new_price"
                                step="0.01"
                                min="0"
                                value="{{ old('new_price') }}"
                                required
                                oninput="calculateDiscount()"
                                placeholder="135000"
                                class="w-full rounded-xl border border-slate-300 pl-8 pr-3 py-2 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none bg-white"
                            >
                        </div>
                        @error('new_price')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Dynamic Live Discount Calculator -->
                <div id="discount_banner" class="hidden p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center justify-between">
                    <span id="discount_percentage_text" class="font-bold"></span>
                    <span id="savings_text" class="font-medium text-emerald-700"></span>
                </div>
                <div id="price_warning" class="hidden p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700 font-bold">
                    ⚠️ New price cannot be greater than original old price!
                </div>
            </div>

            <!-- 5. Active Status Toggle -->
            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 size-4">
                <label for="is_active" class="text-xs font-bold text-slate-700 cursor-pointer">
                    Publish Immediately (Visible in product showcase & live catalog)
                </label>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit" id="submit_btn" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm shadow-indigo-600/30">
                    Save & Publish Product
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Embedded category data for dynamic dependent dropdown
    const categoriesData = @json($categories);

    function onCategoryChange(selectedSubcatId = null) {
        const catSelect = document.getElementById('category_id');
        const subSelect = document.getElementById('subcategory_id');
        const countLabel = document.getElementById('subcat_count_label');
        const catId = catSelect.value;

        subSelect.innerHTML = '';

        if (!catId) {
            subSelect.disabled = true;
            subSelect.innerHTML = '<option value="" disabled selected>-- Select a category first --</option>';
            countLabel.innerText = '';
            return;
        }

        const category = categoriesData.find(c => c.id == catId);
        if (category && category.subcategories && category.subcategories.length > 0) {
            subSelect.disabled = false;
            countLabel.innerText = `(${category.subcategories.length} available)`;

            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.disabled = true;
            defaultOpt.selected = !selectedSubcatId;
            defaultOpt.innerText = '-- Select Subcategory --';
            subSelect.appendChild(defaultOpt);

            category.subcategories.forEach(sub => {
                const opt = document.createElement('option');
                opt.value = sub.id;
                opt.innerText = sub.name;
                if (selectedSubcatId && sub.id == selectedSubcatId) {
                    opt.selected = true;
                }
                subSelect.appendChild(opt);
            });
        } else {
            subSelect.disabled = true;
            countLabel.innerText = '(0 subcategories)';
            const emptyOpt = document.createElement('option');
            emptyOpt.value = '';
            emptyOpt.disabled = true;
            emptyOpt.selected = true;
            emptyOpt.innerText = 'No subcategories in this category';
            subSelect.appendChild(emptyOpt);
        }
    }

    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const previewImg = document.getElementById('preview_img');
            const placeholder = document.getElementById('preview_placeholder');
            previewImg.src = URL.createObjectURL(file);
            previewImg.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
    }

    function calculateDiscount() {
        const oldP = parseFloat(document.getElementById('old_price').value);
        const newP = parseFloat(document.getElementById('new_price').value);
        const banner = document.getElementById('discount_banner');
        const warning = document.getElementById('price_warning');
        const submitBtn = document.getElementById('submit_btn');

        if (!isNaN(oldP) && !isNaN(newP) && oldP > 0) {
            if (newP > oldP) {
                banner.classList.add('hidden');
                warning.classList.remove('hidden');
                submitBtn.disabled = true;
            } else {
                warning.classList.add('hidden');
                submitBtn.disabled = false;

                const pct = Math.round(((oldP - newP) / oldP) * 100);
                const savings = oldP - newP;

                if (pct > 0) {
                    banner.classList.remove('hidden');
                    document.getElementById('discount_percentage_text').innerText = `✨ Discount Applied: ${pct}% OFF`;
                    document.getElementById('savings_text').innerText = `Customer saves: ৳${savings.toLocaleString()}`;
                } else {
                    banner.classList.add('hidden');
                }
            }
        } else {
            banner.classList.add('hidden');
            warning.classList.add('hidden');
            submitBtn.disabled = false;
        }
    }

    // Restore old values if validation redirected back
    document.addEventListener('DOMContentLoaded', () => {
        const oldCat = "{{ old('category_id') }}";
        const oldSub = "{{ old('subcategory_id') }}";
        if (oldCat) {
            onCategoryChange(oldSub);
        }
        calculateDiscount();
    });
</script>
@endpush
@endsection

