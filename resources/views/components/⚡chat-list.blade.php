<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversations;
use App\Models\Messages;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $chatId = null;
    public $message = '';
    public $perpage = 15; 
    public $attachment;

    public function sendMessage(){
        if(empty(trim($this->message)) && !$this->attachment){
            return;
        }

        $filePath=null;
        $fileType=null;
        $extension=null;
        $attachmentExtension=null;

        if($this->attachment){

            $this->validate([
                'attachment' => 'required|max:20480',
            ]);

           $attachmentExtension = $this->attachment->getClientOriginalExtension();
    $extension = strtolower($attachmentExtension);

            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $fileType = 'image';
            } elseif (in_array($extension, ['mp4', 'mov', 'avi', 'wmv'])) {
                $fileType = 'video';
            } else {
                $fileType = 'file'; 
            }


            $filePath=$this->attachment->store('chat-files','public');

        }

        

        Messages::create([
           'conversation_id' => $this->chatId, 
            'user_id' => auth()->id(),         
            'body' => $this->message,
            'file_path' => $filePath, 
            'file_type' => $fileType,
        ]);

        $this->message = '';
        $this->attachement=null;
        $this->perpage += 1; 
    }

public function deleteMessage($messageId){

    $Message=Messages::find($messageId);

    if($Message && $Message->user_id === auth()->id()){
        $Message->delete();
    }
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
                   
                            <img src="{{ asset('storage/' . $chatPartner->avatar) }}"" class="w-12 h-12 rounded-full object-cover border border-slate-800 shadow-md">
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
        
        <div class="flex items-center gap-2 group {{ $isMe ? 'justify-end flex-row' : 'justify-start' }}">
            
           
            @if($isMe)
                <button 
                    wire:click="deleteMessage({{ $message->id }})" 
                    wire:confirm="Are you sure you want to delete this message?"
                    class="opacity-0 group-hover:opacity-100 text-slate-500 hover:text-red-400 p-1.5 rounded-full hover:bg-slate-800/60 transition-all duration-200 cursor-pointer shrink-0"
                    title="Delete Message"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                    </svg>
                </button>
            @endif

           
            <div class="max-w-[65%] rounded-2xl px-4 py-2.5 text-sm shadow-md transition-all
                {{ $isMe ? 'bg-blue-600 text-white rounded-br-none' : 'bg-slate-800 text-slate-100 rounded-bl-none' }}">
                
              
                @if($message->file_path)
                    <div class="mb-2 max-w-full overflow-hidden rounded-lg">
                        
                     
                        @if($message->file_type === 'image')
                            <a href="{{ asset('storage/' . $message->file_path) }}" target="_blank" class="block hover:opacity-90 transition-opacity">
                                <img src="{{ asset('storage/' . $message->file_path) }}" class="max-h-60 w-auto object-cover rounded-lg shadow-sm border border-black/10">
                            </a>

                       
                        @elseif($message->file_type === 'video')
                            <video controls class="max-h-60 w-full rounded-lg shadow-sm border border-black/10">
                                <source src="{{ asset('storage/' . $message->file_path) }}" type="video/{{ pathinfo($message->file_path, PATHINFO_EXTENSION) }}">
                                Your browser does not support the video tag.
                            </video>

                      
                        @else
                            <a href="{{ asset('storage/' . $message->file_path) }}" download class="flex items-center gap-2 p-2.5 rounded-xl border transition-colors text-xs font-medium
                                {{ $isMe ? 'bg-blue-700/60 hover:bg-blue-700/90 border-blue-500/30 text-blue-100' : 'bg-slate-900/60 hover:bg-slate-900/90 border-slate-700/40 text-blue-400' }}">
                                <svg class="w-5 h-5 shrink-0 {{ $isMe ? 'text-blue-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                                </svg>
                                <span class="truncate max-w-[150px]">{{ basename($message->file_path) }}</span>
                                <svg class="w-4 h-4 shrink-0 ml-auto {{ $isMe ? 'text-blue-300' : 'text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                @endif

               
                @if(!empty(trim($message->body)))
                    <p class="leading-relaxed break-words font-medium">{{ $message->body }}</p>
                @endif

              
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
    
 
    @if ($attachment)
        <div class="max-w-4xl mx-auto mb-2 p-2 bg-slate-800 rounded-xl flex items-center justify-between text-xs text-blue-400">
            <div class="flex items-center gap-2 truncate">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                </svg>
                <span class="truncate">Selected: {{ $attachment->getClientOriginalName() }}</span>
            </div>
            <button type="button" wire:click="$set('attachment', null)" class="text-red-400 hover:text-red-300 font-bold ml-2">Remove</button>
        </div>
    @endif

    <form class="flex items-center gap-2 max-w-4xl mx-auto" wire:submit.prevent="sendMessage">
        
   
        <label for="chat-file" class="p-3 bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-slate-200 rounded-xl cursor-pointer transition-all shrink-0" title="Attach image, video or file">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 4.636a9 9 0 010 12.728l-8.486 8.485a6 6 0 11-8.485-8.485l10.607-10.607a4 4 0 115.656 5.656l-9.192 9.193a2 2 0 11-2.829-2.828l8.486-8.485M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9"></path>
            </svg>
        </label>
        
        <input type="file" id="chat-file" wire:model="attachment" class="hidden">

        
        <input 
            type="text" 
            placeholder="Write a message..." 
            wire:model="message"
            class="flex-1 bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 outline-none focus:ring-2 focus:ring-blue-500 placeholder-slate-500 transition-all"
        >

        {{-- Send ခလုတ် --}}
        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-3 rounded-xl text-sm font-bold transition-all shadow-lg active:scale-95 shrink-0">
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

