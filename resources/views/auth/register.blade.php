@extends('layouts.app')

@section('title', 'Create Account - Chat App')

@section('content')
<div class="min-h-[calc(100vh-80px)] flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        
        {{-- Header Section --}}
        <div class="text-center">
            {{-- Chat App Registration Icon (Changed to Multi-user Chat Icon) --}}
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-600 shadow-lg shadow-blue-200 mb-4 animate-bounce">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-black text-slate-900 uppercase tracking-tight">Get Started</h2>
            <p class="mt-2 text-sm text-slate-500 font-medium">Create an account today to connect and message your friends instantly.</p>
        </div>

        {{-- Main Register Card --}}
        <div class="bg-white p-8 sm:p-10 rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100">
            
            {{-- Error Alerts --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 rounded-xl border border-red-100">
                    <ul class="list-disc list-inside text-xs text-red-600 font-bold space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Registration Form --}}
            <form class="space-y-5" action="{{ route('register') }}" method="POST">
                @csrf

                {{-- Full Name Field --}}
                <div class="space-y-2">
                    <label for="name" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required 
                            class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-blue-500 transition-all outline-none" 
                            placeholder="John Doe">
                    </div>
                </div>

                {{-- Email Address Field --}}
                <div class="space-y-2">
                    <label for="email" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path>
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required 
                            class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-blue-500 transition-all outline-none" 
                            placeholder="example@mail.com">
                    </div>
                </div>

                {{-- Password Field --}}
                <div class="space-y-2">
                    <label for="password" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" required 
                            class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-blue-500 transition-all outline-none" 
                            placeholder="••••••••">
                    </div>
                </div>

                {{-- Confirm Password Field --}}
                <div class="space-y-2">
                    <label for="password_confirmation" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                            class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-blue-500 transition-all outline-none" 
                            placeholder="••••••••">
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent text-sm font-black rounded-xl text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all active:scale-[0.98]">
                        CREATE ACCOUNT
                    </button>
                </div>
            </form>

            {{-- Footer Sign In Link --}}
            <div class="mt-8 text-center border-t border-slate-100 pt-6">
                <p class="text-sm text-slate-500 font-medium">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-black text-blue-600 hover:text-blue-700 hover:underline decoration-2 underline-offset-4 ml-1 transition-all">
                        Sign In Instead
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection