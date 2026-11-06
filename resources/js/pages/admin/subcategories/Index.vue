<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    Plus,
    Pencil,
    Trash2,
    Layers,
    X,
    Check,
    AlertTriangle,
    Tags,
    Package,
} from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface CategoryOption {
    id: number;
    name: string;
}

interface Subcategory {
    id: number;
    category_id: number;
    name: string;
    slug: string;
    description: string | null;
    is_active: boolean;
    products_count: number;
    created_at: string;
    category?: CategoryOption;
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
    subcategories: Pagination<Subcategory>;
    categories: CategoryOption[];
}>();

defineOptions({
    layout: AppLayout,
});

// Modal state
const isModalOpen = ref(false);
const isEditing = ref(false);
const editingSubcategory = ref<Subcategory | null>(null);

// Delete state
const isDeleteModalOpen = ref(false);
const subcategoryToDelete = ref<Subcategory | null>(null);

// Form
const form = useForm({
    category_id: '' as string | number,
    name: '',
    description: '',
    is_active: true,
});

const openCreateModal = () => {
    isEditing.value = false;
    editingSubcategory.value = null;
    form.reset();
    form.clearErrors();
    form.category_id = props.categories.length > 0 ? props.categories[0].id : '';
    form.is_active = true;
    isModalOpen.value = true;
};

const openEditModal = (subcat: Subcategory) => {
    isEditing.value = true;
    editingSubcategory.value = subcat;
    form.clearErrors();
    form.category_id = subcat.category_id;
    form.name = subcat.name;
    form.description = subcat.description || '';
    form.is_active = Boolean(subcat.is_active);
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    form.clearErrors();
};

const submitForm = () => {
    if (isEditing.value && editingSubcategory.value) {
        form.put(`/admin/subcategories/${editingSubcategory.value.id}`, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/admin/subcategories', {
            onSuccess: () => closeModal(),
        });
    }
};

const confirmDelete = (subcat: Subcategory) => {
    subcategoryToDelete.value = subcat;
    isDeleteModalOpen.value = true;
};

const executeDelete = () => {
    if (!subcategoryToDelete.value) return;
    router.delete(`/admin/subcategories/${subcategoryToDelete.value.id}`, {
        onSuccess: () => {
            isDeleteModalOpen.value = false;
            subcategoryToDelete.value = null;
        },
    });
};
</script>

<template>
    <Head title="Subcategory Management" />

    <div class="flex flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground flex items-center gap-2">
                    <Layers class="size-6 text-purple-600 dark:text-purple-400" />
                    Subcategories Management
                </h1>
                <p class="text-sm text-muted-foreground mt-0.5">
                    Create and maintain subcategories under designated parent categories with relational integrity.
                </p>
            </div>
            <button
                @click="openCreateModal"
                class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow-sm hover:bg-primary/90 transition"
            >
                <Plus class="size-4" />
                Add Subcategory
            </button>
        </div>

        <!-- Table Card -->
        <div class="rounded-xl border border-border bg-card shadow-sm overflow-hidden">
            <div v-if="subcategories.data.length === 0" class="py-16 text-center">
                <Layers class="mx-auto size-12 text-muted-foreground/30 mb-3" />
                <h3 class="text-base font-semibold text-foreground">No Subcategories Found</h3>
                <p class="text-xs text-muted-foreground max-w-sm mx-auto mt-1 mb-4">
                    Subcategories allow deeper organization of products under a parent category.
                </p>
                <button
                    @click="openCreateModal"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3 py-1.5 text-xs font-medium text-primary-foreground hover:bg-primary/90 transition"
                >
                    <Plus class="size-3.5" /> Add Subcategory
                </button>
            </div>

            <div v-else class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-border bg-muted/40 text-xs uppercase text-muted-foreground">
                        <tr>
                            <th class="py-3 px-4">#</th>
                            <th class="py-3 px-4">Subcategory</th>
                            <th class="py-3 px-4">Parent Category</th>
                            <th class="py-3 px-4">SEO Slug</th>
                            <th class="py-3 px-4">Products</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <tr v-for="(sub, idx) in subcategories.data" :key="sub.id" class="hover:bg-muted/30 transition">
                            <td class="py-3.5 px-4 text-xs text-muted-foreground">
                                {{ (subcategories.current_page - 1) * subcategories.per_page + idx + 1 }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-foreground">{{ sub.name }}</span>
                                <p v-if="sub.description" class="text-xs text-muted-foreground line-clamp-1 mt-0.5">
                                    {{ sub.description }}
                                </p>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 rounded-md bg-blue-500/10 px-2 py-1 text-xs font-medium text-blue-600 dark:text-blue-400">
                                    <Tags class="size-3" />
                                    {{ sub.category?.name || 'Unassigned' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <code class="rounded bg-muted px-2 py-0.5 text-xs font-mono text-muted-foreground">
                                    {{ sub.slug }}
                                </code>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full">
                                    <Package class="size-3" />
                                    {{ sub.products_count }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span
                                    v-if="sub.is_active"
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
                                        @click="openEditModal(sub)"
                                        class="rounded p-1.5 text-muted-foreground hover:bg-primary/10 hover:text-primary transition"
                                        title="Edit Subcategory"
                                    >
                                        <Pencil class="size-4" />
                                    </button>
                                    <button
                                        @click="confirmDelete(sub)"
                                        class="rounded p-1.5 text-muted-foreground hover:bg-destructive/10 hover:text-destructive transition"
                                        title="Delete Subcategory"
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
            <div v-if="subcategories.total > subcategories.per_page" class="border-t border-border p-4 flex items-center justify-between text-xs text-muted-foreground">
                <span>Showing {{ subcategories.data.length }} of {{ subcategories.total }} subcategories</span>
                <div class="flex items-center gap-1">
                    <button
                        v-for="(link, i) in subcategories.links"
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
                        {{ isEditing ? 'Edit Subcategory' : 'Create New Subcategory' }}
                    </h2>
                    <button @click="closeModal" class="rounded p-1 text-muted-foreground hover:bg-muted">
                        <X class="size-4" />
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="mt-4 space-y-4">
                    <!-- Parent Category -->
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1">
                            Parent Category <span class="text-destructive">*</span>
                        </label>
                        <select
                            v-model="form.category_id"
                            class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground focus:border-primary focus:ring-1 focus:ring-primary outline-none transition"
                            :class="{ 'border-destructive': form.errors.category_id }"
                        >
                            <option value="" disabled>-- Select Category --</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                {{ cat.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.category_id" class="mt-1 text-xs text-destructive">
                            {{ form.errors.category_id }}
                        </p>
                    </div>

                    <!-- Subcategory Name -->
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1">
                            Subcategory Name <span class="text-destructive">*</span>
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="e.g. Smart Watches"
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
                            placeholder="Brief description of products in this subcategory..."
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
                            id="sub_active"
                            v-model="form.is_active"
                            type="checkbox"
                            class="rounded border-border text-primary focus:ring-primary size-4"
                        />
                        <label for="sub_active" class="text-xs font-medium text-foreground cursor-pointer">
                            Active (Available for product assignment)
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
                            {{ isEditing ? 'Save Changes' : 'Create Subcategory' }}
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
                        <h3 class="text-base font-bold text-foreground">Delete Subcategory</h3>
                        <p class="text-xs text-muted-foreground">This action cannot be undone.</p>
                    </div>
                </div>

                <div class="rounded-lg bg-destructive/5 border border-destructive/15 p-3 text-xs text-foreground space-y-1 my-3">
                    <p class="font-semibold text-destructive">Data Integrity Notice:</p>
                    <p class="text-muted-foreground">
                        Deleting <strong>{{ subcategoryToDelete?.name }}</strong> will also delete all
                        <strong>{{ subcategoryToDelete?.products_count }} related products</strong> under it to preserve relational integrity.
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
