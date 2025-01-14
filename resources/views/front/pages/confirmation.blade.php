
@extends('front.layout.pages-layout')

@section('content')
    <div class="container">
        <h3>Payment Confirmation</h3>

        @if(session('payment'))
            @php
                $payment = session('payment');
            @endphp

            <div class="alert alert-success">
                <h4>Payment Successful!</h4>
                <p><strong>Transaction ID:</strong> {{ $payment->transaction_id ?? 'N/A' }}</p>
                <p><strong>Payment Method:</strong> {{ $payment->payment_method }}</p>
                <p><strong>Total Amount Paid:</strong> ₹{{ $payment->amount }}</p>
                <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
            </div>

            <h5>Shipping Address:</h5>
            <p>{{ session('shipping')['shipping_address'] }}</p>
            <p>{{ session('shipping')['phone_number'] }}</p>

            <a href="{{ route('/') }}" class="btn btn-primary">Back to Home</a>
        @else
            <div class="alert alert-danger">
                <p>No payment details found.</p>
            </div>
        @endif
    </div>


@endsection

