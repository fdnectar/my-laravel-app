<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrderItems extends Component
{
    public function render()
    {
        $orders = Order::with(['payment', 'items.product'])
                ->orderBy('created_at', 'desc')
                ->get();

        return view('livewire.order-items', compact('orders'));
    }
}
