<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign In | {{ config('app.name', 'E-commerce Platform') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
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
                        primary: '#405189',
                        success: '#0ab39c',
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .auth-one-bg {
            background-image: linear-gradient(to right, #364574, #405189);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 380px;
        }
        .bg-overlay {
            position: absolute;
            height: 100%;
            width: 100%;
            right: 0;
            bottom: 0;
            left: 0;
            top: 0;
            opacity: 0.85;
            background-color: #364574;
        }
        .shape {
            position: absolute;
            bottom: 0;
            right: 0;
            left: 0;
            z-index: 1;
            pointer-events: none;
        }
        .shape svg {
            width: 100%;
            height: auto;
            fill: #f1f5f9;
        }
    </style>
</head>
<body class="min-h-full flex flex-col justify-between relative bg-slate-100 antialiased text-slate-800">

    <!-- Decorative Top Wave Background (Replico / Velzon Style) -->
    <div class="auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>
        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="relative z-10 flex-1 flex flex-col justify-center items-center px-4 sm:px-6 py-12">
        <div class="w-full max-w-md">

            <!-- Logo & Title Header -->
            <div class="text-center mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 font-black text-2xl text-white tracking-tight hover:opacity-90 transition">
                    <span class="size-9 rounded-xl bg-white/10 backdrop-blur-xs flex items-center justify-center text-white border border-white/20 shadow-sm">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </span>
                    <span>Shop<span class="text-emerald-400">Admin</span></span>
                </a>
                <p class="text-xs text-indigo-200 mt-2 font-medium">E-commerce Platform & Management Dashboard</p>
            </div>

            <!-- Card Container -->
            <div class="rounded-2xl border border-slate-200/80 bg-white shadow-xl overflow-hidden">
                <div class="p-6 sm:p-8">
                    <!-- Heading -->
                    <div class="text-center pb-4 border-b border-slate-100">
                        <h4 class="text-lg font-extrabold text-[#405189]">Welcome Back !</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Sign in to continue to E-commerce Dashboard.</p>
                    </div>

                    <!-- Pre-seeded Admin Credentials Pill -->
                    <div class="mt-5 rounded-xl bg-indigo-50/80 border border-indigo-100 p-3.5 text-xs text-slate-700">
                        <div class="flex items-center gap-1.5 font-bold text-indigo-700 mb-1">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Pre-configured Admin Access:</span>
                        </div>
                        <div class="flex flex-col gap-0.5 font-mono text-[11px] text-slate-600 ml-5">
                            <p>Email: <strong class="text-indigo-900">admin@banglapuzzle.com</strong></p>
                            <p>Password: <strong class="text-indigo-900">password</strong></p>
                        </div>
                    </div>

                    <!-- Validation Errors -->
                    @if($errors->any())
                        <div class="mt-4 p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700 flex items-center gap-2">
                            <svg class="size-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <!-- Sign In Form -->
                    <form method="POST" action="{{ route('login') }}" class="mt-5 space-y-4">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email', 'admin@banglapuzzle.com') }}"
                                    required
                                    autofocus
                                    placeholder="Enter email"
                                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs text-slate-800 focus:border-[#405189] focus:ring-1 focus:ring-[#405189] outline-none transition"
                                >
                            </div>
                        </div>

                        <!-- Password with Eye Toggle (replico style) -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    value="password"
                                    required
                                    placeholder="Enter password"
                                    class="w-full rounded-xl border border-slate-300 pl-3.5 pr-10 py-2.5 text-xs text-slate-800 focus:border-[#405189] focus:ring-1 focus:ring-[#405189] outline-none transition"
                                >
                                <button
                                    type="button"
                                    id="password-addon"
                                    onclick="togglePassword()"
                                    class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 transition"
                                    title="Toggle password visibility"
                                >
                                    <!-- Eye Open Icon -->
                                    <svg id="eye-open" class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <!-- Eye Closed Icon -->
                                    <svg id="eye-closed" class="size-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between text-xs pt-1">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remember" id="remember" checked class="rounded border-slate-300 text-[#0ab39c] focus:ring-[#0ab39c] size-4">
                                <span class="text-slate-600 font-medium">Remember me</span>
                            </label>
                            <a href="{{ route('home') }}" class="text-xs font-semibold text-[#405189] hover:underline">
                                Browse Store →
                            </a>
                        </div>

                        <!-- Submit Button (Velzon Green / Success Button) -->
                        <div class="pt-2">
                            <button
                                type="submit"
                                class="w-full py-2.5 rounded-xl bg-[#0ab39c] hover:bg-[#099885] text-white font-bold text-xs tracking-wide shadow-md shadow-emerald-600/25 transition duration-150 flex items-center justify-center gap-2"
                            >
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                <span>Sign In</span>
                            </button>
                        </div>
                    </form>

                    <!-- Notice that Registration is closed for Admin-Only security -->
                    <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                        <p class="text-[11px] text-slate-400 flex items-center justify-center gap-1">
                            <svg class="size-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>Protected Admin Portal • Public registration is disabled</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center text-xs text-slate-500">
                <p>© {{ date('Y') }} {{ config('app.name', 'E-commerce Platform Development') }}.</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Assessment for <strong>Bangla Puzzle Limited</strong> • By <strong>Md Naimul Hasan Naeem</strong></p>
            </div>
        </div>
    </div>

    <!-- Password visibility toggle script -->
    <script>
        function togglePassword() {
            const pwdInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                pwdInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
