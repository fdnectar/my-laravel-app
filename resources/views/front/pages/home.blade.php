@extends('front.layout.pages-layout')

@section('content')

<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">All Products</span></h2>
    </div>
    @livewire('product-list')
</div>

@endsection
