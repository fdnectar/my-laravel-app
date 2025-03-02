@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : '')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Add Invoice</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Add Invoice</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.store-invoice') }}" method="POST">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <x-form-alerts></x-form-alerts>
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Client</label>
                                    <select name="client_id" id="client_id" class="form-select">
                                        <option value="">Select Client</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Invoice Number</label>
                                    <input type="text" class="form-control" name="invoice_number" id="invoice_number" value="{{ $invoiceNumber }}" readonly>
                                    @error('invoice_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="invoice_date">Invoice Date</label>
                                    <input type="date" class="form-control" name="invoice_date" id="invoice_date" value="{{ date('Y-m-d') }}">
                                    @error('invoice_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="due_date">Due Date</label>
                                    <input type="date" class="form-control" name="due_date" id="due_date">
                                    @error('due_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="col-md-12">
                                <label class="form-label" for="due_date">Invoice Items</label>
                                <table class="table table-bordered" id="invoice_items_table">
                                    <thead>
                                        <tr>
                                            <th>Particular</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoice_items_body">
                                        <tr>
                                            <td><input type="text" name="particular[]" class="form-control"></td>
                                            <td><input type="number" name="quantity[]" class="form-control quantity" min="1" value="1"></td>
                                            <td><input type="number" name="unit_price[]" class="form-control unit_price" min="0" step="0.01"></td>
                                            <td><input type="number" name="total_price[]" class="form-control total_price" readonly></td>
                                            <td><button type="button" class="btn btn-danger remove-item">X</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" id="add_item" class="btn btn-primary">Add Items</button>

                                <div class="mt-3">
                                    <h5>Total Amount: <span id="invoice_total">0.00</span></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <button type="submit" class="btn btn-primary w-md">Create Invoice</button>
        </div>
    </form>


@endsection

@push('custom-scripts')

<script>
    // document.addEventListener("DOMContentLoaded", function() {
    //     flatpickr("#invoice_date", {
    //         dateFormat: "Y-m-d",
    //         defaultDate: "today",
    //         allowInput: false
    //     });
    // });

    document.querySelector('form').addEventListener('submit', function(e) {
        let emptyTotals = false;
        
        document.querySelectorAll('.total_price').forEach(field => {
            if (!field.value || field.value == '0' || field.value == '0.00') {
                const row = field.closest('tr');
                const quantity = row.querySelector('.quantity').value || 0;
                const unitPrice = row.querySelector('.unit_price').value || 0;
                field.value = (quantity * unitPrice).toFixed(2);
                
                if (!field.value || field.value == '0' || field.value == '0.00') {
                    emptyTotals = true;
                }
            }
        });
        
        if (emptyTotals) {
            console.log('Some total prices are still empty or zero');
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        let invoiceItemsBody = document.getElementById('invoice_items_body');
        let addItemButton = document.getElementById('add_item');
        let invoiceTotal = document.getElementById('invoice_total');

        function calculateTotalPrice(row) {
            let quantity = row.querySelector('.quantity').value;
            let unitPrice = row.querySelector('.unit_price').value;
            let totalPriceField = row.querySelector('.total_price');
            let total = (quantity * unitPrice).toFixed(2);
            totalPriceField.value = total;
            updateInvoiceTotal();
        }

        function updateInvoiceTotal() {
            let total = 0;
            document.querySelectorAll('.total_price').forEach(item => {
                total += parseFloat(item.value) || 0;
            });
            invoiceTotal.innerText = total.toFixed(2);
        }

        invoiceItemsBody.addEventListener('input', function (event) {
            if (event.target.classList.contains('quantity') || event.target.classList.contains('unit_price')) {
                let row = event.target.closest('tr');
                calculateTotalPrice(row);
            }
        });

        addItemButton.addEventListener('click', function () {
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="text" name="particular[]" class="form-control"></td>
                <td><input type="number" name="quantity[]" class="form-control quantity" min="1" value="1"></td>
                <td><input type="number" name="unit_price[]" class="form-control unit_price" min="0" step="0.01"></td>
                <td><input type="number" name="total_price[]" class="form-control total_price" readonly></td>
                <td><button type="button" class="btn btn-danger remove-item">X</button></td>
            `;
            invoiceItemsBody.appendChild(newRow);
        });

        invoiceItemsBody.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-item')) {
                let row = event.target.closest('tr');
                row.remove();
                updateInvoiceTotal();
            }
        });
    });

   
</script>

@endpush