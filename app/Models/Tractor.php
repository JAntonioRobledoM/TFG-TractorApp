<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tractor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'field_2', 'model', 'year', 'description'
    ];

    /**
     * Get the users that own the tractor.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'users_tractors');
    }

    /**
     * Get the listings for the tractor.
     */
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }
}