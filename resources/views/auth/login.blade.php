@extends('layouts.app')

@section('title', 'Sign In - The Voice Myanmar')

@section('content')
<div class="min-h-[calc(100vh-80px)] flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-600 shadow-lg shadow-blue-200 mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-black text-slate-900 uppercase tracking-tight">Sign In</h2>
            <p class="mt-2 text-sm text-slate-500 font-medium">သင်၏ အကောင့်သို့ ဝင်ရောက်ပြီး မဲပေးလိုက်ပါ။</p>
        </div>

        <div class="bg-white p-8 sm:p-10 rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100">
            
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 rounded-xl border border-red-100">
                    <ul class="list-disc list-inside text-xs text-red-600 font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <a href="{{ route('google.redirect') }}" 
                   class="w-full flex items-center justify-center gap-3 py-3.5 px-4 border border-slate-200 text-sm font-bold rounded-xl text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 shadow-sm transition-all active:scale-[0.98]">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.64 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <span>Continue with Google</span>
                </a>
            </div>

            <div class="relative my-6 flex items-center justify-center">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-100"></div>
                </div>
                <span class="relative bg-white px-4 text-xs font-black text-slate-400 uppercase tracking-widest">Or</span>
            </div>

            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf

                <div class="space-y-2">
                    <label for="email" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path></svg>
                        </div>
                        <input id="email" name="email" type="email" required 
                            class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-blue-500 transition-all outline-none" 
                            placeholder="example@mail.com">
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between ml-1">
                        <label for="password" class="text-xs font-black text-slate-400 uppercase tracking-widest">Password</label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" name="password" type="password" required 
                            class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-blue-500 transition-all outline-none" 
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded transition-all cursor-pointer">
                    <label for="remember" class="ml-2 block text-sm text-slate-600 font-medium cursor-pointer">Remember me</label>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-black rounded-xl text-white bg-slate-900 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg transition-all active:scale-[0.98]">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-slate-400 group-hover:text-blue-200 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </span>
                        SIGN IN
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center border-t border-slate-50 pt-6">
                <p class="text-sm text-slate-500 font-medium">
                    အကောင့်မရှိသေးဘူးလား? 
                    <a href="{{ route('register') }}" class="font-black text-blue-600 hover:text-blue-700 hover:underline decoration-2 underline-offset-4 ml-1 transition-all">
                        Register Now
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection