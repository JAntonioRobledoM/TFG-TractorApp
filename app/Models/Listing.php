<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tractor_id', 'seller_id', 'type', 'price', 
        'description', 'is_active', 'start_date', 'end_date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
    ];

    /**
     * Get the tractor associated with the listing.
     */
    public function tractor()
    {
        return $this->belongsTo(Tractor::class);
    }

    /**
     * Get the seller of the listing.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the requests for this listing.
     */
    public function requests()
    {
        return $this->hasMany(Request::class);
    }
}