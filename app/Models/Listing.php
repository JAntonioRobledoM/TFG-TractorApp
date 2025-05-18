<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tractor_id',
        'seller_id',
        'type',
        'price',
        'description',
        'is_active',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the tractor that this listing is for.
     */
    public function tractor()
    {
        return $this->belongsTo(Tractor::class);
    }

    /**
     * Get the seller of this listing.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Alias for the seller relation.
     * This allows accessing the seller as 'user' for backward compatibility.
     */
    public function user()
    {
        return $this->seller();
    }

    /**
     * Get the requests for this listing.
     */
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    /**
     * Get a single apero relationship through the tractor.
     * This is a convenience method for accessing the first apero.
     */
    public function apero()
    {
        return $this->hasOneThrough(
            Apero::class,
            Tractor::class,
            'id', // Clave en la tabla tractors
            'id', // Clave en la tabla aperos
            'tractor_id', // Clave foránea en listings
            'id' // Clave local en tractors
        )->whereExists(function ($query) {
            $query->from('apero_tractor')
                ->whereColumn('apero_tractor.tractor_id', 'tractors.id')
                ->whereColumn('apero_tractor.apero_id', 'aperos.id');
        });
    }
}