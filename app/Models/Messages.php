<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    
public function conversation(){
    return $this->belongsTo(Conversations::class,'conversation_id');
}

public function user(){
    return $this->belongsTo(User::class,'user_id');
}
}
