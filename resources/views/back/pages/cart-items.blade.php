
@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : '' )

@push('custom-styles')

<style>
    .badge-text {
        font-size: 12px;
    }
</style>

@endpush

@section('content')


    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Ordered Items</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Cart Items</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">

                    @livewire('order-items')

                </div>
            </div>
        </div> <!-- end col -->
    </div>



@endsection
