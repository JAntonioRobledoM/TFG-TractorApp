<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title'
    ];

    /**
     * Get the users that are part of the conversation.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_user');
    }

    /**
     * Get the messages for this conversation.
     * Note: This assumes you'll add a conversation_id to messages table
     * or you'll need to filter messages based on users in this conversation.
     */
    public function messages()
    {
        // If you add conversation_id to messages table:
        // return $this->hasMany(Message::class);
        
        // Alternative implementation would require custom logic
    }
}