<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'E-Commerce Platform') }} - @yield('title', 'Shop')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (via CDN + Vite) -->
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
    </style>
</head>
<body class="h-full antialiased text-slate-800 flex flex-col justify-between">
    <div>
        <!-- Public Navigation Bar -->
        <header class="bg-white border-b border-slate-200/80 sticky top-0 z-40 shadow-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <!-- Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-black text-lg text-slate-900 tracking-tight hover:text-indigo-600 transition">
                    <span class="size-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-600/30">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </span>
                    <span>BanglaPuzzle<span class="text-indigo-600">Store</span></span>
                </a>

                <!-- Right Menu -->
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-sm shadow-indigo-600/30">
                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span>Admin Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition shadow-xs">
                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            <span>Admin Login</span>
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="py-8">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>© {{ date('Y') }} Bangla Puzzle Store</p>
        </div>
    </footer>

    <script>
        const Toast = {
            fire: function(opts = {}) {
                let icon = opts.icon || 'success';
                let title = opts.title || '';
                let bg = '#10b981';
                if (icon === 'error') bg = '#ef4444';
                else if (icon === 'warning') bg = '#f59e0b';
                else if (icon === 'info') bg = '#4f46e5';

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

    @stack('scripts')
</body>
</html>

