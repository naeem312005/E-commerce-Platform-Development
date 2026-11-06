<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'E-Commerce Platform') }} - @yield('title', 'Admin Panel')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (via CDN for instant zero-config rendering + Vite) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css'])
    @endif

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* SweetAlert2 Custom Styling */
        .swal2-popup {
            border-radius: 1.25rem !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        .swal2-title {
            font-size: 1.15rem !important;
            font-weight: 700 !important;
            color: #0f172a !important;
        }
        .swal2-html-container {
            font-size: 0.8125rem !important;
            color: #475569 !important;
        }

        /* Colorful SweetAlert2 Toasts */
        .swal2-colored-toast {
            border-radius: 1rem !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25), 0 8px 10px -6px rgba(0, 0, 0, 0.15) !important;
            padding: 0.85rem 1.25rem !important;
            font-weight: 600 !important;
        }
        .swal2-colored-toast .swal2-title {
            color: #ffffff !important;
            font-size: 0.875rem !important;
            font-weight: 700 !important;
            line-height: 1.35 !important;
        }
        .swal2-colored-toast .swal2-timer-progress-bar {
            background: rgba(255, 255, 255, 0.75) !important;
            height: 3px !important;
        }
        .swal2-colored-toast .swal2-icon {
            margin: 0 0.75rem 0 0 !important;
        }
        .swal2-colored-toast .swal2-icon.swal2-icon-show {
            border-color: rgba(255, 255, 255, 0.85) !important;
        }

        .switch-track {
            position: relative;
            display: inline-block;
            width: 38px !important;
            height: 22px !important;
            border-radius: 9999px;
            transition: background-color 0.2s ease-in-out;
            cursor: pointer;
            flex-shrink: 0;
            vertical-align: middle;
        }
        .switch-track.active {
            background-color: #10b981 !important; /* emerald-500 */
        }
        .switch-track.inactive {
            background-color: #cbd5e1 !important; /* slate-300 */
        }
        .switch-thumb {
            position: absolute;
            top: 3px !important;
            left: 3px !important;
            width: 16px !important;
            height: 16px !important;
            border-radius: 9999px;
            background-color: #ffffff !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.15), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease-in-out;
            pointer-events: none;
        }
        .switch-track.active .switch-thumb {
            transform: translateX(16px) !important;
        }
        .switch-track.inactive .switch-thumb {
            transform: translateX(0) !important;
        }
    </style>
</head>
<body class="h-full antialiased text-slate-800">
    <div class="min-h-full flex flex-col lg:flex-row">
        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="w-full lg:w-64 bg-slate-900 text-white shrink-0 flex flex-col justify-between border-r border-slate-800 shadow-xl transition-all">
            <div>
                <!-- Brand Header -->
                <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800/80 bg-slate-950/40">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 font-extrabold text-base tracking-tight text-white hover:text-indigo-400 transition">
                        <span class="size-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-600/30">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </span>
                        <span>ShopAdmin</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1 text-sm font-medium">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/40 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Dashboard</span>
                    </a>

                    <div class="pt-4 pb-1 px-3 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        Catalog Management
                    </div>

                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/40 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <span>Categories</span>
                    </a>

                    <a href="{{ route('admin.subcategories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.subcategories.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/40 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Subcategories</span>
                    </a>

                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/40 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Products</span>
                    </a>

                    <div class="pt-4 pb-1 px-3 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        Administration
                    </div>

                    <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.profile.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/40 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Profile Settings</span>
                    </a>

                    <div class="pt-4 pb-1 px-3 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        Public Store
                    </div>

                    <a href="{{ route('catalog.public') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('catalog.*') || request()->routeIs('home') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/40 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Product Showcase</span>
                    </a>
                </nav>
            </div>

            <!-- User Info & Logout -->
            <div class="p-4 border-t border-slate-800/80 bg-slate-950/40">
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-2.5 min-w-0 group cursor-pointer hover:opacity-90 transition" title="Edit Profile">
                        <img src="{{ auth()->user()?->avatar_url ?? 'https://ui-avatars.com/api/?name=Admin' }}" alt="Avatar" class="size-8 rounded-full object-cover border border-indigo-500/40 shrink-0">
                        <div class="truncate text-xs">
                            <p class="font-semibold text-white truncate group-hover:text-indigo-400 transition">{{ auth()->user()->name ?? 'Administrator' }}</p>
                            <p class="text-slate-400 text-[10px] truncate">{{ auth()->user()->email ?? 'admin@banglapuzzle.com' }}</p>
                        </div>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                        @csrf
                        <button type="submit" title="Logout" class="p-1.5 rounded-lg text-slate-400 hover:text-red-400 hover:bg-red-500/10 transition">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Topbar Header -->
            <header class="h-16 bg-white border-b border-slate-200/80 px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <div class="flex items-center gap-3">
                    <h2 class="text-lg font-bold text-slate-800">
                        @yield('header_title', 'Overview')
                    </h2>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('catalog.public') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 transition shadow-xs">
                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>Live</span>
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-sm shadow-indigo-600/30">
                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Product</span>
                    </a>
                    <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-2 p-1 pl-2 pr-3 text-xs font-semibold rounded-lg border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 transition shadow-xs" title="Profile Settings">
                        <img src="{{ auth()->user()?->avatar_url ?? 'https://ui-avatars.com/api/?name=Admin' }}" alt="Profile" class="size-6 rounded-full object-cover">
                        <span class="hidden md:inline">{{ auth()->user()?->name ?? 'Admin' }}</span>
                    </a>
                </div>
            </header>

            <!-- Alerts Banner -->
            <div class="px-6 pt-4">
                @if(session('success'))
                    <div class="flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 p-3.5 text-sm text-emerald-800 shadow-xs">
                        <svg class="size-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="flex items-center gap-3 rounded-xl bg-red-50 border border-red-200 p-3.5 text-sm text-red-800 shadow-xs">
                        <svg class="size-5 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="rounded-xl bg-red-50 border border-red-200 p-3.5 text-sm text-red-800 shadow-xs">
                        <div class="flex items-center gap-2 font-bold mb-1">
                            <svg class="size-5 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span>Please fix the following validation errors:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-0.5 ml-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Page Body -->
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Global SweetAlert2 Colorful Toast configuration
        const Toast = {
            fire: function(opts = {}) {
                let icon = opts.icon || 'success';
                let title = opts.title || '';
                let bg = '#10b981'; // Green for success
                if (icon === 'error') {
                    bg = '#ef4444'; // Red for error
                } else if (icon === 'warning') {
                    bg = '#f59e0b'; // Amber for warning
                } else if (icon === 'info') {
                    bg = '#4f46e5'; // Indigo for info
                }

                return Swal.fire({
                    toast: true,
                    position: opts.position || 'top-end',
                    icon: icon,
                    iconColor: '#ffffff',
                    title: title,
                    text: opts.text || '',
                    showConfirmButton: false,
                    timer: opts.timer || 3500,
                    timerProgressBar: true,
                    color: '#ffffff',
                    background: bg,
                    customClass: {
                        popup: 'swal2-colored-toast'
                    },
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
            },
            success: function(title, text = '') { return this.fire({ icon: 'success', title, text }); },
            error: function(title, text = '') { return this.fire({ icon: 'error', title, text }); },
            warning: function(title, text = '') { return this.fire({ icon: 'warning', title, text }); },
            info: function(title, text = '') { return this.fire({ icon: 'info', title, text }); }
        };
        window.Toast = Toast;

        async function toggleStatus(url, buttonElement, activeText = 'Active', inactiveText = 'Inactive') {
            buttonElement.disabled = true;
            const track = buttonElement.querySelector('.switch-track') || buttonElement.querySelector('.toggle-track');
            const label = buttonElement.querySelector('.switch-label') || buttonElement.querySelector('.toggle-label');

            try {
                const response = await fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    if (data.is_active) {
                        track.classList.remove('inactive', 'bg-slate-300');
                        track.classList.add('active', 'bg-emerald-500');
                        if (label) {
                            label.innerText = activeText;
                            label.classList.remove('text-slate-400');
                            label.classList.add('text-emerald-700');
                        }
                    } else {
                        track.classList.remove('active', 'bg-emerald-500');
                        track.classList.add('inactive', 'bg-slate-300');
                        if (label) {
                            label.innerText = inactiveText;
                            label.classList.remove('text-emerald-700');
                            label.classList.add('text-slate-400');
                        }
                    }

                    Toast.fire({
                        icon: 'success',
                        title: data.message || ('Status updated to ' + (data.is_active ? activeText : inactiveText))
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message || 'Failed to update status.'
                    });
                }
            } catch (error) {
                console.error('Toggle error:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'An error occurred while updating status.'
                });
            } finally {
                buttonElement.disabled = false;
            }
        }
    </script>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toast.fire({
                    icon: 'success',
                    title: "{{ addslashes(session('success')) }}"
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toast.fire({
                    icon: 'error',
                    title: "{{ addslashes(session('error')) }}"
                });
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toast.fire({
                    icon: 'warning',
                    title: "{{ addslashes($errors->first()) }}"
                });
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>

