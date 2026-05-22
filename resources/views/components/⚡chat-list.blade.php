<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversations;
use App\Models\Messages;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;
    public $search = '';
    public $chatId = null;
    public $message = '';
    public $perpage = 15; 

    public function sendMessage(){
        if(empty(trim($this->message))){
            return;
        }

        Messages::create([
            'conversation_id' => $this->chatId, 
            'user_id' => auth()->id(),         
            'body' => $this->message,
        ]);

        $this->message = '';
        $this->perpage += 1; 
    }

    public function loadMore()
    {
       
        $this->perpage += 15; 
    }
  
    public function setChatId($id){
        $this->chatId = $id;
        $this->perpage = 15;                                          
    }

    public function with(): array 
    {
        $user = Auth::user();
        $allChats = $user->allconversations();
       
        if(!empty($this->search)){
            $allChats = $allChats->filter(
                function($conversation){
                    $partner = $conversation->sender_id === auth()->id() ? $conversation->receiver : $conversation->sender;
                    return stripos($partner->name, $this->search) !== false;
                }
            );
        }

        $activeMessages = [];
        $activePartner = null;

        if(!empty($this->chatId)){
            $currentChat = Conversations::find($this->chatId);

            if($currentChat){
              
                $activeMessages = $currentChat->messages()->latest()->take($this->perpage)->get()->reverse();
                $activePartner = $currentChat->sender_id === auth()->id() ? $currentChat->receiver : $currentChat->sender;
            }
        }

        return [
            'conversations' => $allChats,
            'activeMessages' => $activeMessages,
            'activePartner' => $activePartner,
        ];
    }
}; 
?>

<div class="w-full h-screen flex bg-slate-950 text-slate-100 overflow-hidden font-sans">
    
  
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
                            <img src="{{ $chatPartner->avatar }}" class="w-12 h-12 rounded-full object-cover border border-slate-800 shadow-md">
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

    
    <div class="flex-1 h-full flex flex-col bg-slate-950 relative overflow-hidden">
        @if($chatId && $activePartner)
            
            <div class="h-[69px] px-6 border-b border-slate-800/60 bg-slate-900/50 backdrop-blur flex items-center gap-3 shrink-0">
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
                    @forelse($activeMessages as $message)
                        @php
                            $isMe = $message->user_id === auth()->id();
                        @endphp
                        
                        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[65%] rounded-2xl px-4 py-2.5 text-sm shadow-md transition-all
                                {{ $isMe ? 'bg-blue-600 text-white rounded-br-none' : 'bg-slate-800 text-slate-100 rounded-bl-none' }}">
                                <p class="leading-relaxed break-words font-medium">{{ $message->body }}</p>
                                <span class="block text-[10px] text-right mt-1 font-mono {{ $isMe ? 'text-blue-200' : 'text-slate-400' }}">
                                    {{ $message->created_at->format('g:i A') }}
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
                <form class="flex gap-2 max-w-4xl mx-auto" wire:submit.prevent="sendMessage">
                    <input 
                        type="text" 
                        placeholder="Write a message..." 
                        wire:model="message"
                        class="flex-1 bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 outline-none focus:ring-2 focus:ring-blue-500 placeholder-slate-500 transition-all"
                    >
                    <button class="bg-blue-600 hover:bg-blue-500 text-white px-5 rounded-xl text-sm font-bold transition-all shadow-lg active:scale-95">
                        Send
                    </button>
                </form>
            </div>

        @else
           
            <div class="flex-1 flex flex-col items-center justify-center text-slate-500 select-none">
                <div class="bg-slate-900/60 p-4 rounded-full mb-3 shadow-inner">
                    <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"></path>
                    </svg>
                </div>
                <p class="text-xs bg-slate-900/50 px-3 py-1.5 rounded-full font-medium text-slate-400 shadow-sm border border-slate-800/40">
                    Select a chat to start messaging
                </p>
            </div>
        @endif
    </div>

</div>