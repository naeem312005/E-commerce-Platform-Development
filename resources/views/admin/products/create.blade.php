@extends('layouts.app')

@section('title', 'Add New Product')

@section('header', 'Add New Product')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Top Back Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Products</span>
        </a>
    </div>

    <!-- Product Form Card -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 md:p-8 shadow-xs">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="productForm">
            @csrf

            <!-- 1. Category & Subcategory Selection (Dependent Dropdown) -->
            <div class="rounded-xl bg-slate-50 border border-slate-200/80 p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">1. Category Classification</h3>
                    <span class="text-[11px] font-semibold text-indigo-600">Dependent Cascading Dropdown</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Category Select -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Primary Category <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="category_id"
                            name="category_id"
                            required
                            onchange="onCategoryChange()"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none bg-white"
                        >
                            <option value="" disabled selected>-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->subcategories->count() }} subcategories)
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subcategory Select (Populated dynamically) -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold text-slate-700">
                                Subcategory <span class="text-red-500">*</span>
                            </label>
                            <span id="subcat_count_label" class="text-[10px] text-slate-400 font-medium"></span>
                        </div>
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

            <!-- 3. Primary Product Image -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">3. Primary Product Image <span class="text-red-500">*</span></h3>

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
                        <p class="text-xs font-semibold text-slate-700">Click or drag main image to upload</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">JPG, PNG, WEBP up to 5MB</p>
                    </div>

                    <!-- Live Image Preview Box -->
                    <div class="flex flex-col items-center justify-center">
                        <div id="preview_box" class="size-28 rounded-2xl border border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden shadow-xs">
                            <span id="preview_placeholder" class="text-[10px] text-slate-400 font-medium">No image</span>
                            <img id="preview_img" class="hidden size-full object-cover" alt="Preview">
                        </div>
                        <span class="text-[11px] text-slate-400 mt-1">Main Cover Preview</span>
                    </div>
                </div>
                @error('image')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- 4. Additional Gallery Images -->
            <div class="space-y-4 rounded-xl bg-slate-50/60 border border-slate-200/80 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">4. Product Gallery Images (Optional)</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Upload multiple photos for customer thumbnail view (up to 8 images).</p>
                    </div>
                </div>

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
                    <svg class="size-8 text-indigo-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <p class="text-xs font-bold text-slate-800">Select Multiple Photos for Gallery</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Hold Ctrl / Cmd to select several files at once</p>
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
                            Original Price (৳) <span class="text-red-500">*</span>
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
                            Sale Price (৳) <span class="text-red-500">*</span>
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
                            value="{{ old('stock_quantity', 10) }}"
                            required
                            placeholder="e.g. 15"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none bg-white"
                        >
                        <p class="text-[10px] text-slate-400 mt-1">0 = Out of Stock, 1-5 = Low Stock</p>
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
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition shadow-xs self-start"
                    >
                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Variant</span>
                    </button>
                </div>

                <div id="variants_header" class="hidden sm:grid grid-cols-12 gap-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 pt-1">
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
                <p id="no_variants_text" class="text-xs text-slate-400 italic py-2">
                    No variants added. Click "Add Variant" above to add choices like Color or Size.
                </p>
            </div>

            <!-- 7. Active Status Toggle -->
            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 size-4">
                <label for="is_active" class="text-xs font-bold text-slate-700 cursor-pointer">
                    Publish Immediately (Visible in product showcase & live catalog)
                </label>
            </div>

            <!-- Submit Button Bar -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Cancel
                </a>
                <button
                    type="submit"
                    id="submit_btn"
                    class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-md shadow-indigo-600/30 flex items-center gap-2"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Save Product</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const categoriesData = @json($categories);
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
            const placeholder = document.getElementById('preview_placeholder');
            previewImg.src = URL.createObjectURL(file);
            previewImg.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
    }

    function previewGalleryImages(event) {
        const files = event.target.files;
        const container = document.getElementById('gallery_preview_container');
        container.innerHTML = '';

        if (files && files.length > 0) {
            container.classList.remove('hidden');
            Array.from(files).forEach((file, idx) => {
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
            <div class="sm:col-span-2">
                <input type="text" name="variants[${idx}][attribute_name]" value="${data.attribute_name || 'Color'}" placeholder="Type (e.g. Color)" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs text-slate-800 outline-none focus:border-purple-600">
            </div>
            <div class="sm:col-span-3">
                <input type="text" name="variants[${idx}][attribute_value]" value="${data.attribute_value || ''}" placeholder="Option (e.g. Blue Titanium)" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs text-slate-800 outline-none focus:border-purple-600">
            </div>
            <div class="sm:col-span-3 flex items-center gap-1.5">
                <input type="file" name="variants[${idx}][image]" accept="image/*" title="Optional Variant Photo" class="w-full text-[10px] text-slate-500 file:mr-1.5 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 cursor-pointer">
            </div>
            <div class="sm:col-span-2">
                <input type="number" step="0.01" name="variants[${idx}][price]" value="${data.price || ''}" placeholder="Price (৳)" class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs text-slate-800 outline-none focus:border-purple-600">
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
