
@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : '' )

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Edit Product</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Edit Product</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{ route('admin.update-product') }}" method="POST" enctype="multipart/form-data" id="updateProductForm">
        <div class="row">
            <div class="col-xl-8 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <x-form-alerts></x-form-alerts>
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Product Name</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name }}" placeholder="Enter Product Name"
                                    value="">
                                    <span class="text-danger error-text product_name_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Product SKU (Leave empty for auto sku generation)</label>
                                    <input type="text" class="form-control" id="sku_prefix" name="sku_prefix" placeholder="e.g., SHOES"
                                    value="{{ $product->sku }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Product SKU (Add Manually)</label>
                                    <input type="text" class="form-control" id="sku" name="sku" placeholder="e.g., SHOES_BLU_001"
                                    value="{{ $product->sku }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Product Description</label>
                                    <textarea name="product_description" id="product_description" placeholder="Enter Product Summary" cols="4" rows="4" class="form-control summernote"
                                    >{!! $product->description !!}</textarea>
                                    <span class="text-danger error-text product_description_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Product Price</label>
                                    <input type="text" class="form-control" id="product_price" name="product_price" value="{{ $product->product_price }}" placeholder="Eg: ₹ 500"
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
                                        <img src="/images/products/{{ $product->product_image }}" alt="" class="img-thumbnail" id="product-image-preview">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Product Stock Quantity</label>
                                    <input type="text" class="form-control" id="stock_quantity" name="stock_quantity" value="{{ $product->stock_quantity }}" placeholder="Eg: 25"
                                    value="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Product Low Stock Threshold</label>
                                    <input type="text" class="form-control" id="low_stock_threshold" name="low_stock_threshold" value="{{ $product->low_stock_threshold }}" placeholder="Eg: 25"
                                    value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <button type="submit" class="btn btn-primary w-md">Update Product</button>
        </div>
    </form>

    <div class="row mt-5">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h5 class="my-0 text-dark">Additional Images</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.upload-product-images', ['product_id' => request('id')]) }}" class="dropzone">
                        <input type="hidden" name="product_id" value="{{ request('id') }}">
                        @csrf
                    </form>
                    <div class="mb-2 mt-2">
                        <button type="submit" class="btn btn-primary w-md" id="uploadAdditionalImagesBtn">Upload Images</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-container mb-2" id="product_images">

        </div>
    </div>


@endsection

@push('custom-styles')

    <link rel="stylesheet" href="/extra-assets/dropzonejs/min/dropzone.min.css">
    <style>
        .box-container {
            width: 100%;
            display: flex;
            flex-direction: row;
            gap: 1rem;
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        .box-container .box {
            background: #423838;
            width: 110px;
            height: 110px;
            position: relative;
            overflow: hidden;
        }

        .box-container .box img{
            width: 100%;
            height: 100%;
        }

        .box-container .box a {
            position: absolute;
            right: 7px;
            bottom: 5px;
        }

    </style>

@endpush

@push('custom-scripts')

<script src="/extra-assets/dropzonejs/min/dropzone.min.js"></script>

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

    $("#updateProductForm").on('submit', function(e) {
        e.preventDefault();
        var summary = $('textarea.summernote').summernote('code')
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
                    $('#success .toast-body').text(response.msg);
                    var successToast = new bootstrap.Toast(document.getElementById('success'));
                    successToast.show();

                    setTimeout(function() {
                        location.reload();
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

    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone('.dropzone', {
        autoProcessQueue:false,
        parallelUploads:1,
        addRemoveLinks:true,
        maxFileSize:2,
        acceptedFiles:'image/*',
        init:function() {
            thisDz = this;
            var uploadBtn = document.getElementById('uploadAdditionalImagesBtn');
            uploadBtn.addEventListener('click', function() {
                var nFiles = myDropzone.getQueuedFiles().length;
                thisDz.options.parallelUploads = nFiles;
                thisDz.processQueue();
            });
            thisDz.on('queuecomplete', function(){
                this.removeAllFiles();
                getProductImages();
            });
        }
    });

    getProductImages()
    function getProductImages() {
        var url = "{{ route('admin.get-product-images', ['product_id' => request('id')]) }}";
        $.get(url, {}, function(response) {
            $('div#product_images').html(response.data);
        }, 'json');
    }

    $(document).on('click', '#deleteProductImage', function(e) {
        e.preventDefault();
        var url = "{{ route('admin.delete-product-images') }}";
        var token = "{{ csrf_token() }}";
        var image_id = $(this).data("image");
        Swal.fire({
            title: "Are you sure?",
            text: "You want to delete this image!",
            icon: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#2ab57d",
            cancelButtonColor: "#fd625e",
            confirmButtonText: "Yes, delete it!",
            allowOutsideClick: false,
        }).then(function (result) {
            if(result.value) {
                $.post(url, { _token:token, image_id:image_id }, function(response){
                    if(response.status == 1) {
                        getProductImages();
                        $('#success .toast-body').text(response.msg);
                        var successToast = new bootstrap.Toast(document.getElementById('success'));
                        successToast.show();
                    } else {
                        $('#danger .toast-body').text(response.msg);
                        var dangerToast = new bootstrap.Toast(document.getElementById('danger'));
                        dangerToast.show();
                    }
                }, 'json');
            }
        });
    });

</script>

@endpush
