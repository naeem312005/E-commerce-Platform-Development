<footer class="mt-auto border-t border-slate-200 bg-white py-4 px-6 text-xs text-slate-500">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left">
        <div>
            &copy; {{ date('Y') }} <span class="font-bold text-slate-800">{{ config('app.name', 'E-Commerce Platform') }}</span>. All rights reserved.
            <span class="hidden md:inline">&bull; Developed for <span class="font-semibold text-slate-700">Bangla Puzzle Limited</span></span>
        </div>
        <div class="flex items-center gap-3 text-[11px] text-slate-400 font-mono">
            <span>Laravel v{{ Illuminate\Foundation\Application::VERSION }}</span>
            <span>&bull;</span>
            <span>PHP v{{ PHP_VERSION }}</span>
        </div>
    </div>
</footer>

