@extends('layouts.app')

@section('title', 'Edit Product')
@section('header_title', 'Edit Product')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Link -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition">
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Products</span>
        </a>

        <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">
            <span>View Public Storefront</span>
            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
    </div>

    <!-- Form Container -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 md:p-8 shadow-xs">
        <div class="pb-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">Edit Product: {{ $product->name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="font-mono text-xs bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-bold">{{ $product->sku }}</span>
                    <span class="text-xs text-slate-400">/{{ $product->slug }}</span>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-8" id="editProductForm">
            @csrf
            @method('PUT')

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
                            <option value="" disabled>-- Select Parent Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
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
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none bg-white"
                        >
                            <option value="" disabled>-- Select Subcategory --</option>
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
                        value="{{ old('name', $product->name) }}"
                        required
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
                        class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none resize-y"
                    >{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- 3. Primary Product Image (Optional Replacement) -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">3. Primary Cover Image</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                    <div class="sm:col-span-2 relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 p-6 text-center hover:bg-slate-50 transition cursor-pointer">
                        <input
                            type="file"
                            id="image_input"
                            name="image"
                            accept="image/*"
                            onchange="previewImage(event)"
                            class="absolute inset-0 size-full opacity-0 cursor-pointer"
                        >
                        <svg class="size-8 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs font-semibold text-slate-700">Click or drag new image to replace cover</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">Leave empty to keep existing image</p>
                    </div>

                    <!-- Live Image Preview Box -->
                    <div class="flex flex-col items-center justify-center">
                        <div class="size-28 rounded-2xl border border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden shadow-xs">
                            <img id="preview_img" src="{{ $product->image_url }}" class="size-full object-cover" alt="Preview">
                        </div>
                        <span class="text-[11px] text-slate-400 mt-1">Current Cover Image</span>
                    </div>
                </div>
                @error('image')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- 4. Gallery Images (Existing & Add New) -->
            <div class="space-y-4 rounded-xl bg-slate-50/60 border border-slate-200/80 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">4. Product Gallery Images</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Manage existing thumbnails and upload new photos.</p>
                    </div>
                </div>

                <!-- Existing Gallery Thumbnails -->
                @if($product->images->count() > 0)
                    <div>
                        <p class="text-[11px] font-bold text-slate-600 mb-2">Existing Gallery Photos (Check box or click trash to remove on save):</p>
                        <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                            @foreach($product->images as $galleryImg)
                                <div class="relative aspect-square rounded-xl overflow-hidden border border-slate-200 bg-white group shadow-2xs" id="gallery_item_{{ $galleryImg->id }}">
                                    <img src="{{ $galleryImg->image_url }}" alt="Gallery" class="size-full object-cover">
                                    <label class="absolute inset-0 bg-red-900/60 text-white flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer p-1 text-center">
                                        <input type="checkbox" name="delete_images[]" value="{{ $galleryImg->id }}" class="size-4 rounded text-red-600 focus:ring-red-500 mb-1">
                                        <span class="text-[9px] font-bold">Mark Delete</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Upload Additional Photos -->
                <div class="relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-white p-6 text-center hover:bg-slate-50 transition cursor-pointer">
                    <input
                        type="file"
                        id="gallery_input"
                        name="gallery[]"
                        accept="image/*"
                        multiple
                        onchange="previewGalleryImages(event)"
                        class="absolute inset-0 size-full opacity-0 cursor-pointer"
                    >
                    <svg class="size-8 text-indigo-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <p class="text-xs font-bold text-slate-800">Add More Photos to Gallery</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Select multiple images to append to gallery</p>
                </div>

                <div id="gallery_preview_container" class="hidden grid grid-cols-4 sm:grid-cols-6 gap-3 pt-2"></div>
                @error('gallery.*')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- 5. Pricing & Stock Management -->
            <div class="rounded-xl bg-slate-50 border border-slate-200/80 p-5 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">5. Pricing & Stock Management</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
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
                                value="{{ old('old_price', $product->old_price) }}"
                                required
                                oninput="calculateDiscount()"
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
                                value="{{ old('new_price', $product->new_price) }}"
                                required
                                oninput="calculateDiscount()"
                                class="w-full rounded-xl border border-slate-300 pl-8 pr-3 py-2 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none bg-white"
                            >
                        </div>
                        @error('new_price')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Quantity -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Stock Quantity <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            id="stock_quantity"
                            name="stock_quantity"
                            min="0"
                            value="{{ old('stock_quantity', $product->stock_quantity) }}"
                            required
                            class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none bg-white"
                        >
                        <p class="text-[10px] text-slate-400 mt-1">
                            Status: <strong class="text-slate-700">{{ $product->stock_status_label }}</strong>
                        </p>
                        @error('stock_quantity')
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

            <!-- 6. Product Variants -->
            <div class="space-y-4 rounded-xl bg-slate-50 border border-slate-200/80 p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">6. Product Variants (Color, Size, Storage, etc.)</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Define custom choices for customers.</p>
                    </div>
                    <button
                        type="button"
                        onclick="addVariantRow()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition shadow-xs self-start"
                    >
                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Variant</span>
                    </button>
                </div>

                <div id="variants_header" class="{{ $product->variants->count() > 0 ? '' : 'hidden' }} sm:grid grid-cols-12 gap-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 pt-1">
                    <div class="col-span-2">Type (e.g. Color)</div>
                    <div class="col-span-3">Option Name</div>
                    <div class="col-span-3">Variant Photo</div>
                    <div class="col-span-2">Price (৳) (Opt)</div>
                    <div class="col-span-1">Stock</div>
                    <div class="col-span-1 text-right">Delete</div>
                </div>

                <div id="variants_container" class="space-y-2">
                    <!-- Variant rows inserted dynamically via JS -->
                </div>
                <p id="no_variants_text" class="text-xs text-slate-400 italic py-2 {{ $product->variants->count() > 0 ? 'hidden' : '' }}">
                    No variants added. Click "Add Variant" above to add choices like Color or Size.
                </p>
            </div>

            <!-- 7. Active Status Toggle -->
            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-200">
                <div>
                    <label for="is_active" class="text-xs font-bold text-slate-800 block cursor-pointer">Active Status</label>
                    <span class="text-[11px] text-slate-400">Keep product published and visible in storefront</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                </label>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit" id="submit_btn" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm shadow-indigo-600/30">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const categoriesData = @json($categories);
    const initialSubcatId = {{ old('subcategory_id', $product->subcategory_id) }};
    const existingVariants = @json($product->variants);
    let variantIndex = 0;

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
            previewImg.src = URL.createObjectURL(file);
        }
    }

    function previewGalleryImages(event) {
        const files = event.target.files;
        const container = document.getElementById('gallery_preview_container');
        container.innerHTML = '';

        if (files && files.length > 0) {
            container.classList.remove('hidden');
            Array.from(files).forEach((file) => {
                const div = document.createElement('div');
                div.className = 'relative aspect-square rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-xs';
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'size-full object-cover';
                div.appendChild(img);
                container.appendChild(div);
            });
        } else {
            container.classList.add('hidden');
        }
    }

    function addVariantRow(data = {}) {
        const container = document.getElementById('variants_container');
        const emptyText = document.getElementById('no_variants_text');
        const header = document.getElementById('variants_header');
        emptyText.classList.add('hidden');
        if (header) header.classList.remove('hidden');

        const idx = variantIndex++;
        const row = document.createElement('div');
        row.id = `variant_row_${idx}`;
        row.className = 'grid grid-cols-1 sm:grid-cols-12 gap-2 items-center bg-white p-3 rounded-xl border border-slate-200 shadow-2xs';

        row.innerHTML = `
            ${data.id ? `<input type="hidden" name="variants[${idx}][id]" value="${data.id}">` : ''}
            <div class="sm:col-span-2">
                <input type="text" name="variants[${idx}][attribute_name]" value="${data.attribute_name || 'Color'}" placeholder="Type (e.g. Color)" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs text-slate-800 outline-none focus:border-purple-600">
            </div>
            <div class="sm:col-span-3">
                <input type="text" name="variants[${idx}][attribute_value]" value="${data.attribute_value || ''}" placeholder="Option (e.g. Blue Titanium)" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs text-slate-800 outline-none focus:border-purple-600">
            </div>
            <div class="sm:col-span-3 flex items-center gap-1.5">
                ${data.image_url ? `<img src="${data.image_url}" class="size-7 rounded-lg object-cover border border-slate-200 shrink-0" alt="thumb">` : ''}
                <input type="file" name="variants[${idx}][image]" accept="image/*" title="Upload or replace photo" class="w-full text-[10px] text-slate-500 file:mr-1 file:py-1 file:px-1.5 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 cursor-pointer">
            </div>
            <div class="sm:col-span-2">
                <input type="number" step="0.01" name="variants[${idx}][price]" value="${data.price !== null && data.price !== undefined ? data.price : ''}" placeholder="Price (৳)" class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs text-slate-800 outline-none focus:border-purple-600">
            </div>
            <div class="sm:col-span-1">
                <input type="number" min="0" name="variants[${idx}][stock_quantity]" value="${data.stock_quantity ?? 5}" placeholder="Qty" title="Stock Quantity" class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs text-slate-800 outline-none focus:border-purple-600">
            </div>
            <div class="sm:col-span-1 text-right">
                <button type="button" onclick="removeVariantRow(${idx})" class="p-1.5 text-slate-400 hover:text-red-600 transition" title="Remove variant">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        `;

        container.appendChild(row);
    }

    function removeVariantRow(idx) {
        const row = document.getElementById(`variant_row_${idx}`);
        if (row) row.remove();

        const container = document.getElementById('variants_container');
        if (container.children.length === 0) {
            document.getElementById('no_variants_text').classList.remove('hidden');
            const header = document.getElementById('variants_header');
            if (header) header.classList.add('hidden');
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

    document.addEventListener('DOMContentLoaded', () => {
        onCategoryChange(initialSubcatId);
        calculateDiscount();

        // Populate existing variants
        if (existingVariants && existingVariants.length > 0) {
            existingVariants.forEach(v => addVariantRow(v));
        }
    });
</script>
@endpush
@endsection
