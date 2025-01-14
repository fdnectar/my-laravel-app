<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
        'specifications'
    ];

    protected $casts = [
        'specifications' => 'array'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function incrementQuantity()
    {
        $this->increment('quantity');
        $this->update([
            'subtotal' => $this->quantity * $this->unit_price
        ]);

        // // Update order total
        // $this->order->update([
        //     'total' => $this->order->items->sum('subtotal')
        // ]);
    }

    public function updateQuantity($quantity): void
    {
        $this->quantity = $quantity;
        $this->subtotal = $this->unit_price * $quantity;
        $this->save();

        $this->order->recalculateTotal();
    }
}
