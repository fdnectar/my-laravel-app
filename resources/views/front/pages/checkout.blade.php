@extends('front.layout.pages-layout')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-secondary mb-5">
                <div class="card-header bg-secondary border-0">
                    <h4 class="font-weight-semi-bold m-0">Cart Summary</h4>
                </div>
                <div class="card-body">
                    <h5>Items in your cart</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cart->items as $item)
                            <tr>
                                <td>{{ $item->product->product_name }}</td>
                                <td>₹{{ $item->product->product_price }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₹{{ $item->product->product_price * $item->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between mb-3 pt-1">
                        <h6 class="font-weight-medium">Subtotal</h6>
                        <h6 class="font-weight-medium">₹{{ $sub_total }}</h6>
                    </div>
                </div>

                <div class="card-footer border-secondary bg-transparent">
                    <div class="d-flex justify-content-between mt-2">
                        <h5 class="font-weight-bold">Total</h5>
                        <h5 class="font-weight-bold">₹{{ $sub_total }}</h5>
                    </div>
                    <a href="{{ route('checkout.showShippingForm') }}" type="submit" class="btn btn-block btn-primary my-3 py-3">Complete Order</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
