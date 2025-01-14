<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use App\Models\ShoppingCart;

class ProductList extends Component
{
    protected $listeners = ['addToCart'];
    public Product $product;
    public int $quantity = 1;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function addToCart($id)
    {
        $order = Order::firstOrCreate(
            [
                'user_id' => 1,
                'status' => 'cart'
            ],
            [
                'order_number' => 'CART-' . uniqid(),
                'total' => 0
            ]
        );

        $existingOrderItem = OrderItem::where([
            'order_id' => $order->id,
            'product_id' => $id
        ])->first();

        if ($existingOrderItem) {
            $existingOrderItem->increment('quantity');
            $existingOrderItem->update([
                'subtotal' => $existingOrderItem->quantity * $existingOrderItem->unit_price
            ]);
        } else {
            $product = Product::findOrFail($id);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => 1,
                'unit_price' => $product->product_price,
                'subtotal' => $product->product_price
            ]);
        }

        $order->update([
            'total' => $order->items->sum('subtotal')
        ]);

        $this->dispatch('updateCartCount');
        session()->flash('success', 'Product added to cart successfully');
    }

    public function render()
    {
        $data = [
            'products' => Product::all()
        ];
        return view('livewire.product-list', $data);
    }
}
