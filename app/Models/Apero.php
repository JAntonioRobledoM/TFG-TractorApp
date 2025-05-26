<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Apero extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'description',
        'brand',
        'model',
        'year',
        'is_available',
        'user_id',
        'image',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'year' => 'integer',
        'is_available' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['image_url'];

    /**
     * Get the tractors that this apero is attached to.
     */
    public function tractors()
    {
        return $this->belongsToMany(Tractor::class)
            ->withTimestamps()
            ->withPivot('attached_at', 'detached_at');
    }

    /**
     * Get the full URL for the apero image.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        
        return null;
    }

    /**
     * Delete the apero image when the model is deleted.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($apero) {
            if ($apero->image) {
                Storage::delete($apero->image);
            }
        });
    }
}