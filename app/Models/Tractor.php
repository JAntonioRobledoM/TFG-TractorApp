<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tractor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'field_2',
        'model',
        'year',
        'description',
        'brand',
        'license_plate',
        'color',
        'horsepower',
        'working_hours',
        'is_available',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'year' => 'integer',
        'horsepower' => 'integer',
        'working_hours' => 'decimal:2',
        'is_available' => 'boolean',
        'field_2' => 'integer',
    ];

    /**
     * Get the users that own this tractor.
     */
    public function owners()
    {
        return $this->belongsToMany(User::class, 'users_tractors');
    }

    /**
     * Get the listings for this tractor.
     */
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    /**
     * Get the aperos attached to this tractor.
     */
    public function aperos()
    {
        return $this->belongsToMany(Apero::class)
            ->withTimestamps()
            ->withPivot('attached_at', 'detached_at');
    }
}