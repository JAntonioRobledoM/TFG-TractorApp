<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'listing_id', 'requester_id', 'type', 'status', 
        'offered_price', 'requested_start_date', 'requested_end_date', 'message'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'requested_start_date' => 'date',
        'requested_end_date' => 'date',
        'offered_price' => 'decimal:2',
    ];

    /**
     * Get the listing associated with the request.
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    /**
     * Get the requester who made the request.
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * Get the notifications related to this request.
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'related');
    }
}