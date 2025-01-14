<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\PaymentOption;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    public function index(Request $request) {
        $cart = Order::where([
            'user_id' => 1,
            'status' => 'cart'
        ])->with(['items.product'])->first();

        if (!$cart) {
            return redirect()->route('shoppingcart')->with('error', 'Your cart is empty');
        }

        $sub_total = 0;
        foreach ($cart->items as $item) {
            $sub_total += $item->product->product_price * $item->quantity;
        }

        return view('front.pages.checkout', [
            'cart' => $cart,
            'sub_total' => $sub_total,
        ]);
    }

    public function showShippingForm() {
        return view('front.pages.shipping');
    }

    public function handleShippingForm(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'shipping_address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
        ]);

        session(['shipping' => $validated]);
        return redirect()->route('checkout.showPaymentForm');
    }

    public function showPaymentForm()
    {
        $paymentMethods = PaymentOption::all();
        return view('front.pages.payment', compact('paymentMethods'));
    }

    public function handlePaymentForm(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:card,cod',
        ]);

        $cart = Order::where(['user_id' => 1, 'status' => 'cart'])->with(['items.product'])->first();
        // dd($cart);
        $shipping = session('shipping');
        // dd($shipping);

        $paymentStatus = false;
        $paymentDetails = null;

        if ($validated['payment_method'] == 'card') {

            $paymentDetails = [
                'order_id' => $cart->id,
                'payment_method' => 'Debit Card',
                'amount' => $cart->total,
                'status' => 'success',
                'transaction_id' => uniqid()
            ];
            $paymentStatus = true;
        } else {
            $paymentDetails = [
                'order_id' => $cart->id,
                'payment_method' => 'Cash on Delivery',
                'amount' => $cart->total,
                'status' => 'pending',
                'transaction_id' => null,
            ];
            $paymentStatus = true;
        }

        if ($paymentStatus) {
            $payment = Payment::create($paymentDetails);
            $cart->update([
                'status' => 'completed',
                'shipping_address' => $shipping['shipping_address'],
                'phone_number' => $shipping['phone_number'],
                'checked_out_at' => now(),
            ]);
            session(['payment' => $payment]);
            return redirect()->route('checkout.confirmation');
        } else {
            session()->flash('error', 'Payment failed. Please try again.');
            return redirect()->route('checkout.showPaymentForm');
        }
    }

    public function checkoutConfirmation()
    {
        return view('front.pages.confirmation', );
    }

}
