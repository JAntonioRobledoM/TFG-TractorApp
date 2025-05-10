<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'dni',
        'tlf',
        'pass',
        'pfp',
        'email',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'pass',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_admin' => 'boolean',
        'tlf' => 'integer',
    ];

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
     * Get the requests made by the user.
     */
    public function requests()
    {
        return $this->hasMany(Request::class, 'requester_id');
    }

    /**
     * Get the conversations that the user is a part of.
     */
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class);
    }

    /**
     * Get the messages sent by the user.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get the messages received by the user.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}