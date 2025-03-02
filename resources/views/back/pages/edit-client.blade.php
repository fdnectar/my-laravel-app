
@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : '' )

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Edit Client</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Edit Client</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{ route('admin.update-client') }}" method="POST" id="editClientForm">
        <div class="row">
            <div class="col-xl-8 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <x-form-alerts></x-form-alerts>
                        @csrf
                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Name</label>
                                    <input type="text" class="form-control" id="client_name" name="client_name" value="{{ $client->name }}" placeholder="Enter Client Name"
                                    value="">
                                    <span class="text-danger error-text client_name_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Email</label>
                                    <input type="email" class="form-control" id="client_email" name="client_email" value="{{ $client->email }}" placeholder="Enter Client Email"
                                    value="">
                                    <span class="text-danger error-text client_email_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Phone Number</label>
                                    <input type="text" class="form-control" id="client_phone" name="client_phone" value="{{ $client->phone }}" placeholder="Enter Client Phone"
                                    value="">
                                    <span class="text-danger error-text client_phone_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Address</label>
                                    <textarea name="client_address" id="client_address" class="form-control" placeholder="Enter Client Address">{{ $client->address }}</textarea>
                                    <span class="text-danger error-text client_address_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <button type="submit" class="btn btn-primary w-md">Edit Client</button>
        </div>
    </form>


@endsection

@push('custom-scripts')

<script>

    $("#editClientForm").on('submit', function(e) {
        e.preventDefault();
        var form = this;
        var formdata = new FormData(form);

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
                        window.location.href = '{{ route("admin.all-clients") }}';
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
