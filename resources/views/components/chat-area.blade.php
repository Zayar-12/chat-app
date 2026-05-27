@props(['chatId', 'activePartner', 'activeMessages', 'perpage', 'attachment', 'message'])

<div class="flex-1 h-full flex flex-col bg-slate-950 relative overflow-hidden">
    @if($chatId && $activePartner)
        <div class="h-[69px] px-6 border-b border-slate-800/60 bg-slate-900/50 backdrop-blur flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="relative">
                    @if($activePartner->avatar)
                        <img src="{{ asset('storage/' . $activePartner->avatar) }}" class="w-10 h-10 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-bold">
                            {{ strtoupper(substr($activePartner->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-100">{{ $activePartner->name }}</h2>
                    <span class="text-[11px] text-green-400 flex items-center gap-1 font-medium">
                        <span class="h-1.5 w-1.5 rounded-full bg-green-500 inline-block"></span> online
                    </span>
                </div>
            </div>

            <div>
                <button 
                    type="button"
                    wire:click="deleteChat({{ $chatId }})"
                    wire:confirm="Are you sure you want to delete this entire chat? This cannot be undone."
                    class="p-2 bg-slate-800 hover:bg-red-950/40 text-slate-400 hover:text-red-400 rounded-xl transition-all border border-slate-800 hover:border-red-900/50 active:scale-95 flex items-center justify-center cursor-pointer group"
                    title="Delete Entire Chat"
                >
                    <svg class="w-4 h-4 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex-1 min-h-0 overflow-y-auto p-6 space-y-3 custom-scrollbar bg-slate-950 flex flex-col">
            @if(count($activeMessages) >= $perpage)
                <div class="flex justify-center mb-4 shrink-0">
                    <button 
                        wire:click="loadMore" 
                        class="text-xs bg-slate-900/80 hover:bg-slate-800 text-blue-400 px-4 py-2 rounded-full transition-all border border-slate-800/80 shadow-md flex items-center gap-1.5 active:scale-95"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"></path>
                        </svg>
                        Load older messages
                    </button>
                </div>
            @endif

            <div class="space-y-3 flex-1">
                @forelse($activeMessages as $msg)
                    @php $isMe = $msg->user_id === auth()->id(); @endphp
                    
                    <div class="flex items-center gap-2 group {{ $isMe ? 'justify-end flex-row' : 'justify-start' }}">
                        @if($isMe)
                            <button 
                                wire:click="deleteMessage({{ $msg->id }})" 
                                wire:confirm="Are you sure you want to delete this message?"
                                class="opacity-0 group-hover:opacity-100 text-slate-500 hover:text-red-400 p-1.5 rounded-full hover:bg-slate-800/60 transition-all duration-200 cursor-pointer shrink-0"
                                title="Delete Message"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                                </svg>
                            </button>
                        @endif

                        <div class="max-w-[65%] rounded-2xl px-4 py-2.5 text-sm shadow-md transition-all {{ $isMe ? 'bg-blue-600 text-white rounded-br-none' : 'bg-slate-800 text-slate-100 rounded-bl-none' }}">
                            @if($msg->file_path)
                                <div class="mb-2 max-w-full overflow-hidden rounded-lg">
                                    @if($msg->file_type === 'image')
                                        <a href="{{ asset('storage/' . $msg->file_path) }}" target="_blank" class="block hover:opacity-90 transition-opacity">
                                            <img src="{{ asset('storage/' . $msg->file_path) }}" class="max-h-60 w-auto object-cover rounded-lg shadow-sm border border-black/10">
                                        </a>
                                    @elseif($msg->file_type === 'video')
                                        <video controls class="max-h-60 w-full rounded-lg shadow-sm border border-black/10">
                                            <source src="{{ asset('storage/' . $msg->file_path) }}" type="video/{{ pathinfo($msg->file_path, PATHINFO_EXTENSION) }}">
                                        </video>
                                    @else
                                        <a href="{{ asset('storage/' . $msg->file_path) }}" download class="flex items-center gap-2 p-2.5 rounded-xl border transition-colors text-xs font-medium {{ $isMe ? 'bg-blue-700/60 hover:bg-blue-700/90 border-blue-500/30 text-blue-100' : 'bg-slate-900/60 hover:bg-slate-900/90 border-slate-700/40 text-blue-400' }}">
                                            <svg class="w-5 h-5 shrink-0 {{ $isMe ? 'text-blue-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                                            </svg>
                                            <span class="truncate max-w-[150px]">{{ basename($msg->file_path) }}</span>
                                        </a>
                                    @endif
                                </div>
                            @endif

                            @if(!empty(trim($msg->body)))
                                <p class="leading-relaxed break-words font-medium">{{ $msg->body }}</p>
                            @endif

                            <span class="block text-[10px] text-right mt-1 font-mono {{ $isMe ? 'text-blue-200' : 'text-slate-400' }}">
                                {{ $msg->created_at->format('g:i A') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex items-center justify-center text-slate-500 text-sm">
                        Say hello to start the conversation!
                    </div>
                @endforelse
            </div>
        </div>

        <div class="p-4 bg-slate-900/40 border-t border-slate-800/60 shrink-0">
            @if ($attachment)
                <div class="max-w-4xl mx-auto mb-2 p-2 bg-slate-800 rounded-xl flex items-center justify-between text-xs text-blue-400">
                    <div class="flex items-center gap-2 truncate">
                        <span class="truncate">Selected: {{ $attachment->getClientOriginalName() }}</span>
                    </div>
                    <button type="button" wire:click="$set('attachment', null)" class="text-red-400 hover:text-red-300 font-bold ml-2">Remove</button>
                </div>
            @endif

            <form class="flex items-center gap-2 max-w-4xl mx-auto" wire:submit.prevent="sendMessage">
                <label for="chat-file" class="p-3 bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-slate-200 rounded-xl cursor-pointer transition-all shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 4.636a9 9 0 010 12.728l-8.486 8.485a6 6 0 11-8.485-8.485l10.607-10.607a4 4 0 115.656 5.656l-9.192 9.193a2 2 0 11-2.829-2.828l8.486-8.485M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9"></path>
                    </svg>
                </label>
                <input type="file" id="chat-file" wire:model="attachment" class="hidden">

                <div x-data="{ 
                    showPicker: false,
                    emojis: ['😊', '😂', '🤣', '❤️', '🔥', '👍', '🙏', '🎉', '✨', '😎', '😢', '😮', '👏', '🙌', '💯', '🚀'],
                    insertEmoji(emoji) {
                        let currentMessage = $wire.get('message') || '';
                        $wire.set('message', currentMessage + emoji);
                    }
                }" class="flex-1 relative">
                    <input type="text" placeholder="Write a message..." wire:model="message" class="w-full bg-slate-900 border border-slate-800 rounded-xl pl-4 pr-12 py-3 text-sm text-slate-200 outline-none focus:ring-2 focus:ring-blue-500 placeholder-slate-500 transition-all">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <button type="button" x-on:click="showPicker = !showPicker" class="text-xl opacity-75 hover:opacity-100 transition-opacity focus:outline-none">😊</button>
                        <div x-show="showPicker" x-on:click.away="showPicker = false" x-transition class="absolute bottom-full right-0 mb-2 p-3 bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl grid grid-cols-6 gap-2 w-56 z-50 max-h-48 overflow-y-auto" style="display: none;">
                            <template x-for="emoji in emojis">
                                <button type="button" x-on:click="insertEmoji(emoji); showPicker = false" class="text-xl p-1 hover:bg-slate-800 rounded-lg transition-colors" x-text="emoji"></button>
                            </template>
                        </div>
                    </div>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-3 rounded-xl text-sm font-bold transition-all shadow-lg shrink-0">Send</button>
            </form>
        </div>
    @else
        <div class="flex-1 flex flex-col items-center justify-center text-slate-500 select-none">
            <div class="bg-slate-900/60 p-4 rounded-full mb-3 shadow-inner">
                <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"></path>
                </svg>
            </div>
            <p class="text-xs bg-slate-900/50 px-3 py-1.5 rounded-full font-medium text-slate-400 border border-slate-800/40">
                Select a chat to start messaging
            </p>
        </div>
    @endif
</div>