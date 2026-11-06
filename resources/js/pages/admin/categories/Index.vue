<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    Plus,
    Pencil,
    Trash2,
    Tags,
    X,
    Check,
    AlertTriangle,
    Layers,
    Package,
} from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface Category {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    is_active: boolean;
    subcategories_count: number;
    products_count: number;
    created_at: string;
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
    categories: Pagination<Category>;
}>();

defineOptions({
    layout: AppLayout,
});

// Modal state
const isModalOpen = ref(false);
const isEditing = ref(false);
const editingCategory = ref<Category | null>(null);

// Delete state
const isDeleteModalOpen = ref(false);
const categoryToDelete = ref<Category | null>(null);

// Form
const form = useForm({
    name: '',
    description: '',
    is_active: true,
});

const openCreateModal = () => {
    isEditing.value = false;
    editingCategory.value = null;
    form.reset();
    form.clearErrors();
    form.is_active = true;
    isModalOpen.value = true;
};

const openEditModal = (category: Category) => {
    isEditing.value = true;
    editingCategory.value = category;
    form.clearErrors();
    form.name = category.name;
    form.description = category.description || '';
    form.is_active = Boolean(category.is_active);
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    form.clearErrors();
};

const submitForm = () => {
    if (isEditing.value && editingCategory.value) {
        form.put(`/admin/categories/${editingCategory.value.id}`, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/admin/categories', {
            onSuccess: () => closeModal(),
        });
    }
};

const confirmDelete = (category: Category) => {
    categoryToDelete.value = category;
    isDeleteModalOpen.value = true;
};

const executeDelete = () => {
    if (!categoryToDelete.value) return;
    router.delete(`/admin/categories/${categoryToDelete.value.id}`, {
        onSuccess: () => {
            isDeleteModalOpen.value = false;
            categoryToDelete.value = null;
        },
    });
};
</script>

<template>
    <Head title="Category Management" />

    <div class="flex flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground flex items-center gap-2">
                    <Tags class="size-6 text-primary" />
                    Categories Management
                </h1>
                <p class="text-sm text-muted-foreground mt-0.5">
                    Organize your product hierarchy by creating and managing top-level product categories.
                </p>
            </div>
            <button
                @click="openCreateModal"
                class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow-sm hover:bg-primary/90 transition"
            >
                <Plus class="size-4" />
                Add Category
            </button>
        </div>

        <!-- Table Card -->
        <div class="rounded-xl border border-border bg-card shadow-sm overflow-hidden">
            <div v-if="categories.data.length === 0" class="py-16 text-center">
                <Tags class="mx-auto size-12 text-muted-foreground/30 mb-3" />
                <h3 class="text-base font-semibold text-foreground">No Categories Found</h3>
                <p class="text-xs text-muted-foreground max-w-sm mx-auto mt-1 mb-4">
                    Categories group your products and subcategories. Click the button below to add your first category.
                </p>
                <button
                    @click="openCreateModal"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3 py-1.5 text-xs font-medium text-primary-foreground hover:bg-primary/90 transition"
                >
                    <Plus class="size-3.5" /> Add Category
                </button>
            </div>

            <div v-else class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-border bg-muted/40 text-xs uppercase text-muted-foreground">
                        <tr>
                            <th class="py-3 px-4">#</th>
                            <th class="py-3 px-4">Category Name</th>
                            <th class="py-3 px-4">SEO Slug</th>
                            <th class="py-3 px-4">Subcategories</th>
                            <th class="py-3 px-4">Products</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <tr v-for="(cat, idx) in categories.data" :key="cat.id" class="hover:bg-muted/30 transition">
                            <td class="py-3.5 px-4 text-xs text-muted-foreground">
                                {{ (categories.current_page - 1) * categories.per_page + idx + 1 }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-foreground">{{ cat.name }}</span>
                                <p v-if="cat.description" class="text-xs text-muted-foreground line-clamp-1 mt-0.5">
                                    {{ cat.description }}
                                </p>
                            </td>
                            <td class="py-3.5 px-4">
                                <code class="rounded bg-muted px-2 py-0.5 text-xs font-mono text-muted-foreground">
                                    {{ cat.slug }}
                                </code>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-purple-600 dark:text-purple-400 bg-purple-500/10 px-2 py-0.5 rounded-full">
                                    <Layers class="size-3" />
                                    {{ cat.subcategories_count }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full">
                                    <Package class="size-3" />
                                    {{ cat.products_count }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span
                                    v-if="cat.is_active"
                                    class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:text-emerald-400"
                                >
                                    <span class="size-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                                <span
                                    v-else
                                    class="inline-flex items-center gap-1 rounded-full bg-muted px-2.5 py-0.5 text-xs font-medium text-muted-foreground"
                                >
                                    <span class="size-1.5 rounded-full bg-muted-foreground"></span> Inactive
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button
                                        @click="openEditModal(cat)"
                                        class="rounded p-1.5 text-muted-foreground hover:bg-primary/10 hover:text-primary transition"
                                        title="Edit Category"
                                    >
                                        <Pencil class="size-4" />
                                    </button>
                                    <button
                                        @click="confirmDelete(cat)"
                                        class="rounded p-1.5 text-muted-foreground hover:bg-destructive/10 hover:text-destructive transition"
                                        title="Delete Category"
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
            <div v-if="categories.total > categories.per_page" class="border-t border-border p-4 flex items-center justify-between text-xs text-muted-foreground">
                <span>Showing {{ categories.data.length }} of {{ categories.total }} categories</span>
                <div class="flex items-center gap-1">
                    <button
                        v-for="(link, i) in categories.links"
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

        <!-- Create / Edit Modal -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4"
        >
            <div class="w-full max-w-md rounded-2xl border border-border bg-card p-6 shadow-xl animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between pb-4 border-b border-border">
                    <h2 class="text-lg font-semibold text-foreground">
                        {{ isEditing ? 'Edit Category' : 'Create New Category' }}
                    </h2>
                    <button @click="closeModal" class="rounded p-1 text-muted-foreground hover:bg-muted">
                        <X class="size-4" />
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="mt-4 space-y-4">
                    <!-- Category Name -->
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1">
                            Category Name <span class="text-destructive">*</span>
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="e.g. Consumer Electronics"
                            class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground focus:border-primary focus:ring-1 focus:ring-primary outline-none transition"
                            :class="{ 'border-destructive': form.errors.name }"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-xs text-destructive">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1">
                            Description (Optional)
                        </label>
                        <textarea
                            v-model="form.description"
                            rows="3"
                            placeholder="Brief summary of what this category contains..."
                            class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground focus:border-primary focus:ring-1 focus:ring-primary outline-none transition resize-none"
                            :class="{ 'border-destructive': form.errors.description }"
                        ></textarea>
                        <p v-if="form.errors.description" class="mt-1 text-xs text-destructive">
                            {{ form.errors.description }}
                        </p>
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center gap-2">
                        <input
                            id="cat_active"
                            v-model="form.is_active"
                            type="checkbox"
                            class="rounded border-border text-primary focus:ring-primary size-4"
                        />
                        <label for="cat_active" class="text-xs font-medium text-foreground cursor-pointer">
                            Active (Visible across subcategories & products)
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2 pt-4 border-t border-border">
                        <button
                            type="button"
                            @click="closeModal"
                            class="rounded-lg border border-border px-4 py-2 text-xs font-medium text-foreground hover:bg-muted transition"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-4 py-2 text-xs font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50 transition"
                        >
                            <Check class="size-3.5" />
                            {{ isEditing ? 'Save Changes' : 'Create Category' }}
                        </button>
                    </div>
                </form>
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
                        <h3 class="text-base font-bold text-foreground">Delete Category</h3>
                        <p class="text-xs text-muted-foreground">This action cannot be undone.</p>
                    </div>
                </div>

                <div class="rounded-lg bg-destructive/5 border border-destructive/15 p-3 text-xs text-foreground space-y-1 my-3">
                    <p class="font-semibold text-destructive">Data Integrity Notice:</p>
                    <p class="text-muted-foreground">
                        Deleting <strong>{{ categoryToDelete?.name }}</strong> will also cascade and remove its
                        <strong>{{ categoryToDelete?.subcategories_count }} subcategories</strong> and
                        <strong>{{ categoryToDelete?.products_count }} related products</strong> to maintain referential integrity.
                    </p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3">
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
