@extends('front.layout.pages-layout')

@section('content')

    <div class="container">
        <h3>Select Payment Method</h3>

        <div class="row">
            <div class="col-lg-6">
                <form action="{{ route('checkout.handlePaymentForm') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-control" required>
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method->code }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Confirm Order</button>
                </form>
            </div>
        </div>

    </div>

@endsection
