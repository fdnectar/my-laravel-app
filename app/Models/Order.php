<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'total',
        'notes',
        'checked_out_at',
        'shipping_address',
        'phone_number',
    ];

    protected $casts = [
        'checked_out_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function recalculateTotal(): void
    {
        $this->total = $this->items->sum('subtotal');
        $this->save();
    }

    public static function getCurrentCart($userId)
    {
        return self::firstOrCreate(
            [
                'user_id' => $userId,
                'status' => 'cart'
            ],
            [
                'order_number' => 'CART-' . uniqid()
            ]
        );
    }

    // Helper method to check if order is in cart state
    public function isCart(): bool
    {
        return $this->status === 'cart';
    }
}
