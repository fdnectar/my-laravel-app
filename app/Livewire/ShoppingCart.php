<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use App\Models\OrderItem;
use App\Models\ShoppingCart as Cart;

class ShoppingCart extends Component
{
    public $cartItems, $sub_total = 0, $total = 0;

    public function incrementQty($id) {
        $cartItem = OrderItem::findOrFail($id);
        $cartItem->quantity += 1;
        $cartItem->subtotal = $cartItem->quantity * $cartItem->unit_price;
        $cartItem->save();

        $order = $cartItem->order;
        $order->total = $order->items->sum('subtotal');
        $order->save();
    }

    public function deccrementQty($id) {
        $cartItem = OrderItem::findOrFail($id);
        if($cartItem->quantity > 1) {
            $cartItem->quantity -= 1;
            $cartItem->subtotal = $cartItem->quantity * $cartItem->unit_price;
            $cartItem->save();

            $order = $cartItem->order;
            $order->total = $order->items->sum('subtotal');
            $order->save();
        } else {
            session()->flash('info', 'You cannot have less than 1 quantity');
        }
    }

    public function removeItem($itemId)
    {
        $orderItem = OrderItem::find($itemId);

        if ($orderItem) {
            $order = $orderItem->order;
            $orderItem->delete();

            if ($order->items()->count() > 0) {
                $order->update([
                    'total' => $order->items()->sum('subtotal')
                ]);
            } else {
                $order->update([
                    'total' => 0,
                    'status' => 'cancelled'
                ]);
            }

            $this->dispatch('updateCartCount');
            session()->flash('success', 'Item removed successfully');
        } else {
            session()->flash('error', 'Item not found');
        }
    }

    public function render()
    {
        $cart = Order::where([
            'user_id' => 1,
            'status' => 'cart'
        ])->with(['items.product'])->first();

        $this->total = 0;
        $this->sub_total = 0;
        if ($cart) {
            foreach ($cart->items as $item) {
                $this->sub_total += $item->product->product_price * $item->quantity;
            }
        } else {
            $this->sub_total = 0;
        }

        return view('livewire.shopping-cart', ['cart' => $cart]);
    }
}
