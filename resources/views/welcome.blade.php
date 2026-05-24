<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }} - Connect Instantly</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
               
                @layer theme{:root,:host{--font-sans:'Instrument Sans',ui-sans-serif,system-ui,sans-serif;--color-slate-950:oklch(.129 .042 264.695);--color-slate-900:oklch(.208 .042 265.755);--color-slate-800:oklch(.279 .041 260.031);--color-slate-400:oklch(.704 .04 256.788);--color-slate-100:oklch(.968 .007 247.896);--color-blue-600:oklch(.546 .245 262.881);--color-blue-500:oklch(.623 .214 259.815);--color-purple-600:oklch(.558 .288 302.321)}}
            </style>
        @endif
    </head>
    <body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between antialiased font-sans selection:bg-blue-500 selection:text-white overflow-x-hidden">
        
       
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[500px] pointer-events-none opacity-40 select-none">
            <div class="absolute -top-40 left-10 w-[400px] h-[400px] bg-blue-600 rounded-full blur-[120px]"></div>
            <div class="absolute -top-40 right-10 w-[400px] h-[400px] bg-purple-600 rounded-full blur-[120px]"></div>
        </div>

        
        <header class="w-full max-w-6xl mx-auto px-6 py-5 flex items-center justify-between relative z-10">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-purple-600 flex items-center justify-center shadow-lg shadow-blue-600/30">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"></path>
                    </svg>
                </div>
                <span class="text-lg font-bold tracking-wider bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-300">
                  ZayChat
                </span>
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-200 rounded-xl text-sm font-medium transition-all">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-slate-400 hover:text-slate-100 text-sm font-medium transition-colors">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-semibold transition-all shadow-md shadow-blue-600/20 active:scale-95">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

    
        <main class="w-full max-w-4xl mx-auto px-6 py-16 flex flex-col items-center text-center relative z-10 my-auto">
            
          
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-xs text-blue-400 font-medium mb-6 animate-pulse">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                Next-Gen Real-time Messaging
            </div>

         
            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-white mb-6 leading-[1.15]">
                Connect with anyone,<br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-indigo-400 to-purple-400">
                    anywhere, instantly.
                </span>
            </h1>

           
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full justify-center">
                @auth
                    <a href="{{ url('/home') }}" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/20 transition-all text-base flex items-center justify-center gap-2 group">
                        Enter Chat Rooms
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/20 transition-all text-base flex items-center justify-center gap-2 group">
                        Get Started Free
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                        </svg>
                    </a>
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-slate-900 hover:bg-slate-800/80 border border-slate-800 hover:border-slate-700 text-slate-200 font-semibold rounded-2xl transition-all text-base">
                        Sign In
                    </a>
                @endauth
            </div>

           
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 w-full max-w-3xl mt-20 pt-10 border-t border-slate-900/60 text-left">
                <div class="p-2">
                    <h3 class="text-white font-semibold text-sm mb-1">⚡ Real-time</h3>
                    <p class="text-xs text-slate-500">Instant delivery with WebSockets technology.</p>
                </div>
                <div class="p-2">
                    <h3 class="text-white font-semibold text-sm mb-1">📁 Rich Media</h3>
                    <p class="text-xs text-slate-500">Share pictures, videos and docs flawlessly.</p>
                </div>
                <div class="p-2 col-span-2 sm:col-span-1">
                    <h3 class="text-white font-semibold text-sm mb-1">🛡️ Secure Room</h3>
                    <p class="text-xs text-slate-500">Full control over your conversation history.</p>
                </div>
            </div>

        </main>

     
        <footer class="w-full max-w-6xl mx-auto px-6 py-6 text-center text-xs text-slate-600 relative z-10">
            <p>&copy; {{ date('Y') }} ZayChat. All rights reserved.</p>
        </footer>

    </body>
</html>