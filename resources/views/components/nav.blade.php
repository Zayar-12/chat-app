<nav class="sticky top-0 z-50 w-full bg-slate-950/80 backdrop-blur-md border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('welcome') }}" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 bg-gradient-to-tr from-blue-600 to-amber-400 rounded-xl flex items-center justify-center shadow-lg group-hover:rotate-12 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m8 0h-3m4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                        </svg>
                    </div>
                    <span class="text-white font-black text-xl tracking-tighter uppercase ml-2">
                        The Voice <span class="text-blue-500">MM</span>
                    </span>
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('welcome') }}" class="text-gray-300 hover:text-white font-bold text-sm uppercase tracking-widest transition-colors">
                    Vote Now
                </a>
                </div>

            @if (Route::has('login'))
                <div class="flex items-center gap-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white font-bold text-sm transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-600/20 transition-all active:scale-95">
                            Register
                        </a>
                    @endguest

                    @auth
                        <div class="flex items-center gap-6">
                            <a href="{{ route('profile') }}" class="flex items-center gap-3 group">
                                <div class="text-right hidden sm:block">
                                    <p class="text-xs text-gray-500 font-bold uppercase tracking-tighter">Welcome back,</p>
                                    <p class="text-sm text-white font-black">{{ Auth::user()->name }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-600 to-blue-400 flex items-center justify-center text-white font-bold border-2 border-white/10 group-hover:border-blue-500 transition-colors">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            </a>

                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="Logout">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
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