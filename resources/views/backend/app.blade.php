<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <title>{{ config('app.name', 'E-Commerce Platform') }} - @yield('title', 'Admin Panel')</title>

    {{-- Header Partial: Meta, Fonts, Tailwind, Vite, SweetAlert2 --}}
    @include('backend.partials.header')

    {{-- Theme Partial: Custom CSS, Switch, Toast Styles --}}
    @include('backend.partials.theme')

    <!-- Page Specific Styles -->
    @stack('styles')
</head>
<body class="h-full antialiased text-slate-800">
    <div class="min-h-full flex flex-col lg:flex-row">
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-30 hidden lg:hidden transition-opacity duration-300" aria-hidden="true"></div>

        <!-- Sidebar Navigation Partial -->
        @include('backend.partials.sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar Partial -->
            @include('backend.partials.navbar')

            <!-- Main Page Content Partial -->
            @include('backend.partials.content')

            <!-- Footer Partial -->
            @include('backend.partials.footer')
        </div>
    </div>

    <!-- Scripts Partial: SweetAlert2 Toast, Status Toggle, Flash Listeners -->
    @include('backend.partials.scripts')

    <!-- CKEditor Partial -->
    @include('backend.partials.ckeditor')

    <!-- Page Specific Scripts -->
    @stack('scripts')
</body>
</html>

