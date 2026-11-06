<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import {
    ArrowLeft,
    UploadCloud,
    X,
    Check,
    Package,
    Tags,
    Layers,
    DollarSign,
    Sparkles,
    AlertCircle,
} from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface Subcategory {
    id: number;
    name: string;
}

interface Category {
    id: number;
    name: string;
    subcategories: Subcategory[];
}

interface Product {
    id: number;
    category_id: number;
    subcategory_id: number;
    name: string;
    slug: string;
    sku: string;
    description: string;
    image_url: string;
    old_price: string;
    new_price: string;
    is_active: boolean;
}

const props = defineProps<{
    categories: Category[];
    product?: Product | null;
}>();

defineOptions({
    layout: AppLayout,
});

const isEditing = computed(() => Boolean(props.product));

// Form initialization
const form = useForm({
    category_id: (props.product?.category_id || '') as string | number,
    subcategory_id: (props.product?.subcategory_id || '') as string | number,
    name: props.product?.name || '',
    description: props.product?.description || '',
    image: null as File | null,
    old_price: (props.product?.old_price || '') as string | number,
    new_price: (props.product?.new_price || '') as string | number,
    is_active: props.product ? Boolean(props.product.is_active) : true,
    _method: isEditing.value ? 'PUT' : 'POST',
});

// Dynamic Dependent Subcategory List
const availableSubcategories = computed(() => {
    if (!form.category_id) return [];
    const cat = props.categories.find((c) => String(c.id) === String(form.category_id));
    return cat ? cat.subcategories : [];
});

// If category changes and selected subcategory does not belong to it, reset subcategory
watch(
    () => form.category_id,
    (newCatId, oldCatId) => {
        if (oldCatId && newCatId !== oldCatId) {
            const valid = availableSubcategories.value.some(
                (s) => String(s.id) === String(form.subcategory_id)
            );
            if (!valid) {
                form.subcategory_id = '';
            }
        }
    }
);

// Image Preview
const imagePreview = ref<string | null>(props.product?.image_url || null);
const isDragging = ref(false);

const handleFileSelect = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const handleDrop = (event: DragEvent) => {
    isDragging.value = false;
    if (event.dataTransfer?.files && event.dataTransfer.files[0]) {
        const file = event.dataTransfer.files[0];
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const removeSelectedImage = () => {
    form.image = null;
    imagePreview.value = props.product?.image_url || null;
};

// Live Discount Calculation
const calculatedDiscount = computed(() => {
    const oldP = parseFloat(String(form.old_price));
    const newP = parseFloat(String(form.new_price));

    if (isNaN(oldP) || isNaN(newP) || oldP <= 0) return null;

    if (newP > oldP) {
        return {
            isValid: false,
            message: 'New price cannot be higher than original old price!',
            percentage: 0,
            savings: 0,
        };
    }

    const percentage = Math.round(((oldP - newP) / oldP) * 100);
    const savings = oldP - newP;

    return {
        isValid: true,
        percentage,
        savings,
    };
});

// Form Submit
const submit = () => {
    if (isEditing.value && props.product) {
        form.post(`/admin/products/${props.product.id}`, {
            forceFormData: true,
        });
    } else {
        form.post('/admin/products', {
            forceFormData: true,
        });
    }
};
</script>

<template>
    <Head :title="isEditing ? 'Edit Product' : 'Add New Product'" />

    <div class="flex flex-col gap-6 p-6 max-w-4xl mx-auto w-full">
        <!-- Top Navigation -->
        <div class="flex items-center justify-between">
            <Link
                href="/admin/products"
                class="inline-flex items-center gap-1.5 text-xs font-medium text-muted-foreground hover:text-foreground transition"
            >
                <ArrowLeft class="size-4" /> Back to Products
            </Link>
            <span class="text-xs text-muted-foreground">
                <span class="text-destructive">*</span> indicates required field
            </span>
        </div>

        <!-- Header Card -->
        <div class="flex items-center gap-3">
            <div class="rounded-xl bg-primary/10 p-3 text-primary">
                <Package class="size-6" />
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">
                    {{ isEditing ? 'Edit Product: ' + product?.name : 'Create New Product' }}
                </h1>
                <p class="text-xs text-muted-foreground mt-0.5">
                    Fill in the product specifications, category hierarchy, media, and pricing details.
                </p>
            </div>
        </div>

        <!-- Form Card -->
        <form @submit.prevent="submit" class="space-y-6">
            <!-- 1. Categorization Section -->
            <div class="rounded-xl border border-border bg-card p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-semibold text-foreground flex items-center gap-2 border-b border-border pb-2">
                    <Tags class="size-4 text-primary" />
                    1. Categorization & Hierarchy
                </h3>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Category Select -->
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1">
                            Category <span class="text-destructive">*</span>
                        </label>
                        <select
                            v-model="form.category_id"
                            class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                            :class="{ 'border-destructive': form.errors.category_id }"
                        >
                            <option value="" disabled>-- Select Parent Category --</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                {{ cat.name }} ({{ cat.subcategories.length }} subcategories)
                            </option>
                        </select>
                        <p v-if="form.errors.category_id" class="mt-1 text-xs text-destructive">
                            {{ form.errors.category_id }}
                        </p>
                    </div>

                    <!-- Dynamic Subcategory Select -->
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1 flex items-center justify-between">
                            <span>Subcategory <span class="text-destructive">*</span></span>
                            <span v-if="form.category_id" class="text-[11px] text-muted-foreground">
                                ({{ availableSubcategories.length }} available)
                            </span>
                        </label>
                        <select
                            v-model="form.subcategory_id"
                            :disabled="!form.category_id || availableSubcategories.length === 0"
                            class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground focus:border-primary focus:ring-1 focus:ring-primary outline-none disabled:opacity-50"
                            :class="{ 'border-destructive': form.errors.subcategory_id }"
                        >
                            <option value="" disabled>
                                {{ !form.category_id ? 'First select a category above' : availableSubcategories.length === 0 ? 'No subcategories in this category' : '-- Select Subcategory --' }}
                            </option>
                            <option v-for="sub in availableSubcategories" :key="sub.id" :value="sub.id">
                                {{ sub.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.subcategory_id" class="mt-1 text-xs text-destructive">
                            {{ form.errors.subcategory_id }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- 2. Basic Information Section -->
            <div class="rounded-xl border border-border bg-card p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-semibold text-foreground flex items-center gap-2 border-b border-border pb-2">
                    <Package class="size-4 text-emerald-600" />
                    2. Product Information
                </h3>

                <!-- Product Name -->
                <div>
                    <label class="block text-xs font-semibold text-foreground mb-1">
                        Product Name <span class="text-destructive">*</span>
                    </label>
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="e.g. Apple iPhone 15 Pro Max 256GB"
                        class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                        :class="{ 'border-destructive': form.errors.name }"
                    />
                    <p v-if="form.errors.name" class="mt-1 text-xs text-destructive">
                        {{ form.errors.name }}
                    </p>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-semibold text-foreground mb-1">
                        Product Description <span class="text-destructive">*</span>
                    </label>
                    <textarea
                        v-model="form.description"
                        rows="4"
                        placeholder="Provide detailed description of features, specifications, and warranty..."
                        class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground focus:border-primary focus:ring-1 focus:ring-primary outline-none resize-y"
                        :class="{ 'border-destructive': form.errors.description }"
                    ></textarea>
                    <p v-if="form.errors.description" class="mt-1 text-xs text-destructive">
                        {{ form.errors.description }}
                    </p>
                </div>
            </div>

            <!-- 3. Image Upload Section -->
            <div class="rounded-xl border border-border bg-card p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-semibold text-foreground flex items-center gap-2 border-b border-border pb-2">
                    <UploadCloud class="size-4 text-blue-600" />
                    3. Product Image <span v-if="!isEditing" class="text-destructive">*</span>
                </h3>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 items-center">
                    <!-- Dropzone -->
                    <div
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleDrop"
                        class="sm:col-span-2 relative flex flex-col items-center justify-center rounded-xl border-2 border-dashed p-6 text-center transition cursor-pointer"
                        :class="[
                            isDragging ? 'border-primary bg-primary/5' : 'border-border bg-muted/20 hover:bg-muted/40',
                            form.errors.image ? 'border-destructive' : ''
                        ]"
                    >
                        <input
                            type="file"
                            accept="image/jpeg,image/png,image/jpg,image/webp,image/svg+xml"
                            @change="handleFileSelect"
                            class="absolute inset-0 size-full opacity-0 cursor-pointer"
                        />
                        <UploadCloud class="size-8 text-muted-foreground/60 mb-2" />
                        <p class="text-xs font-medium text-foreground">
                            Drag and drop product image here, or <span class="text-primary underline">browse</span>
                        </p>
                        <p class="text-[11px] text-muted-foreground mt-1">
                            Supported: JPG, PNG, WEBP, SVG (Max 5MB)
                        </p>
                    </div>

                    <!-- Live Preview -->
                    <div class="flex flex-col items-center justify-center">
                        <div v-if="imagePreview" class="relative group size-28 rounded-xl border border-border overflow-hidden bg-muted shadow-xs">
                            <img :src="imagePreview" alt="Product Preview" class="size-full object-cover" />
                            <button
                                v-if="form.image"
                                type="button"
                                @click="removeSelectedImage"
                                class="absolute top-1 right-1 rounded-full bg-black/70 p-1 text-white hover:bg-black transition"
                                title="Remove selected image"
                            >
                                <X class="size-3.5" />
                            </button>
                        </div>
                        <div v-else class="size-28 rounded-xl border border-dashed border-border flex flex-col items-center justify-center text-muted-foreground/40 bg-muted/10">
                            <Package class="size-8 mb-1" />
                            <span class="text-[10px]">No image</span>
                        </div>
                        <span class="text-[11px] text-muted-foreground mt-1.5">Live Preview</span>
                    </div>
                </div>

                <p v-if="form.errors.image" class="mt-1 text-xs text-destructive">
                    {{ form.errors.image }}
                </p>
            </div>

            <!-- 4. Pricing & Discount Section -->
            <div class="rounded-xl border border-border bg-card p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-semibold text-foreground flex items-center gap-2 border-b border-border pb-2">
                    <DollarSign class="size-4 text-emerald-600" />
                    4. Pricing & Valuation
                </h3>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Old Price -->
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1">
                            Original / Old Price (৳) <span class="text-destructive">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-xs font-bold text-muted-foreground">৳</span>
                            <input
                                v-model="form.old_price"
                                type="number"
                                step="0.01"
                                min="0"
                                placeholder="e.g. 150000"
                                class="w-full rounded-lg border border-border bg-background pl-8 pr-3 py-2 text-sm text-foreground focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                                :class="{ 'border-destructive': form.errors.old_price }"
                            />
                        </div>
                        <p v-if="form.errors.old_price" class="mt-1 text-xs text-destructive">
                            {{ form.errors.old_price }}
                        </p>
                    </div>

                    <!-- New Price -->
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1">
                            Current / New Price (৳) <span class="text-destructive">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-xs font-bold text-muted-foreground">৳</span>
                            <input
                                v-model="form.new_price"
                                type="number"
                                step="0.01"
                                min="0"
                                placeholder="e.g. 135000"
                                class="w-full rounded-lg border border-border bg-background pl-8 pr-3 py-2 text-sm text-foreground focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                                :class="{ 'border-destructive': form.errors.new_price }"
                            />
                        </div>
                        <p v-if="form.errors.new_price" class="mt-1 text-xs text-destructive">
                            {{ form.errors.new_price }}
                        </p>
                    </div>
                </div>

                <!-- Live Discount Indicator -->
                <div v-if="calculatedDiscount" class="mt-3">
                    <div
                        v-if="!calculatedDiscount.isValid"
                        class="flex items-center gap-2 rounded-lg bg-destructive/10 border border-destructive/20 p-3 text-xs text-destructive"
                    >
                        <AlertCircle class="size-4 shrink-0" />
                        <span>{{ calculatedDiscount.message }}</span>
                    </div>

                    <div
                        v-else-if="calculatedDiscount.percentage > 0"
                        class="flex items-center justify-between rounded-lg bg-emerald-500/10 border border-emerald-500/20 p-3 text-xs text-emerald-700 dark:text-emerald-300"
                    >
                        <div class="flex items-center gap-2">
                            <Sparkles class="size-4 text-emerald-600" />
                            <span>Calculated Discount: <strong>{{ calculatedDiscount.percentage }}% OFF</strong></span>
                        </div>
                        <span>Customer saves: <strong>৳{{ calculatedDiscount.savings.toLocaleString() }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- 5. Publishing Status -->
            <div class="rounded-xl border border-border bg-card p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <input
                        id="prod_active"
                        v-model="form.is_active"
                        type="checkbox"
                        class="rounded border-border text-primary focus:ring-primary size-4"
                    />
                    <label for="prod_active" class="text-xs font-medium text-foreground cursor-pointer">
                        Published & Active (Product will appear immediately in the public catalog showcase)
                    </label>
                </div>
            </div>

            <!-- Actions Bar -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <Link
                    href="/admin/products"
                    class="rounded-lg border border-border px-5 py-2.5 text-xs font-medium text-foreground hover:bg-muted transition"
                >
                    Cancel
                </Link>
                <button
                    type="submit"
                    :disabled="form.processing || (calculatedDiscount !== null && !calculatedDiscount.isValid)"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-6 py-2.5 text-xs font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50 transition shadow-sm"
                >
                    <Check class="size-4" />
                    {{ isEditing ? 'Update Product' : 'Create & Publish Product' }}
                </button>
            </div>
        </form>
    </div>
</template>
