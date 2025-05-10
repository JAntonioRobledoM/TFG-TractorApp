<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
    ];

    /**
     * Get the users that are part of this conversation.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the messages for this conversation.
     */
    public function messages()
    {
        return $this->morphMany(Message::class, 'related');
    }
}