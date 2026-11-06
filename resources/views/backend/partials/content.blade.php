<main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
    <!-- Inline Alerts (Graceful Fallback alongside SweetAlert2 Toasts) -->
    @if(session('success'))
        <div class="mb-5 rounded-2xl bg-emerald-50 border border-emerald-200/80 p-4 text-sm text-emerald-800 flex items-start gap-3 shadow-xs">
            <svg class="size-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="font-medium">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 rounded-2xl bg-red-50 border border-red-200/80 p-4 text-sm text-red-800 flex items-start gap-3 shadow-xs">
            <svg class="size-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="font-medium">{{ session('error') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 rounded-2xl bg-amber-50 border border-amber-200/80 p-4 text-sm text-amber-800 shadow-xs">
            <div class="flex items-center gap-2 font-bold mb-1 text-amber-900">
                <svg class="size-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>Please correct the errors below:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5 text-amber-700 pl-6">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Dynamic View Content -->
    @yield('content')
</main>

