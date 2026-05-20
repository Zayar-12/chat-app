@extends('layouts.app') @section('content')
<div class="min-h-[80vh] flex flex-col items-center justify-center px-6">
    <div class="w-full max-w-md bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-2xl p-8 shadow-sm">
        
        <div class="mb-6 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-[#fff2f2] dark:bg-[#1d0002] rounded-full mb-4">
                <svg class="w-8 h-8 text-[#f53003]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Email Verification</h1>
            <p class="mt-3 text-sm text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">
                ကျေးဇူးပြု၍ သင်၏ Email ကို စစ်ဆေးပေးပါ။ ကျွန်ုပ်တို့ ပို့လိုက်သော link ကို နှိပ်ပြီး verification ပြုလုပ်ပေးရန် လိုအပ်ပါသည်။
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/10 border border-green-200 dark:border-green-800/30 rounded-lg">
                <p class="text-sm text-green-600 dark:text-green-400 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Verification link အသစ်ကို ပို့လိုက်ပါပြီ။
                </p>
            </div>
        @endif

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full py-2.5 px-4 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] text-sm font-medium rounded-lg hover:bg-black dark:hover:bg-white transition-all shadow-sm">
                    Verification Email ပြန်ပို့မည်
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] underline underline-offset-4 transition-colors">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection