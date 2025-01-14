@extends('front.layout.pages-layout')

@section('content')

@include('front.layout.flash')

<div class="row px-xl-5 py-5">
    <div class="col-lg-5 pb-5">
        <div id="product-carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner border">
                <div class="carousel-item active">
                    <img class="w-100 h-100" src="/images/products/{{ $product->product_image }}" alt="Image">
                </div>
                @foreach ($product->images as $image)
                    <div class="carousel-item">
                        <img class="w-100 h-100" src="/images/products/additionals/{{ $image->image }}" alt="Image">
                    </div>
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                <i class="fa fa-2x fa-angle-left text-dark"></i>
            </a>
            <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                <i class="fa fa-2x fa-angle-right text-dark"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-7 pb-5">
        <h3 class="font-weight-semi-bold">{{ $product->product_name }}</h3>
        <div class="d-flex mb-3">
            <div class="text-primary mr-2">
                <small class="fas fa-star"></small>
                <small class="fas fa-star"></small>
                <small class="fas fa-star"></small>
                <small class="fas fa-star-half-alt"></small>
                <small class="far fa-star"></small>
            </div>
            <small class="pt-1">(50 Reviews)</small>
        </div>
        <h3 class="font-weight-semi-bold mb-4">₹{{ $product->product_price }}.00</h3>
        <p class="mb-4">Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.</p>

        <div class="d-flex align-items-center mb-4 pt-2">
            <button
                    onclick="Livewire.dispatch('addToCart', { id: {{ $product->id }} })"
                    class="btn btn-primary px-3"
                >
                    <i class="fa fa-shopping-cart mr-1"></i> Add To Cart
            </button>
        </div>

        <div class="d-flex pt-2">
            <p class="text-dark font-weight-medium mb-0 mr-2">Share on:</p>
            <div class="d-inline-flex">
                <a class="text-dark px-2" href="">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a class="text-dark px-2" href="">
                    <i class="fab fa-twitter"></i>
                </a>
                <a class="text-dark px-2" href="">
                    <i class="fab fa-linkedin-in"></i>
                </a>
                <a class="text-dark px-2" href="">
                    <i class="fab fa-pinterest"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div style="display: none;">
    @livewire('product-list')
</div>

@endsection

@push('custom-scripts')



@endpush
