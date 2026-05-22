<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversations extends Model
{
   use HasFactory;

     public function messages(){
        return $this->hasMany(Messages::class,'conversation_id');
     }
    
    
       public function sender(){
          return $this->belongsTo(User::class,'sender_id');
       }

         public function receiver(){
          return $this->belongsTo(User::class,'receiver_id');
       }

}
