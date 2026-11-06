<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import {
    Plus,
    Pencil,
    Trash2,
    Package,
    Search,
    Filter,
    Eye,
    Tags,
    Layers,
    AlertTriangle,
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
    discount_percentage: number;
    saved_amount: number;
    is_active: boolean;
    created_at: string;
    category?: { id: number; name: string };
    subcategory?: { id: number; name: string };
}

interface Pagination<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
}

const props = defineProps<{
    products: Pagination<Product>;
    categories: Category[];
    filters: {
        search?: string;
        category_id?: string;
        subcategory_id?: string;
    };
}>();

defineOptions({
    layout: AppLayout,
});

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category_id || '');
const selectedSubcategory = ref(props.filters.subcategory_id || '');

// Delete state
const isDeleteModalOpen = ref(false);
const productToDelete = ref<Product | null>(null);

// Subcategories available based on selected category
const filteredSubcategories = ref<Subcategory[]>([]);

watch(
    selectedCategory,
    (catId) => {
        if (!catId) {
            filteredSubcategories.value = [];
            selectedSubcategory.value = '';
            return;
        }
        const found = props.categories.find((c) => String(c.id) === String(catId));
        filteredSubcategories.value = found ? found.subcategories : [];
        if (!filteredSubcategories.value.some((s) => String(s.id) === String(selectedSubcategory.value))) {
            selectedSubcategory.value = '';
        }
    },
    { immediate: true }
);

// Apply filter with debounce or trigger
const applyFilters = () => {
    router.get(
        '/admin/products',
        {
            search: search.value || undefined,
            category_id: selectedCategory.value || undefined,
            subcategory_id: selectedSubcategory.value || undefined,
        },
        { preserveState: true, replace: true }
    );
};

const resetFilters = () => {
    search.value = '';
    selectedCategory.value = '';
    selectedSubcategory.value = '';
    router.get('/admin/products', {}, { replace: true });
};

const confirmDelete = (product: Product) => {
    productToDelete.value = product;
    isDeleteModalOpen.value = true;
};

const executeDelete = () => {
    if (!productToDelete.value) return;
    router.delete(`/admin/products/${productToDelete.value.id}`, {
        onSuccess: () => {
            isDeleteModalOpen.value = false;
            productToDelete.value = null;
        },
    });
};
</script>

<template>
    <Head title="Products Management" />

    <div class="flex flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground flex items-center gap-2">
                    <Package class="size-6 text-emerald-600 dark:text-emerald-400" />
                    Products Management
                </h1>
                <p class="text-sm text-muted-foreground mt-0.5">
                    Create, update, and manage products with categorization, SEO slugs, and pricing.
                </p>
            </div>
            <Link
                href="/admin/products/create"
                class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow-sm hover:bg-primary/90 transition"
            >
                <Plus class="size-4" />
                Add New Product
            </Link>
        </div>

        <!-- Filter Bar -->
        <div class="rounded-xl border border-border bg-card p-4 shadow-sm">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-4">
                <!-- Search -->
                <div class="relative">
                    <Search class="absolute left-3 top-2.5 size-4 text-muted-foreground" />
                    <input
                        v-model="search"
                        @keydown.enter="applyFilters"
                        type="text"
                        placeholder="Search by name, SKU..."
                        class="w-full rounded-lg border border-border bg-background pl-9 pr-3 py-2 text-xs text-foreground focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                    />
                </div>

                <!-- Category Filter -->
                <div>
                    <select
                        v-model="selectedCategory"
                        @change="applyFilters"
                        class="w-full rounded-lg border border-border bg-background px-3 py-2 text-xs text-foreground focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                    >
                        <option value="">All Categories</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                            {{ cat.name }}
                        </option>
                    </select>
                </div>

                <!-- Subcategory Filter -->
                <div>
                    <select
                        v-model="selectedSubcategory"
                        @change="applyFilters"
                        :disabled="!selectedCategory || filteredSubcategories.length === 0"
                        class="w-full rounded-lg border border-border bg-background px-3 py-2 text-xs text-foreground focus:border-primary focus:ring-1 focus:ring-primary outline-none disabled:opacity-50"
                    >
                        <option value="">All Subcategories</option>
                        <option v-for="sub in filteredSubcategories" :key="sub.id" :value="sub.id">
                            {{ sub.name }}
                        </option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <button
                        @click="applyFilters"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg bg-muted px-3 py-2 text-xs font-medium text-foreground hover:bg-accent transition"
                    >
                        <Filter class="size-3.5" /> Filter
                    </button>
                    <button
                        v-if="search || selectedCategory || selectedSubcategory"
                        @click="resetFilters"
                        class="rounded-lg border border-border px-3 py-2 text-xs font-medium text-muted-foreground hover:bg-muted transition"
                    >
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="rounded-xl border border-border bg-card shadow-sm overflow-hidden">
            <div v-if="products.data.length === 0" class="py-16 text-center">
                <Package class="mx-auto size-12 text-muted-foreground/30 mb-3" />
                <h3 class="text-base font-semibold text-foreground">No Products Found</h3>
                <p class="text-xs text-muted-foreground max-w-sm mx-auto mt-1 mb-4">
                    {{ search || selectedCategory ? 'No products match your filter criteria.' : 'Start adding products to your catalog.' }}
                </p>
                <Link
                    href="/admin/products/create"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3 py-1.5 text-xs font-medium text-primary-foreground hover:bg-primary/90 transition"
                >
                    <Plus class="size-3.5" /> Add Product
                </Link>
            </div>

            <div v-else class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-border bg-muted/40 text-xs uppercase text-muted-foreground">
                        <tr>
                            <th class="py-3 px-4">Product</th>
                            <th class="py-3 px-4">Category & Subcategory</th>
                            <th class="py-3 px-4">Pricing</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <tr v-for="product in products.data" :key="product.id" class="hover:bg-muted/30 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <img
                                        :src="product.image_url"
                                        :alt="product.name"
                                        class="size-12 rounded-lg object-cover border border-border bg-muted shrink-0"
                                    />
                                    <div class="min-w-0">
                                        <p class="font-semibold text-foreground line-clamp-1 leading-snug">{{ product.name }}</p>
                                        <div class="flex items-center gap-2 mt-0.5 text-xs text-muted-foreground">
                                            <span class="font-mono text-[11px] bg-muted px-1.5 py-0.5 rounded">{{ product.sku }}</span>
                                            <span class="font-mono text-[11px] text-muted-foreground">/{{ product.slug }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-col gap-1">
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-500/10 px-2 py-0.5 rounded w-fit">
                                        <Tags class="size-3" />
                                        {{ product.category?.name || 'N/A' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-purple-600 dark:text-purple-400 bg-purple-500/10 px-2 py-0.5 rounded w-fit">
                                        <Layers class="size-3" />
                                        {{ product.subcategory?.name || 'N/A' }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-baseline gap-2">
                                    <span class="font-bold text-emerald-600 dark:text-emerald-400 text-sm">
                                        ৳{{ Number(product.new_price).toLocaleString() }}
                                    </span>
                                    <span v-if="Number(product.old_price) > Number(product.new_price)" class="text-xs text-muted-foreground line-through">
                                        ৳{{ Number(product.old_price).toLocaleString() }}
                                    </span>
                                </div>
                                <div v-if="product.discount_percentage > 0" class="mt-1">
                                    <span class="inline-block rounded bg-red-500/10 px-1.5 py-0.5 text-[10px] font-semibold text-red-600 dark:text-red-400">
                                        {{ product.discount_percentage }}% OFF (Save ৳{{ Number(product.saved_amount).toLocaleString() }})
                                    </span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span
                                    v-if="product.is_active"
                                    class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:text-emerald-400"
                                >
                                    <span class="size-1.5 rounded-full bg-emerald-500"></span> Published
                                </span>
                                <span
                                    v-else
                                    class="inline-flex items-center gap-1 rounded-full bg-muted px-2.5 py-0.5 text-xs font-medium text-muted-foreground"
                                >
                                    <span class="size-1.5 rounded-full bg-muted-foreground"></span> Draft
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <Link
                                        :href="`/products/${product.slug}`"
                                        class="rounded p-1.5 text-muted-foreground hover:bg-muted hover:text-foreground transition"
                                        title="View SEO Details Page"
                                    >
                                        <Eye class="size-4" />
                                    </Link>
                                    <Link
                                        :href="`/admin/products/${product.id}/edit`"
                                        class="rounded p-1.5 text-muted-foreground hover:bg-primary/10 hover:text-primary transition"
                                        title="Edit Product"
                                    >
                                        <Pencil class="size-4" />
                                    </Link>
                                    <button
                                        @click="confirmDelete(product)"
                                        class="rounded p-1.5 text-muted-foreground hover:bg-destructive/10 hover:text-destructive transition"
                                        title="Delete Product"
                                    >
                                        <Trash2 class="size-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="products.total > products.per_page" class="border-t border-border p-4 flex items-center justify-between text-xs text-muted-foreground">
                <span>Showing {{ products.data.length }} of {{ products.total }} products</span>
                <div class="flex items-center gap-1">
                    <button
                        v-for="(link, i) in products.links"
                        :key="i"
                        :disabled="!link.url"
                        @click="link.url && router.visit(link.url)"
                        v-html="link.label"
                        :class="[
                            'px-2.5 py-1 rounded border text-xs transition',
                            link.active
                                ? 'bg-primary text-primary-foreground border-primary font-bold'
                                : 'bg-card border-border hover:bg-muted text-foreground disabled:opacity-40'
                        ]"
                    ></button>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div
            v-if="isDeleteModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4"
        >
            <div class="w-full max-w-md rounded-2xl border border-destructive/20 bg-card p-6 shadow-xl animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-center gap-3 text-destructive mb-3">
                    <div class="rounded-full bg-destructive/10 p-2.5">
                        <AlertTriangle class="size-6" />
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-foreground">Delete Product</h3>
                        <p class="text-xs text-muted-foreground">This action cannot be undone.</p>
                    </div>
                </div>

                <p class="text-xs text-foreground my-3">
                    Are you sure you want to delete <strong>{{ productToDelete?.name }}</strong>? The uploaded product image and all database records will be permanently removed.
                </p>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-border">
                    <button
                        type="button"
                        @click="isDeleteModalOpen = false"
                        class="rounded-lg border border-border px-4 py-2 text-xs font-medium text-foreground hover:bg-muted"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="executeDelete"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-destructive px-4 py-2 text-xs font-medium text-destructive-foreground hover:bg-destructive/90"
                    >
                        <Trash2 class="size-3.5" /> Confirm Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
