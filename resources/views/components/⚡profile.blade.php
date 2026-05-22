<?php

use Livewire\Component;
use Livewire\WithFileUploads; 
use Illuminate\Support\Facades\Storage;

new class extends Component
{
    use WithFileUploads; 

    public $image; 

    public function updateProfile()
    {
       
        $this->validate([
            'image' => 'required|image|max:2048', 
        ]);

        $user = auth()->user();

       
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

      
        $path = $this->image->store('avatars', 'public');

      
        $user->update([
            'avatar' => $path
        ]);

       
        $this->reset('image');

      
        $this->dispatch('profile-updated'); 
    }
};
?>

<div class="w-full min-h-screen bg-slate-950 text-slate-100 flex items-center justify-center p-4 font-sans">
    <div class="w-full max-w-md bg-slate-900 border border-slate-800/80 rounded-2xl p-6 shadow-xl relative overflow-hidden">
        
       
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-600/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-purple-600/10 rounded-full blur-2xl"></div>

        <div class="flex flex-col items-center text-center relative z-10">
            
          
            <div class="relative mb-5">
                <div class="absolute inset-0 bg-gradient-to-tr from-blue-600 to-purple-600 rounded-full blur-md opacity-70"></div>
                
                <label for="avatar-input" class="relative block w-28 h-28 rounded-full cursor-pointer group overflow-hidden border-2 border-slate-800 bg-slate-950 shadow-inner">
                    
                 
                    @if ($image)
                        <img class="w-full h-full object-cover" src="{{ $image->temporaryUrl() }}" alt="Preview">
                    @define-block
                 
                    @elseif (Auth::user()->avatar)
                        <img class="w-full h-full object-cover" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                    @else
                       
                        <div class="w-full h-full bg-gradient-to-br from-blue-600 to-blue-500 flex items-center justify-center text-white text-3xl font-black">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif

                   
                    <div class="absolute inset-0 bg-slate-950/60 flex flex-col items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-105 group-hover:scale-100">
                        <svg class="w-6 h-6 text-slate-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"></path>
                        </svg>
                        <span class="text-[10px] uppercase font-bold tracking-wider text-slate-300">Change</span>
                    </div>
                </label>

              
                <input type="file" id="avatar-input" wire:model="image" accept="image/*" class="hidden">
            </div>

            <h1 class="text-xl font-bold text-slate-100 tracking-wide">{{ Auth::user()->name }}</h1>
            <p class="text-sm text-slate-400 mt-1 font-medium">{{ Auth::user()->email }}</p>

           
            <div class="mt-3">
                @if(Auth::user()->email_verified_at)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Verified Account
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending Verification
                    </span>
                @endif
            </div>

            <hr class="w-full border-slate-800/60 my-6">

            <div class="w-full space-y-2.5">
             
                @if ($image)
                    <form wire:submit.prevent="updateProfile" class="w-full">
                        <button type="submit" wire:loading.attr="disabled" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition-all shadow-lg shadow-blue-600/20 active:scale-[0.98] flex items-center justify-center gap-2">
                            <span wire:loading.remove>Save New Profile</span>
                            <span wire:loading class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Uploading...
                            </span>
                        </button>
                    </form>
                @endif

                <a href="/home" class="w-full flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold py-2.5 px-4 rounded-xl text-sm transition-all border border-slate-700/50 active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Back to Messenger
                </a>
            </div>

        </div>
    </div>
</div>