@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<div class="max-w-md mx-auto px-4 py-8">
    <div class="rounded-3xl border border-slate-200/80 bg-white p-8 shadow-xl">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Create Account</h2>
            <p class="text-xs text-slate-500 mt-1">Register a new administrator user.</p>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Full Name</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    placeholder="Md Naimul Hasan Naeem"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    placeholder="user@example.com"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Password</label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Confirm Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    required
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs text-slate-800 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none"
                >
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs tracking-wide transition shadow-md shadow-indigo-600/30 mt-2">
                Register Account
            </button>
        </form>

        <p class="text-center text-xs text-slate-500 mt-4">
            Already have an account? <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:underline">Sign in</a>
        </p>
    </div>
</div>
@endsection
