<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;

class TopHeader extends Component
{
    public $totalItem = 0;

    protected $listeners = ['updateCartCount' => 'getCartItemCount'];

    public function getCartItemCount() {

        $order = Order::where([
                    'user_id' => 1,
                    'status' => 'cart'
                ])->withCount('items')->first();

        $this->totalItem = $order ? $order->items_count : 0;
    }

    public function render()
    {
        $this->getCartItemCount();
        return view('livewire.top-header');
    }
}
