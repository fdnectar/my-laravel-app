<?php

namespace App\Models;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'sku',
        'product_name',
        'description',
        'product_image',
        'product_price',
        'stock_quantity',
        'low_stock_threshold',
        'track_inventory',
        'status',
        'specifications'
    ];

    protected $casts = [
        'specifications' => 'array',
        'track_inventory' => 'boolean',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function images() {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }
}
