<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'first_name', 'last_name', 'dni', 
        'tlf', 'pass', 'pfp', 'email', 'is_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'pass',
    ];

    /**
     * Get the password column name.
     * This overrides Laravel's default 'password' with our 'pass' column.
     */
    public function getAuthPassword()
    {
        return $this->pass;
    }

    /**
     * Get the tractors owned by the user.
     */
    public function tractors()
    {
        return $this->belongsToMany(Tractor::class, 'users_tractors');
    }

    /**
     * Get the listings created by the user.
     */
    public function listings()
    {
        return $this->hasMany(Listing::class, 'seller_id');
    }

    /**
     * Get the requests created by the user.
     */
    public function requests()
    {
        return $this->hasMany(Request::class, 'requester_id');
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the sent messages.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get the received messages.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get the conversations that the user is a part of.
     */
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user');
    }
}