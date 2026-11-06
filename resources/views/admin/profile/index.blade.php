@extends('layouts.app')

@section('title', 'Admin Profile Settings')
@section('header_title', 'Admin Profile Settings')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header Summary Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 md:p-8 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="relative shrink-0">
                    <img
                        id="header_avatar_preview"
                        src="{{ $user->avatar_url }}"
                        alt="{{ $user->name }}"
                        class="size-20 md:size-24 rounded-2xl object-cover border-2 border-indigo-600 shadow-sm bg-slate-100"
                    >
                    <label for="avatar_input" class="absolute -bottom-2 -right-2 p-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-md cursor-pointer transition" title="Change Avatar">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </label>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight truncate">{{ $user->name }}</h2>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                            <span class="size-1.5 rounded-full bg-indigo-500"></span>
                            Administrator
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $user->email }}</p>
                    <p class="text-[11px] text-slate-400 mt-2 flex items-center gap-1.5">
                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Member since {{ $user->created_at?->format('F d, Y') ?? 'N/A' }}</span>
                    </p>
                </div>
            </div>

            <!-- Public Store Link -->
            <a href="{{ route('catalog.public') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 transition shadow-xs self-start sm:self-auto">
                <svg class="size-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>View Storefront</span>
            </a>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex items-center gap-3 border-t border-slate-100 mt-6 pt-4">
            <button
                type="button"
                onclick="switchTab('profileTab', this)"
                class="tab-btn inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition bg-indigo-600 text-white shadow-xs"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Edit Profile</span>
            </button>
            <button
                type="button"
                onclick="switchTab('passwordTab', this)"
                class="tab-btn inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition text-slate-600 hover:bg-slate-100"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Update Password</span>
            </button>
        </div>
    </div>

    <!-- Tab 1: Edit Profile -->
    <div id="profileTab" class="tab-content rounded-3xl border border-slate-200/80 bg-white p-6 md:p-8 shadow-xs">
        <div class="pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-base font-bold text-slate-900">Personal Information</h3>
            <p class="text-xs text-slate-500 mt-0.5">Update your administrator display name, contact email, and profile avatar.</p>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Avatar Upload Row -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700">Profile Photo</label>
                <div class="flex items-center gap-4">
                    <img
                        id="form_avatar_preview"
                        src="{{ $user->avatar_url }}"
                        alt="Preview"
                        class="size-16 rounded-2xl object-cover border border-slate-200 bg-slate-100"
                    >
                    <div class="flex-1">
                        <input
                            type="file"
                            id="avatar_input"
                            name="avatar"
                            accept="image/*"
                            onchange="previewAvatar(event)"
                            class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer"
                        >
                        <p class="text-[10px] text-slate-400 mt-1">PNG, JPG, WEBP up to 2MB. Leave empty to keep current avatar.</p>
                    </div>
                </div>
                @error('avatar')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name -->
            <div class="space-y-1">
                <label for="name" class="block text-xs font-bold text-slate-700">Administrator Name <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    placeholder="e.g. Md Naimul Hasan"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"
                >
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="space-y-1">
                <label for="email" class="block text-xs font-bold text-slate-700">Email Address <span class="text-red-500">*</span></label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    placeholder="admin@example.com"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"
                >
                @error('email')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-sm shadow-indigo-600/30"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Save Profile Changes</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Tab 2: Update Password -->
    <div id="passwordTab" class="tab-content hidden rounded-3xl border border-slate-200/80 bg-white p-6 md:p-8 shadow-xs">
        <div class="pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-base font-bold text-slate-900">Security & Password</h3>
            <p class="text-xs text-slate-500 mt-0.5">Ensure your account is using a strong, random password to stay secure.</p>
        </div>

        <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div class="space-y-1">
                <label for="current_password" class="block text-xs font-bold text-slate-700">Current Password <span class="text-red-500">*</span></label>
                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    required
                    placeholder="Enter your current password"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"
                >
                @error('current_password')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div class="space-y-1">
                <label for="password" class="block text-xs font-bold text-slate-700">New Password <span class="text-red-500">*</span></label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    placeholder="Minimum 8 characters"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"
                >
                @error('password')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="space-y-1">
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700">Confirm New Password <span class="text-red-500">*</span></label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    placeholder="Re-type new password"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"
                >
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-sm shadow-indigo-600/30"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Update Password</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function switchTab(tabId, btn) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.getElementById(tabId).classList.remove('hidden');

        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('bg-indigo-600', 'text-white', 'shadow-xs');
            b.classList.add('text-slate-600', 'hover:bg-slate-100');
        });

        btn.classList.add('bg-indigo-600', 'text-white', 'shadow-xs');
        btn.classList.remove('text-slate-600', 'hover:bg-slate-100');
    }

    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const headerPreview = document.getElementById('header_avatar_preview');
                const formPreview = document.getElementById('form_avatar_preview');
                if (headerPreview) headerPreview.src = e.target.result;
                if (formPreview) formPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
@endsection
