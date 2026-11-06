<header class="bg-white border-b border-slate-200/80 sticky top-0 z-20 shadow-xs">
    <div class="px-4 sm:px-6 py-3.5 flex items-center justify-between gap-4">
        <!-- Left: Mobile Menu Toggle & Page Title -->
        <div class="flex items-center gap-3">
            <button type="button" id="mobile-sidebar-toggle" class="lg:hidden p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 focus:outline-hidden" aria-label="Toggle Sidebar">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div>
                <h1 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                    @yield('header_title', 'Overview Dashboard')
                </h1>
                <p class="text-xs text-slate-500 hidden sm:block">
                    E-Commerce Admin Panel &bull; Bangla Puzzle Assessment
                </p>
            </div>
        </div>

        <!-- Right: Action Buttons & Admin Profile -->
        <div class="flex items-center gap-2 sm:gap-3">
            <!-- View Public Store Button -->
            <a href="{{ route('catalog.public') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition shadow-2xs">
                <svg class="size-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span class="hidden md:inline">Live Store</span>
            </a>

            <!-- Quick Add Product Button -->
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-600 text-xs font-bold text-white hover:bg-indigo-700 transition shadow-2xs shadow-indigo-200">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="hidden sm:inline">Add Product</span>
            </a>

            <!-- Divider -->
            <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

            <!-- Admin Profile Link with Avatar -->
            <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-2 p-1 sm:px-2.5 sm:py-1.5 rounded-xl border border-transparent hover:border-slate-200 hover:bg-slate-50 transition group" title="Profile Settings">
                <img src="{{ auth()->user()?->avatar_url ?? 'https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff' }}" alt="{{ auth()->user()?->name ?? 'Admin' }}" class="size-8 rounded-full object-cover border border-slate-200 shadow-2xs group-hover:scale-105 transition shrink-0">
                <div class="text-left hidden md:block">
                    <div class="text-xs font-bold text-slate-800 leading-tight group-hover:text-indigo-600 transition">
                        {{ auth()->user()?->name ?? 'Admin User' }}
                    </div>
                    <div class="text-[10px] text-slate-400 font-medium leading-none">
                        Super Admin
                    </div>
                </div>
            </a>

            <!-- Logout Icon Button -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition" title="Logout">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</header>

