<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function dishes()
    {
        return $this->hasMany(Dish::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, Dish::class);
    }
}
