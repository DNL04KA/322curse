<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'price',
        'image',
        'is_available',
        'restaurant_id',
        'weight',
        'calories',
        'protein',
        'fat',
        'carbs',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'weight' => 'integer',
        'calories' => 'integer',
        'protein' => 'decimal:2',
        'fat' => 'decimal:2',
        'carbs' => 'decimal:2',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
