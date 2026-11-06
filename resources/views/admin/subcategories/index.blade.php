@extends('layouts.app')

@section('title', 'Subcategories Management')
@section('header_title', 'Subcategory Management')

@section('content')
<div class="space-y-6">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Subcategories</h2>
            <p class="text-xs text-slate-500 mt-0.5">Manage subcategories assigned to parent categories with relational integrity.</p>
        </div>
        <button onclick="openCreateModal()" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-sm shadow-indigo-600/30">
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add Subcategory</span>
        </button>
    </div>

    <!-- Table Card -->
    <div class="rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
        @if($subcategories->isEmpty())
            <div class="py-16 text-center">
                <p class="text-sm font-semibold text-slate-700">No subcategories found.</p>
                <p class="text-xs text-slate-400 mt-1 mb-4">Create your first subcategory under an existing category.</p>
                <button onclick="openCreateModal()" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg bg-indigo-600 text-white">
                    + Add Subcategory
                </button>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase">
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
                    <tbody class="divide-y divide-slate-100">
                        @foreach($subcategories as $index => $sub)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3.5 px-4 text-xs text-slate-400 font-mono">
                                    {{ $subcategories->firstItem() + $index }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-slate-900 text-xs block">{{ $sub->name }}</span>
                                    @if($sub->description)
                                        <span class="text-[11px] text-slate-400 line-clamp-1">{{ $sub->description }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-lg">
                                        {{ $sub->category->name ?? 'Unassigned' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <code class="text-[11px] font-mono bg-slate-100 text-slate-600 px-2 py-0.5 rounded">
                                        /{{ $sub->slug }}
                                    </code>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                                        {{ $sub->products_count }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <button
                                        type="button"
                                        onclick="toggleStatus('{{ route('admin.subcategories.toggle-status', $sub->id) }}', this)"
                                        class="group inline-flex items-center gap-2.5 cursor-pointer focus:outline-none"
                                        title="Click to toggle status"
                                    >
                                        <span class="toggle-track relative inline-block h-5 w-9 shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out {{ $sub->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}">
                                            <span class="toggle-thumb pointer-events-none absolute top-[2px] left-[2px] h-4 w-4 rounded-full bg-white shadow-sm transition-transform duration-200 ease-in-out {{ $sub->is_active ? 'translate-x-full' : 'translate-x-0' }}"></span>
                                        </span>
                                        <span class="toggle-label text-xs font-semibold select-none {{ $sub->is_active ? 'text-emerald-700' : 'text-slate-400' }}">
                                            {{ $sub->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </button>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <button
                                            type="button"
                                            onclick='openEditModal(@json($sub))'
                                            class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 p-1"
                                            title="Edit Subcategory"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            type="button"
                                            onclick="openDeleteModal({{ $sub->id }}, '{{ addslashes($sub->name) }}', {{ $sub->products_count }})"
                                            class="text-xs font-semibold text-red-600 hover:text-red-800 p-1"
                                            title="Delete Subcategory"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($subcategories->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $subcategories->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<!-- Create Subcategory Modal -->
<div id="createModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="font-bold text-slate-900 text-base">Add New Subcategory</h3>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form action="{{ route('admin.subcategories.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Parent Category <span class="text-red-500">*</span></label>
                <select name="category_id" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none">
                    <option value="" disabled selected>-- Select Parent Category --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Subcategory Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Mechanical Keyboards" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Description (Optional)</label>
                <textarea name="description" rows="3" placeholder="Subcategory details..." class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none resize-none"></textarea>
            </div>

            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200">
                <div>
                    <label for="create_active" class="text-xs font-bold text-slate-800 block cursor-pointer">Active Status</label>
                    <span class="text-[10px] text-slate-400">Available across products</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="create_active" name="is_active" value="1" checked class="sr-only peer">
                    <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm">Save Subcategory</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Subcategory Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="font-bold text-slate-900 text-base">Edit Subcategory</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form id="editForm" method="POST" class="mt-4 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Parent Category <span class="text-red-500">*</span></label>
                <select id="edit_category_id" name="category_id" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Subcategory Name <span class="text-red-500">*</span></label>
                <input type="text" id="edit_name" name="name" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                <textarea id="edit_description" name="description" rows="3" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none resize-none"></textarea>
            </div>

            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200">
                <div>
                    <label for="edit_active" class="text-xs font-bold text-slate-800 block cursor-pointer">Active Status</label>
                    <span class="text-[10px] text-slate-400">Visible to customer catalog</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="edit_active" name="is_active" value="1" class="sr-only peer">
                    <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm">Update Subcategory</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-red-200">
        <div class="flex items-center gap-3 text-red-600 mb-3">
            <div class="rounded-full bg-red-100 p-2.5">
                <svg class="size-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 text-base">Delete Subcategory</h3>
                <p class="text-xs text-slate-500">This action cannot be undone.</p>
            </div>
        </div>

        <div class="rounded-xl bg-red-50 border border-red-100 p-3 text-xs text-slate-700 my-3 space-y-1">
            <p class="font-bold text-red-700">Data Integrity Notice:</p>
            <p>Deleting <strong id="delete_sub_name"></strong> will also cascade delete all <strong id="delete_prod_count"></strong> products assigned to it.</p>
        </div>

        <form id="deleteForm" method="POST" class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">Cancel</button>
            <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 text-xs font-bold text-white hover:bg-red-700">Confirm Delete</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    function openEditModal(subcat) {
        document.getElementById('editForm').action = '/admin/subcategories/' + subcat.id;
        document.getElementById('edit_category_id').value = subcat.category_id;
        document.getElementById('edit_name').value = subcat.name;
        document.getElementById('edit_description').value = subcat.description || '';
        document.getElementById('edit_active').checked = Boolean(subcat.is_active);
        document.getElementById('editModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function openDeleteModal(id, name, prodCount) {
        document.getElementById('deleteForm').action = '/admin/subcategories/' + id;
        document.getElementById('delete_sub_name').innerText = name;
        document.getElementById('delete_prod_count').innerText = prodCount;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
