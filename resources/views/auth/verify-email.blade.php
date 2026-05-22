@extends('layouts.app')

@section('title', 'Verify Email - Chat App')

@section('content')
<div class="min-h-[calc(100vh-80px)] flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        
        {{-- Main Card --}}
        <div class="bg-white p-8 sm:p-10 rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100">
            
            {{-- Header Section --}}
            <div class="text-center mb-8">
                {{-- Animated Verification Mail Icon --}}
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 shadow-sm mb-4">
                    <svg class="w-8 h-8 animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 19v-8.93a2 2 0 01.89-1.664l8-5.333a2 2 0 012.22 0l8 5.333A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-black text-slate-900 uppercase tracking-tight">Verify Email</h2>
                <p class="mt-3 text-sm text-slate-500 font-medium leading-relaxed">
                    Thanks for signing up! Please check your inbox and click on the verification link we just emailed you to activate your chat account.
                </p>
            </div>

            {{-- Success Alert (Resent Link) --}}
            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 p-4 bg-emerald-50 rounded-xl border border-emerald-100 flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-xs text-emerald-700 font-bold">
                        A new verification link has been sent to your registered email address.
                    </p>
                </div>
            @endif

            {{-- Actions --}}
            <div class="space-y-4">
                {{-- Resend Form --}}
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" 
                        class="w-full flex justify-center py-4 px-4 border border-transparent text-sm font-black rounded-xl text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all active:scale-[0.98]">
                        RESEND VERIFICATION EMAIL
                    </button>
                </form>

                {{-- Logout Link --}}
                <form method="POST" action="{{ route('logout') }}" class="text-center pt-2">
                    @csrf
                    <button type="submit" 
                        class="text-xs font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">
                        Log Out
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection