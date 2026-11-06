<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Tags,
    Layers,
    Package,
    CheckCircle2,
    Plus,
    ArrowUpRight,
    Store,
    Eye,
} from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface Stats {
    total_categories: number;
    total_subcategories: number;
    total_products: number;
    active_products: number;
}

interface Product {
    id: number;
    name: string;
    slug: string;
    sku: string;
    image_url: string;
    old_price: string;
    new_price: string;
    discount_percentage: number;
    is_active: boolean;
    category?: { id: number; name: string };
    subcategory?: { id: number; name: string };
}

interface CategoryItem {
    id: number;
    name: string;
    subcategories_count: number;
    products_count: number;
}

defineProps<{
    stats: Stats;
    recentProducts: Product[];
    categories: CategoryItem[];
}>();

defineOptions({
    layout: AppLayout,
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <div class="flex flex-col gap-6 p-6">
        <!-- Header Banner -->
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">
                    E-commerce Overview
                </h1>
                <p class="text-sm text-muted-foreground">
                    Manage categories, subcategories, products and view live catalog showcase.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <Link
                    href="/admin/catalog"
                    class="inline-flex items-center gap-2 rounded-lg border border-border bg-card px-4 py-2 text-sm font-medium text-foreground shadow-sm hover:bg-accent transition"
                >
                    <Store class="size-4" />
                    View Catalog
                </Link>
                <Link
                    href="/admin/products/create"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow-sm hover:bg-primary/90 transition"
                >
                    <Plus class="size-4" />
                    Add Product
                </Link>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Categories -->
            <div class="rounded-xl border border-border bg-card p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Total Categories</span>
                    <div class="rounded-lg bg-blue-500/10 p-2.5 text-blue-600 dark:text-blue-400">
                        <Tags class="size-5" />
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-foreground">{{ stats.total_categories }}</span>
                    <Link href="/admin/categories" class="text-xs font-medium text-primary hover:underline inline-flex items-center gap-0.5">
                        Manage <ArrowUpRight class="size-3" />
                    </Link>
                </div>
            </div>

            <!-- Subcategories -->
            <div class="rounded-xl border border-border bg-card p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Subcategories</span>
                    <div class="rounded-lg bg-purple-500/10 p-2.5 text-purple-600 dark:text-purple-400">
                        <Layers class="size-5" />
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-foreground">{{ stats.total_subcategories }}</span>
                    <Link href="/admin/subcategories" class="text-xs font-medium text-primary hover:underline inline-flex items-center gap-0.5">
                        Manage <ArrowUpRight class="size-3" />
                    </Link>
                </div>
            </div>

            <!-- Products -->
            <div class="rounded-xl border border-border bg-card p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Total Products</span>
                    <div class="rounded-lg bg-emerald-500/10 p-2.5 text-emerald-600 dark:text-emerald-400">
                        <Package class="size-5" />
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-foreground">{{ stats.total_products }}</span>
                    <Link href="/admin/products" class="text-xs font-medium text-primary hover:underline inline-flex items-center gap-0.5">
                        Manage <ArrowUpRight class="size-3" />
                    </Link>
                </div>
            </div>

            <!-- Active Products -->
            <div class="rounded-xl border border-border bg-card p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Active in Store</span>
                    <div class="rounded-lg bg-amber-500/10 p-2.5 text-amber-600 dark:text-amber-400">
                        <CheckCircle2 class="size-5" />
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-foreground">{{ stats.active_products }}</span>
                    <span class="text-xs text-muted-foreground">Published</span>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Recent Products List (2 Cols) -->
            <div class="rounded-xl border border-border bg-card p-5 shadow-sm lg:col-span-2">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-foreground">Recent Products</h2>
                    <Link href="/admin/products" class="text-sm font-medium text-primary hover:underline">
                        View All Products
                    </Link>
                </div>

                <div v-if="recentProducts.length === 0" class="flex flex-col items-center justify-center py-10 text-center">
                    <Package class="size-10 text-muted-foreground/40 mb-2" />
                    <p class="text-sm font-medium text-foreground">No products added yet</p>
                    <p class="text-xs text-muted-foreground mb-4">Start by adding your first product.</p>
                    <Link
                        href="/admin/products/create"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3 py-1.5 text-xs font-medium text-primary-foreground hover:bg-primary/90"
                    >
                        <Plus class="size-3.5" /> Add Product
                    </Link>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-border text-xs uppercase text-muted-foreground">
                            <tr>
                                <th class="py-3 px-3">Product</th>
                                <th class="py-3 px-3">Category</th>
                                <th class="py-3 px-3">Pricing</th>
                                <th class="py-3 px-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <tr v-for="product in recentProducts" :key="product.id" class="hover:bg-muted/40 transition">
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-3">
                                        <img
                                            :src="product.image_url"
                                            :alt="product.name"
                                            class="size-10 rounded-lg object-cover border border-border bg-muted"
                                        />
                                        <div>
                                            <p class="font-medium text-foreground leading-snug line-clamp-1">{{ product.name }}</p>
                                            <span class="text-xs text-muted-foreground">{{ product.sku }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-foreground">{{ product.category?.name || 'N/A' }}</span>
                                        <span class="text-xs text-muted-foreground">{{ product.subcategory?.name || 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="font-semibold text-emerald-600 dark:text-emerald-400">৳{{ Number(product.new_price).toLocaleString() }}</span>
                                        <span v-if="Number(product.old_price) > Number(product.new_price)" class="text-xs text-muted-foreground line-through">
                                            ৳{{ Number(product.old_price).toLocaleString() }}
                                        </span>
                                    </div>
                                    <span v-if="product.discount_percentage > 0" class="inline-block rounded bg-red-500/10 px-1.5 py-0.5 text-[10px] font-semibold text-red-600 dark:text-red-400">
                                        {{ product.discount_percentage }}% OFF
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <Link
                                        :href="`/products/${product.slug}`"
                                        class="inline-flex items-center gap-1 rounded px-2 py-1 text-xs font-medium text-primary hover:bg-primary/10 transition"
                                    >
                                        <Eye class="size-3.5" /> View
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Categories Summary (1 Col) -->
            <div class="rounded-xl border border-border bg-card p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-foreground">Categories</h2>
                    <Link href="/admin/categories" class="text-sm font-medium text-primary hover:underline">
                        Manage
                    </Link>
                </div>

                <div v-if="categories.length === 0" class="py-10 text-center">
                    <p class="text-xs text-muted-foreground">No categories found.</p>
                </div>

                <div v-else class="space-y-3">
                    <div
                        v-for="cat in categories"
                        :key="cat.id"
                        class="flex items-center justify-between rounded-lg border border-border/60 p-3 hover:bg-muted/30 transition"
                    >
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-primary/10 p-2 text-primary">
                                <Tags class="size-4" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-foreground">{{ cat.name }}</p>
                                <p class="text-xs text-muted-foreground">{{ cat.subcategories_count }} Subcategories</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-muted px-2.5 py-0.5 text-xs font-semibold text-foreground">
                            {{ cat.products_count }} Products
                        </span>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-border">
                    <Link
                        href="/admin/categories"
                        class="flex w-full items-center justify-center gap-2 rounded-lg border border-border py-2 text-xs font-medium text-foreground hover:bg-accent transition"
                    >
                        <Plus class="size-3.5" /> Add New Category
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
