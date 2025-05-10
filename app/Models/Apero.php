<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * Get the tractors that this apero is attached to.
     */
    public function tractors()
    {
        return $this->belongsToMany(Tractor::class)
            ->withTimestamps()
            ->withPivot('attached_at', 'detached_at');
    }
}