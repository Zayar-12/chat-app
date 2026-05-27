<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversations;
use App\Models\Messages;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;

new class extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $chatId = null;
    public $message = '';
    public $perpage = 15; 
    public $attachment;

    public $strangerName='';
    public $modelPopUp=false;


    public function updatingStrangerName()
    {
        $this->resetPage(); 
    }

    
//     public function getStrangerProperty(){
//   return User::whereNot(function($query){
//         $query->where('id',auth()->id());
//   })->whereDoesntHave('conversationStart',function($query){
//     $query->where('sender_id',auth()->id());
//   })->whereDoesntHave('conversationReceive',function($query){
//     $query->where('receiver_id',auth()->id());
//   })->where('name','like','%'.$this->strangerName.'%')->paginate(5);
//     }

public function getStrangerProperty(){

    $authId=auth()->id();

    $existingUserIds= Conversations::where('sender_id',$authId)->pluck('receiver_id')
    ->merge(
        Conversations::where('receiver_id',$authId)->pluck('sender_id')
    )->unique()->toArray();


   $query = User::where('id', '!=', $authId)
        ->whereNotIn('id', $existingUserIds)
        ->where('name', 'like', '%' . $this->strangerName . '%');

    return $query->paginate(5);
}


public function deleteChat($id){

    $conversation=Conversations::find($id);


    if($conversation->sender_id === auth()->id() || $conversation->receiver_id === auth()->id()){


     $messages= $conversation->messages()->get();

     if($messages){
        foreach ($messages as $message) {
            if($message->file_path){
 Storage::disk('public')->delete($message->file_path);
            }

         $message->delete();
        }
     }

     $conversation->delete();

     if($this->chatId === $id){
        $this->chatId = null;

     }
    
    }
}

    public  function startConversation($userId){
        $newChat=Conversations::create([
            'sender_id'=>auth()->id(),
           'receiver_id' => $userId,
            'last_message_at' => now(),

        ]);

        $this->chatId=$newChat->id;

        $this->strangerName='';
        $this->modelPopUp=false;
        $this->perpage=15;


    }


    

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
         if ($Message->file_path) {
            Storage::disk('public')->delete($Message->file_path);
        }
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
            'strangers'=>$this->getStrangerProperty(),
        ];
    }
}; 
?>
<div class="w-full h-screen flex bg-slate-950 text-slate-100 overflow-hidden font-sans">
    
    <x-chat-sideBar 
        :conversations="$conversations" 
        :chatId="$chatId" 
        :search="$search" 
    />

    <x-chat-area 
        :chatId="$chatId" 
        :activePartner="$activePartner" 
        :activeMessages="$activeMessages" 
        :perpage="$perpage" 
        :attachment="$attachment" 
        :message="$message" 
    />

    <x-new-chatModel 
        :modelPopUp="$modelPopUp" 
        :strangerName="$strangerName" 
        :strangers="$strangers" 
    />

</div>