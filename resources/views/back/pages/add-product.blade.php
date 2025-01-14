
@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : '' )

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Add Product</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Add Product</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{ route('admin.store-product') }}" method="POST" enctype="multipart/form-data" id="addProductForm">
        <div class="row">
            <div class="col-xl-8 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <x-form-alerts></x-form-alerts>
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Product Name</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter Product Name"
                                    value="">
                                    <span class="text-danger error-text product_name_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Product SKU (Leave empty for auto sku generation)</label>
                                    <input type="text" class="form-control" id="sku_prefix" name="sku_prefix" placeholder="e.g., SHOES"
                                    value="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Product SKU (Add Manually)</label>
                                    <input type="text" class="form-control" id="sku" name="sku" placeholder="e.g., SHOES_BLU_001"
                                    value="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Product Description</label>
                                    <textarea name="product_description" id="product_description" placeholder="Enter Product Summary" cols="4" rows="4" class="form-control summernote"
                                    ></textarea>
                                    <span class="text-danger error-text product_description_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Product Price</label>
                                    <input type="text" class="form-control" id="product_price" name="product_price" placeholder="Eg: ₹ 500"
                                    value="">
                                    <span class="text-danger erro-text product_price_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Product Image (Must be square and Maximum dimension of (1080 X 1080))</label>
                                    <input type="file" class="form-control" id="product_image" name="product_image">
                                    <span class="text-danger erro-text product_image_error"></span>
                                    <div class="mb-2 mt-1" style="max-width: 250px;">
                                        <img src="" alt="" class="img-thumbnail" id="product-image-preview">
                                    </div>
                                    <b>NB</b>: <small class="text-danger">You will be able to add more images related to this product, stock qunatity, product low stock threshold when you are on edit product page.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <button type="submit" class="btn btn-primary w-md">Add Product</button>
        </div>
    </form>


@endsection

@push('custom-scripts')

<script>
    $('input[type="file"][name="product_image"][id="product_image"]').on('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                $('#product-image-preview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    $("#addProductForm").on('submit', function(e) {
        e.preventDefault();
        var summary = $('textarea.summernote').summernote('code');
        var form = this;
        var formdata = new FormData(form);
            formdata.append('product_description', summary);

        $.ajax({
            url:$(form).attr('action'),
            method:$(form).attr('method'),
            data:formdata,
            processData:false,
            dataType:'json',
            contentType:false,
            beforeSend:function() {
                $(form).find('span.error-text').text('');
            },
            success:function(response) {
                if(response.status == 1) {
                    $(form)[0].reset();
                    $('textarea.summernote').summernote('code', '');
                    $('#product-image-preview').attr('src', '');
                    $('#success .toast-body').text(response.msg);
                    var successToast = new bootstrap.Toast(document.getElementById('success'));
                    successToast.show();
                    setTimeout(function() {
                        window.location.href = '{{ route("admin.all-products") }}';
                    }, 2000);
                } else {
                    $('#danger .toast-body').text(response.msg);
                    var dangerToast = new bootstrap.Toast(document.getElementById('danger'));
                    dangerToast.show();
                }
            },
            error:function(response) {
                $.each(response.responseJSON.errors, function(prefix, val) {
                    $(form).find('span.'+prefix+'_error').text(val[0]);
                });
            }
        });
    });

</script>

@endpush
