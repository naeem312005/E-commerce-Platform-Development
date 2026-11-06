<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowLeft,
    CheckCircle2,
    Eye,
    Pencil,
    ShieldCheck,
    Sparkles,
    Store,
    Tags,
    Layers,
    Share2,
    Package,
} from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface CategoryInfo {
    id: number;
    name: string;
    slug: string;
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
    category?: CategoryInfo;
    subcategory?: CategoryInfo;
}

const props = defineProps<{
    product: Product;
    relatedProducts: Product[];
}>();

defineOptions({
    layout: AppLayout,
});
</script>

<template>
    <Head :title="`${product.name} - Product Details`" />

    <div class="flex flex-col gap-8 p-6 max-w-6xl mx-auto w-full">
        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs text-muted-foreground flex-wrap">
            <Link href="/dashboard" class="hover:text-foreground transition">Dashboard</Link>
            <span>/</span>
            <Link href="/admin/catalog" class="hover:text-foreground transition">Catalog</Link>
            <span>/</span>
            <span class="text-blue-600 dark:text-blue-400 font-medium">{{ product.category?.name || 'Category' }}</span>
            <span>/</span>
            <span class="text-purple-600 dark:text-purple-400 font-medium">{{ product.subcategory?.name || 'Subcategory' }}</span>
            <span>/</span>
            <span class="text-foreground font-semibold truncate max-w-xs">{{ product.name }}</span>
        </nav>

        <!-- Main Product Presentation Card -->
        <div class="rounded-2xl border border-border bg-card p-6 md:p-8 shadow-sm">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 items-start">
                <!-- Left: Product Image -->
                <div class="space-y-4">
                    <div class="relative aspect-square w-full rounded-2xl border border-border bg-muted overflow-hidden shadow-xs">
                        <img
                            :src="product.image_url"
                            :alt="product.name"
                            class="size-full object-cover transition-transform duration-300 hover:scale-105"
                        />

                        <!-- Floating Badges -->
                        <div
                            v-if="product.discount_percentage > 0"
                            class="absolute top-3 left-3 rounded-full bg-red-600 px-3 py-1 text-xs font-bold text-white shadow-sm flex items-center gap-1"
                        >
                            <Sparkles class="size-3.5" />
                            <span>{{ product.discount_percentage }}% OFF</span>
                        </div>

                        <div class="absolute bottom-3 right-3 rounded-lg bg-black/75 backdrop-blur-xs px-2.5 py-1 text-xs font-mono text-white">
                            {{ product.sku }}
                        </div>
                    </div>

                    <!-- SEO URL indicator box -->
                    <div class="rounded-xl border border-border/80 bg-muted/40 p-3 text-xs flex items-center justify-between">
                        <div class="truncate">
                            <span class="text-muted-foreground block text-[10px] uppercase font-bold tracking-wider">SEO Canonical URL:</span>
                            <code class="text-foreground font-mono text-[11px] truncate">
                                /products/<strong>{{ product.slug }}</strong>
                            </code>
                        </div>
                        <span class="rounded bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 px-2 py-0.5 text-[10px] font-bold shrink-0">
                            SEO Friendly
                        </span>
                    </div>
                </div>

                <!-- Right: Product Information & Pricing -->
                <div class="space-y-6">
                    <!-- Badges -->
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-500/10 px-3 py-1 text-xs font-semibold text-blue-600 dark:text-blue-400">
                            <Tags class="size-3.5" />
                            {{ product.category?.name || 'Unassigned' }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-500/10 px-3 py-1 text-xs font-semibold text-purple-600 dark:text-purple-400">
                            <Layers class="size-3.5" />
                            {{ product.subcategory?.name || 'Unassigned' }}
                        </span>
                        <span
                            v-if="product.is_active"
                            class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-600 dark:text-emerald-400"
                        >
                            <span class="size-1.5 rounded-full bg-emerald-500"></span> In Stock
                        </span>
                    </div>

                    <!-- Title -->
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-foreground leading-tight">
                            {{ product.name }}
                        </h1>
                        <p class="text-xs text-muted-foreground mt-1">
                            SKU: <span class="font-mono font-semibold text-foreground">{{ product.sku }}</span>
                        </p>
                    </div>

                    <!-- Pricing Box -->
                    <div class="rounded-xl border border-border bg-muted/20 p-4">
                        <div class="flex items-baseline gap-3">
                            <span class="text-3xl font-black text-emerald-600 dark:text-emerald-400">
                                ৳{{ Number(product.new_price).toLocaleString() }}
                            </span>
                            <span
                                v-if="Number(product.old_price) > Number(product.new_price)"
                                class="text-base text-muted-foreground line-through"
                            >
                                ৳{{ Number(product.old_price).toLocaleString() }}
                            </span>
                        </div>

                        <div
                            v-if="product.discount_percentage > 0"
                            class="mt-2 inline-flex items-center gap-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-700 dark:text-emerald-300"
                        >
                            <Sparkles class="size-3.5" />
                            <span>You save ৳{{ Number(product.saved_amount).toLocaleString() }} ({{ product.discount_percentage }}% Discount)</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Product Description</h3>
                        <p class="text-sm text-foreground leading-relaxed whitespace-pre-line">
                            {{ product.description }}
                        </p>
                    </div>

                    <!-- Technical Specifications Table -->
                    <div class="space-y-2 pt-2 border-t border-border">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Metadata Specifications</h3>
                        <dl class="grid grid-cols-2 gap-2 text-xs">
                            <div class="rounded-lg bg-muted/30 p-2.5">
                                <dt class="text-muted-foreground">Parent Category</dt>
                                <dd class="font-semibold text-foreground mt-0.5">{{ product.category?.name || 'N/A' }}</dd>
                            </div>
                            <div class="rounded-lg bg-muted/30 p-2.5">
                                <dt class="text-muted-foreground">Subcategory</dt>
                                <dd class="font-semibold text-foreground mt-0.5">{{ product.subcategory?.name || 'N/A' }}</dd>
                            </div>
                            <div class="rounded-lg bg-muted/30 p-2.5">
                                <dt class="text-muted-foreground">SKU Code</dt>
                                <dd class="font-mono font-semibold text-foreground mt-0.5">{{ product.sku }}</dd>
                            </div>
                            <div class="rounded-lg bg-muted/30 p-2.5">
                                <dt class="text-muted-foreground">Publish Date</dt>
                                <dd class="font-semibold text-foreground mt-0.5">
                                    {{ new Date(product.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3 pt-4 border-t border-border">
                        <Link
                            :href="`/admin/products/${product.id}/edit`"
                            class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-xs font-semibold text-primary-foreground hover:bg-primary/90 transition shadow-sm"
                        >
                            <Pencil class="size-4" />
                            Edit Product
                        </Link>
                        <Link
                            href="/admin/catalog"
                            class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-5 py-2.5 text-xs font-semibold text-foreground hover:bg-accent transition"
                        >
                            <Store class="size-4" />
                            Back to Catalog
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products in Same Subcategory -->
        <div v-if="relatedProducts.length > 0" class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-foreground flex items-center gap-2">
                    <Layers class="size-5 text-purple-600 dark:text-purple-400" />
                    More in {{ product.subcategory?.name }}
                </h3>
                <Link href="/admin/catalog" class="text-xs font-semibold text-primary hover:underline">
                    View Catalog
                </Link>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
                <Link
                    v-for="rel in relatedProducts"
                    :key="rel.id"
                    :href="`/products/${rel.slug}`"
                    class="group rounded-xl border border-border bg-card p-3 shadow-xs hover:shadow-md transition flex flex-col justify-between"
                >
                    <div class="aspect-square w-full rounded-lg bg-muted overflow-hidden mb-2 relative">
                        <img :src="rel.image_url" :alt="rel.name" class="size-full object-cover group-hover:scale-105 transition" />
                        <span v-if="rel.discount_percentage > 0" class="absolute top-1.5 left-1.5 rounded-full bg-red-600 px-2 py-0.5 text-[9px] font-bold text-white">
                            -{{ rel.discount_percentage }}%
                        </span>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold text-foreground line-clamp-1 group-hover:text-primary transition">{{ rel.name }}</h4>
                        <div class="mt-1 flex items-baseline gap-1.5">
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">৳{{ Number(rel.new_price).toLocaleString() }}</span>
                            <span v-if="Number(rel.old_price) > Number(rel.new_price)" class="text-[10px] text-muted-foreground line-through">
                                ৳{{ Number(rel.old_price).toLocaleString() }}
                            </span>
                        </div>
                    </div>
                </Link>
            </div>
        </div>
    </div>
</template>
