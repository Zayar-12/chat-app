<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Messages extends Model
{
     use HasFactory;

     public $fillable = [
        
     'conversation_id',
     'user_id',
     'body',
     'file_path',
     'read_at',
     ];
    
public function conversation(){
    return $this->belongsTo(Conversations::class,'conversation_id');
}

public function user(){
    return $this->belongsTo(User::class,'user_id');
}
}
