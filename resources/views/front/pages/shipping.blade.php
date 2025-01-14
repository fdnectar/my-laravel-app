@extends('front.layout.pages-layout')

@section('content')

<div class="container">
    <h3>Shipping Details</h3>

    <div class="row">
        <div class="col-lg-6">
            <form action="{{ route('checkout.handleShippingForm') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="shipping_address">Shipping Address</label>
                    <input type="text" name="shipping_address" id="shipping_address" class="form-control" placeholder="Enter Shipping Address">
                    @error('shipping_address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" placeholder="Enter Phone Number" class="form-control">
                    @error('phone_number')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Next</button>
            </form>
        </div>
    </div>

</div>

@endsection
