<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotKnowledge extends Model
{
    use HasFactory;

    protected $table = 'bot_knowledge';
    protected $fillable = [
        'question', 
        'answer', 
        'confidence', 
        'created_at', 
        'updated_at'
    ];
    public $timestamps = true;
}
