<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'offered_price' => 'decimal:2',
        'requested_start_date' => 'date',
        'requested_end_date' => 'date',
    ];

    /**
     * Get the listing that this request is for.
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    /**
     * Get the user who made this request.
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

        /**
     * La conversación asociada a esta solicitud.
     */
    public function conversation()
    {
        return $this->hasOne(Conversation::class);
    }
}