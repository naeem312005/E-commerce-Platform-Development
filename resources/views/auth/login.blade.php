@extends('layouts.guest')

@section('title', 'Admin Login')

@section('content')
<div class="max-w-md mx-auto px-4 py-8">
    <div class="rounded-3xl border border-slate-200/80 bg-white p-8 shadow-xl">
        <div class="text-center mb-6">
            <span class="inline-flex size-12 rounded-2xl bg-indigo-600 text-white items-center justify-center shadow-lg shadow-indigo-600/30 mb-3">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </span>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Admin Sign In</h2>
            <p class="text-xs text-slate-500 mt-1">Access the product and inventory management system.</p>
        </div>

        <!-- Quick Demo Credentials Hint Box -->
        <div class="rounded-2xl bg-indigo-50/70 border border-indigo-100 p-3.5 text-xs text-slate-700 mb-6 space-y-1">
            <p class="font-bold text-indigo-700">Pre-seeded Credentials:</p>
            <p>Email: <code class="bg-white px-1.5 py-0.5 rounded border border-indigo-200 font-mono text-indigo-900 font-bold">admin@banglapuzzle.com</code></p>
            <p>Password: <code class="bg-white px-1.5 py-0.5 rounded border border-indigo-200 font-mono text-indigo-900 font-bold">password</code></p>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', 'admin@banglapuzzle.com') }}"
                    required
                    autofocus
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Password</label>
                <input
                    type="password"
                    name="password"
                    value="password"
                    required
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none"
                >
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" checked class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 size-4">
                    <span class="text-slate-600 font-medium">Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs tracking-wide transition shadow-md shadow-indigo-600/30 mt-2">
                Sign In to Dashboard
            </button>
        </form>
    </div>
</div>
@endsection
