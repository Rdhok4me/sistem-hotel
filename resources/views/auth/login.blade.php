@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="w-full max-w-sm px-4">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <h1 class="text-xl font-semibold text-gray-900">{{ config('app.name') }}</h1>
        <p class="text-sm text-gray-500 mt-1">Sistem Manajemen Perhotelan</p>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        @if(session('success'))
            <div class="mb-4">
                <x-alert type="success" :message="session('success')" />
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Email
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    autofocus
                    placeholder="admin@hotel.com"
                    class="w-full px-3.5 py-2.5 text-sm border rounded-lg transition
                           focus:outline-none focus:ring-2 focus:ring-indigo-300
                           {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}"
                >
                @error('email')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Password
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full px-3.5 py-2.5 text-sm border rounded-lg transition
                           focus:outline-none focus:ring-2 focus:ring-indigo-300
                           {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}"
                >
                @error('password')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember me --}}
            <div class="flex items-center mb-5">
                <input id="remember" type="checkbox" name="remember"
                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium
                       py-2.5 px-4 rounded-lg text-sm transition focus:outline-none
                       focus:ring-2 focus:ring-indigo-300 focus:ring-offset-2">
                Masuk
            </button>
        </form>
    </div>

    {{-- Hint akun default (development) --}}
    @if(config('app.debug'))
    <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-700">
        <p class="font-medium mb-1">Akun default (development):</p>
        <p>Admin: admin@hotel.com / password</p>
        <p>Resepsionis: resepsionis@hotel.com / password</p>
    </div>
    @endif

</div>
@endsection
