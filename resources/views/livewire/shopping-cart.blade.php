<div>
    @include('front.layout.flash')
    <div class="row px-xl-5 pt-5">
        <div class="col-lg-8 table-responsive mb-5">
            <table class="table table-bordered text-center mb-0">
                <thead class="bg-secondary text-dark">
                    <tr>
                        <th>Products</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @if ($cart && $cart->items)
                        @foreach ($cart->items as $item)
                            <tr>
                                <td class="align-middle">
                                    <img src="/images/products/{{ $item->product->product_image }}" alt="" style="width: 50px; height: 50px; margin-right: 10px;">{{ $item->product->product_name }}</td>
                                <td class="align-middle">₹ {{ $item->product->product_price }}</td>
                                <td class="align-middle">
                                    <div class="input-group quantity mx-auto" style="width: 100px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-primary btn-minus" wire:click="deccrementQty({{ $item->id }})">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control form-control-sm bg-secondary text-center"
                                            value="{{ $item->quantity }}">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-primary btn-plus" wire:click="incrementQty({{ $item->id }})">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">₹ {{ $item->product->product_price * $item->quantity }}</td>
                                <td class="align-middle">
                                    <button class="btn btn-sm btn-primary" wire:click="removeItem({{ $item->id }})"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <p>No items found in the cart.</p>
                    @endif

                </tbody>
            </table>
        </div>
        <div class="col-lg-4">
            <div class="card border-secondary mb-5">
                <div class="card-header bg-secondary border-0">
                    <h4 class="font-weight-semi-bold m-0">Cart Summary</h4>
                </div>
                <div class="card-body">
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
                    <a href="{{ route('checkout.index') }}" class="btn btn-block btn-primary my-3 py-3">Proceed To Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>
