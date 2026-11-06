<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import {
    Store,
    Layers,
    Tags,
    ArrowRight,
    Package,
    Sparkles,
    Eye,
} from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface Product {
    id: number;
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
}

interface Subcategory {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    products: Product[];
}

interface Category {
    id: number;
    name: string;
    slug: string;
    subcategories: Subcategory[];
}

const props = defineProps<{
    categories: Category[];
    totalProducts: number;
}>();

defineOptions({
    layout: AppLayout,
});

const activeCategoryFilter = ref<number | 'all'>('all');

const filteredCategories = computed(() => {
    if (activeCategoryFilter.value === 'all') {
        return props.categories;
    }
    return props.categories.filter((c) => c.id === activeCategoryFilter.value);
});
</script>

<template>
    <Head title="Product Catalog Showcase" />

    <div class="flex flex-col gap-8 p-6 max-w-7xl mx-auto w-full">
        <!-- Hero Showcase Header -->
        <div class="rounded-2xl border border-border bg-gradient-to-r from-primary/10 via-card to-card p-6 md:p-8 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="inline-flex items-center gap-1.5 rounded-full bg-primary/15 px-3 py-1 text-xs font-semibold text-primary mb-2">
                        <Sparkles class="size-3.5" />
                        <span>Requirement 4: Products Organized by Subcategories</span>
                    </div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-foreground">
                        Product Catalog Showcase
                    </h1>
                    <p class="text-sm text-muted-foreground mt-1 max-w-2xl">
                        Explore all live products categorized and organized by their respective subcategories with SEO-friendly slugs and dynamic price calculation.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="rounded-xl border border-border bg-background/80 backdrop-blur-xs px-4 py-3 text-center shadow-xs">
                        <span class="block text-2xl font-black text-foreground">{{ totalProducts }}</span>
                        <span class="text-[11px] font-medium text-muted-foreground uppercase tracking-wider">Live Products</span>
                    </div>
                    <div class="rounded-xl border border-border bg-background/80 backdrop-blur-xs px-4 py-3 text-center shadow-xs">
                        <span class="block text-2xl font-black text-purple-600 dark:text-purple-400">
                            {{ categories.reduce((acc, cat) => acc + cat.subcategories.length, 0) }}
                        </span>
                        <span class="text-[11px] font-medium text-muted-foreground uppercase tracking-wider">Subcategories</span>
                    </div>
                </div>
            </div>

            <!-- Category Filter Tabs -->
            <div class="mt-6 flex flex-wrap items-center gap-2 border-t border-border/60 pt-4">
                <span class="text-xs font-semibold text-muted-foreground mr-1">Filter by Category:</span>
                <button
                    @click="activeCategoryFilter = 'all'"
                    class="rounded-full px-3.5 py-1 text-xs font-medium transition"
                    :class="[
                        activeCategoryFilter === 'all'
                            ? 'bg-primary text-primary-foreground font-semibold shadow-xs'
                            : 'bg-muted text-muted-foreground hover:bg-muted/80'
                    ]"
                >
                    All Categories
                </button>
                <button
                    v-for="cat in categories"
                    :key="cat.id"
                    @click="activeCategoryFilter = cat.id"
                    class="rounded-full px-3.5 py-1 text-xs font-medium transition"
                    :class="[
                        activeCategoryFilter === cat.id
                            ? 'bg-primary text-primary-foreground font-semibold shadow-xs'
                            : 'bg-muted text-muted-foreground hover:bg-muted/80'
                    ]"
                >
                    {{ cat.name }}
                </button>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="categories.length === 0" class="py-20 text-center rounded-2xl border border-dashed border-border bg-card">
            <Package class="mx-auto size-16 text-muted-foreground/30 mb-3" />
            <h3 class="text-lg font-bold text-foreground">No Products in Catalog</h3>
            <p class="text-xs text-muted-foreground max-w-sm mx-auto mt-1 mb-4">
                You haven't published any products under active subcategories yet.
            </p>
            <Link
                href="/admin/products/create"
                class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-xs font-medium text-primary-foreground hover:bg-primary/90"
            >
                Add Your First Product
            </Link>
        </div>

        <!-- Category & Subcategory Sections Loop -->
        <div v-else class="space-y-12">
            <div
                v-for="cat in filteredCategories"
                :key="cat.id"
                class="space-y-8"
            >
                <!-- Category Section Heading -->
                <div class="flex items-center gap-3 border-b-2 border-primary/20 pb-2">
                    <div class="rounded-lg bg-primary/10 p-2 text-primary">
                        <Tags class="size-5" />
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-foreground">{{ cat.name }}</h2>
                        <span class="text-xs text-muted-foreground">Category Slug: /{{ cat.slug }}</span>
                    </div>
                </div>

                <!-- Subcategories Under This Category -->
                <div
                    v-for="sub in cat.subcategories"
                    :key="sub.id"
                    class="rounded-2xl border border-border bg-card/60 p-5 shadow-xs space-y-4"
                >
                    <!-- Subcategory Header -->
                    <div class="flex items-center justify-between border-b border-border pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="rounded-md bg-purple-500/10 p-1.5 text-purple-600 dark:text-purple-400">
                                <Layers class="size-4" />
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-foreground flex items-center gap-2">
                                    {{ sub.name }}
                                    <span class="rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400 px-2 py-0.5 text-[11px] font-semibold">
                                        {{ sub.products.length }} {{ sub.products.length === 1 ? 'Product' : 'Products' }}
                                    </span>
                                </h3>
                                <p v-if="sub.description" class="text-xs text-muted-foreground mt-0.5">
                                    {{ sub.description }}
                                </p>
                            </div>
                        </div>

                        <span class="text-xs font-mono text-muted-foreground hidden sm:inline-block">
                            slug: {{ sub.slug }}
                        </span>
                    </div>

                    <!-- Products Grid for this Subcategory -->
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                        <div
                            v-for="product in sub.products"
                            :key="product.id"
                            class="group relative flex flex-col justify-between rounded-xl border border-border bg-card overflow-hidden shadow-xs hover:shadow-md transition hover:-translate-y-0.5"
                        >
                            <!-- Image Container -->
                            <div class="relative aspect-square w-full overflow-hidden bg-muted">
                                <img
                                    :src="product.image_url"
                                    :alt="product.name"
                                    class="size-full object-cover transition-transform duration-300 group-hover:scale-105"
                                />

                                <!-- Discount Badge -->
                                <div
                                    v-if="product.discount_percentage > 0"
                                    class="absolute top-2 left-2 rounded-full bg-red-600 px-2.5 py-0.5 text-[10px] font-bold text-white shadow-xs"
                                >
                                    -{{ product.discount_percentage }}% OFF
                                </div>

                                <!-- SKU Badge -->
                                <div class="absolute bottom-2 right-2 rounded bg-black/60 backdrop-blur-xs px-1.5 py-0.5 text-[10px] font-mono text-white">
                                    {{ product.sku }}
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-4 flex flex-col flex-1 justify-between gap-3">
                                <div>
                                    <h4 class="font-bold text-foreground text-sm line-clamp-1 group-hover:text-primary transition leading-snug">
                                        {{ product.name }}
                                    </h4>
                                    <p class="text-xs text-muted-foreground line-clamp-2 mt-1">
                                        {{ product.description }}
                                    </p>
                                </div>

                                <!-- Price Block -->
                                <div class="pt-2 border-t border-border/60">
                                    <div class="flex items-baseline justify-between">
                                        <div class="flex items-baseline gap-1.5">
                                            <span class="text-lg font-black text-emerald-600 dark:text-emerald-400">
                                                ৳{{ Number(product.new_price).toLocaleString() }}
                                            </span>
                                            <span
                                                v-if="Number(product.old_price) > Number(product.new_price)"
                                                class="text-xs text-muted-foreground line-through"
                                            >
                                                ৳{{ Number(product.old_price).toLocaleString() }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- SEO Details Page Link -->
                                    <Link
                                        :href="`/products/${product.slug}`"
                                        class="mt-3 flex w-full items-center justify-center gap-1.5 rounded-lg bg-muted py-2 text-xs font-semibold text-foreground hover:bg-primary hover:text-primary-foreground transition"
                                    >
                                        <Eye class="size-3.5" />
                                        View Details
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
