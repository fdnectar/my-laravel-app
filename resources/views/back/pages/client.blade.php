@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : '')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Product Page</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Product Page</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('admin.add-clients') }}" class="btn btn-primary waves-effect waves-light">Add Client</a>
                </div>
                <div class="card-body">

                    <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

                        <div class="row">
                            <div class="col-sm-12">
                                <table id="datatable"
                                    class="table table-bordered dt-responsive nowrap w-100 dataTable no-footer dtr-inline"
                                    aria-describedby="datatable_info" style="width: 1169px;">
                                    <thead>
                                        <tr>
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="datatable"
                                                rowspan="1" colspan="1" style="width: 186.2px;"
                                                aria-sort="ascending"
                                                aria-label="Name: activate to sort column descending">Sr No</th>
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="datatable"
                                                rowspan="1" colspan="1" style="width: 186.2px;"
                                                aria-sort="ascending"
                                                aria-label="Name: activate to sort column descending">Client Name</th>
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="datatable"
                                                rowspan="1" colspan="1" style="width: 186.2px;"
                                                aria-sort="ascending"
                                                aria-label="Name: activate to sort column descending">Client Phone</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 279.2px;"
                                                aria-label="Position: activate to sort column ascending">Client Email</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 136.2px;"
                                                aria-label="Office: activate to sort column ascending">Client Address</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 129.2px;"
                                                aria-label="Start date: activate to sort column ascending">Action
                                            </th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        @forelse ($clients as $key => $client)
                                            <tr class="odd">
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="dtr-control sorting_1" tabindex="0">{{ $client->name }}</td>
                                                <td>{{ $client->phone }}</td>
                                                <td>{{ $client->email }}</td>
                                                <td>{{ $client->address }}</td>
                                                <td>
                                                    <a href="{{ route('admin.edit-client', ['id' => $client->id]) }}" class="text-primary">
                                                        <i class="mdi mdi-square-edit-outline" style="font-size: 25px"></i>
                                                    </a>
                                                    <a href="javascript:;" class="text-danger" id="deleteclientBtn" data-id="{{ $client->id }}">
                                                        <i class="mdi mdi-delete" style="font-size: 25px"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty

                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div>

@endsection

@push('custom-scripts')

<script>
    $(document).on('click', '#deleteProductBtn', function(e) {
        e.preventDefault();
        var url = "{{ route('admin.delete-client') }}";
        var token = "{{ csrf_token() }}";
        var product_id = $(this).data("id");

        Swal.fire({
            title: "Are you sure?",
            text: "You want to delete this product!",
            icon: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#2ab57d",
            cancelButtonColor: "#fd625e",
            confirmButtonText: "Yes, delete it!",
            allowOutsideClick: false,
        }).then(function (result) {
            if(result.value) {
                $.post(url, { _token:token, product_id:product_id }, function(response){
                    if(response.status == 1) {
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
                }, 'json');
            }
        });
    });
</script>

@endpush
