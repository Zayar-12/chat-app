@props(['conversations', 'chatId', 'search'])

<div class="w-full md:w-[380px] h-full flex flex-col bg-slate-900 border-r border-slate-800/80 shrink-0 select-none">
    <div class="p-4 border-b border-slate-800/60 bg-slate-900">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input 
                wire:model.live="search" 
                type="text" 
                placeholder="Search" 
                class="block w-full pl-10 pr-4 py-2 bg-slate-950 border border-slate-800 text-slate-200 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-slate-500"
            >
        </div>
    </div>

    <div class="px-4 pt-4 flex justify-between items-center select-none">
        <h1 class="text-base font-bold text-slate-200">Chats</h1>
        <button 
            wire:click="$set('modelPopUp', true)"
            class="p-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold rounded-xl transition-all shadow-md active:scale-95 flex items-center gap-1"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
            </svg>
            New Chat
        </button>
    </div>
    
    <div class="flex-1 overflow-y-auto p-2 space-y-0.5 custom-scrollbar">
        @forelse($conversations as $conversation)
            @php
                $chatPartner = $conversation->sender_id === auth()->id() ? $conversation->receiver : $conversation->sender;
                $latestMessage = $conversation->messages()->latest()->first();
                $isActive = $chatId === $conversation->id;
            @endphp

            <button 
                wire:click="setChatId({{ $conversation->id }})" 
                class="w-full text-left flex items-center gap-3 p-3 rounded-xl transition-all group active:scale-[0.99] {{ $isActive ? 'bg-blue-600/15 border-l-4 border-blue-500 rounded-l-none' : 'hover:bg-slate-800/60' }}"
            >
                <div class="relative shrink-0">
                    @if($chatPartner->avatar)
                        <img src="{{ asset('storage/' . $chatPartner->avatar) }}" class="w-12 h-12 rounded-full object-cover border border-slate-800 shadow-md">
                    @else
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-600 to-blue-500 flex items-center justify-center text-white text-base font-black shadow-md">
                            {{ strtoupper(substr($chatPartner->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-semibold {{ $isActive ? 'text-blue-400 font-bold' : 'text-slate-200 group-hover:text-white' }} truncate transition-colors">
                            {{ $chatPartner->name }}
                        </p>
                        @if($conversation->last_message_at)
                            <span class="text-[11px] text-slate-500 shrink-0 font-medium">
                                {{ \Carbon\Carbon::parse($conversation->last_message_at)->format('g:i A') }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400 truncate mt-1 leading-normal">
                        {{ $latestMessage?->body ?? 'No messages yet...' }}
                    </p>
                </div>
            </button>
        @empty
            <div class="text-center py-12 px-4">
                <svg class="w-12 h-12 text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="text-sm text-slate-500 font-medium">No conversations found</p>
            </div>
        @endforelse
    </div>
</div>