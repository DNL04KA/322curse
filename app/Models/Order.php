<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'delivery_address',
        'total_amount',
        'status',
        'delivery_time',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'delivery_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'order_items')
            ->withPivot('quantity', 'price', 'special_instructions')
            ->withTimestamps();
    }
}
