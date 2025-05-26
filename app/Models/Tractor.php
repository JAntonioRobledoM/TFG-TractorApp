<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Tractor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'model',
        'year',
        'description',
        'brand',
        'license_plate',
        'color',
        'horsepower',
        'working_hours',
        'is_available',
        'image',
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
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['image_url'];

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

    /**
     * Get the full URL for the tractor image.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        
        return null;
    }

    /**
     * Delete the tractor image when the model is deleted.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($tractor) {
            if ($tractor->image) {
                Storage::delete($tractor->image);
            }
        });
    }
}