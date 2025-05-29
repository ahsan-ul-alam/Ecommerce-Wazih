<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Mass assignment এর জন্য ফিল্ড গুলো
    protected $fillable = [
        'name',
        'mobile',
        'email',
        'address',
        'district',
        'cart',
        'total_amount',
        'delivery_charge',
        'order_number',
    ];


    // যদি 'cart' JSON হিসেবে রাখো, তাহলে এটা কাস্ট করবে
    protected $casts = [
        'cart' => 'array',
        'total_amount' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
    ];
}
