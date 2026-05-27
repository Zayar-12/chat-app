@props(['modelPopUp', 'strangerName', 'strangers'])

@if($modelPopUp)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-md bg-black/70 transition-all">
        <div class="relative w-full max-w-2xl bg-slate-900 border border-slate-800/80 rounded-2xl shadow-2xl flex flex-col max-h-[85vh]" wire:click.away="$set('modelPopUp', false)">
            
            <div class="flex items-center justify-between p-5 border-b border-slate-800/60">
                <h3 class="text-base font-bold text-slate-100 flex items-center gap-3">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11.5m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0M12 21a9.003 9.003 0 0 0 8.354-5.646 9.003 9.003 0 0 0-16.708 0A9.003 9.003 0 0 0 12 21z"></path>
                    </svg>
                    Start New Conversation
                </h3>
                <button wire:click="$set('modelPopUp', false)" class="text-slate-400 hover:text-white bg-slate-800/60 hover:bg-slate-800 p-2 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-5 border-b border-slate-800/40 bg-slate-900/50">
                <div class="relative max-w-md"> 
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input 
                        wire:model.live="strangerName" 
                        type="text" 
                        placeholder="Search people by name..." 
                        class="block w-full pl-10 pr-4 py-2.5 bg-slate-950 border border-slate-800 text-slate-200 text-xs rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder-slate-500"
                    >
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-2 min-h-[300px] custom-scrollbar">
                @forelse($strangers as $stranger)
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-800/40 border border-transparent hover:border-slate-800/20 transition-all group">
                        <div class="flex items-center gap-4 min-w-0">
                            @if($stranger->avatar)
                                <img src="{{ asset('storage/' . $stranger->avatar) }}" class="w-11 h-11 rounded-full object-cover shrink-0 border border-slate-800">
                            @else
                                <div class="w-11 h-11 rounded-full bg-slate-800 text-slate-300 flex items-center justify-center text-sm font-black border border-slate-700 shrink-0 uppercase">
                                    {{ substr($stranger->name, 0, 2) }}
                                </div>
                            @endif
                            <div class="truncate">
                                <p class="text-sm font-semibold text-slate-200 group-hover:text-white truncate">{{ $stranger->name }}</p>
                                <p class="text-xs text-slate-500 truncate mt-0.5">{{ $stranger->email }}</p>
                            </div>
                        </div>

                        <button 
                            wire:click="startConversation({{ $stranger->id }})"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl transition-all shadow-md active:scale-95"
                        >
                            Chat
                        </button>
                    </div>
                @empty
                    <div class="text-center py-20 text-slate-500 text-sm">
                        <svg class="w-12 h-12 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                        </svg>
                        No new people found
                    </div>
                @endforelse
            </div>

            @if($strangers->hasPages())
                <div class="p-4 bg-slate-950/40 border-t border-slate-800/60 rounded-b-2xl px-5">
                    {{ $strangers->links(data: ['scrollTo' => false]) }}
                </div>
            @endif
        </div>
    </div>
@endif