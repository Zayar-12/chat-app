<nav class="sticky top-0 z-50 w-full bg-slate-900/90 backdrop-blur-md border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            {{-- Brand Logo Section --}}
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('welcome') }}" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 bg-gradient-to-tr from-blue-600 to-blue-400 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <span class="text-white font-black text-xl tracking-tight uppercase ml-2">
                        Chat<span class="text-blue-500">ify</span>
                    </span>
                </a>
            </div>

            {{-- Application Links --}}
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('welcome') }}" class="text-slate-300 hover:text-white font-bold text-xs uppercase tracking-widest transition-colors flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-ping"></span>
                    Messages
                </a>
            </div>

            {{-- Authentication Status Section --}}
            @if (Route::has('login'))
                <div class="flex items-center gap-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-slate-300 hover:text-white font-bold text-sm transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-600/20 transition-all active:scale-95">
                            Register
                        </a>
                    @endguest

                    @auth
                        <div class="flex items-center gap-6">
                            {{-- Profile Link --}}
                            <a href="{{ route('profile') }}" class="flex items-center gap-3 group">
                                <div class="text-right hidden sm:block">
                                    <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest">Active Account</p>
                                    <p class="text-sm text-white font-black tracking-tight">{{ Auth::user()->name }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-600 to-blue-400 flex items-center justify-center text-white font-black border-2 border-slate-700 group-hover:border-blue-500 transition-colors shadow-inner">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </a>

                            {{-- Logout Button --}}
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-400 transition-colors" title="Logout">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            @endif

        </div>
    </div>
</nav>