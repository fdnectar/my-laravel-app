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

    <form action="{{ route('admin.update-invoice') }}" method="POST">
        @csrf
        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <x-form-alerts></x-form-alerts>

                        <div class="row">
                            <!-- Client Selection -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="client_id">Client</label>
                                    <select name="client_id" id="client_id" class="form-select">
                                        <option value="">Select Client</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}" 
                                                {{ $invoice->client_id == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> 

                            <!-- Invoice Number -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="invoice_number">Invoice Number</label>
                                    <input type="text" class="form-control" name="invoice_number" 
                                        id="invoice_number" value="{{ $invoice->invoice_number }}" readonly>
                                    @error('invoice_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> 

                            <!-- Invoice Date -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="invoice_date">Invoice Date</label>
                                    <input type="date" class="form-control" name="invoice_date" 
                                        id="invoice_date" value="{{ $invoice->invoice_date }}">
                                    @error('invoice_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Due Date -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="due_date">Due Date</label>
                                    <input type="date" class="form-control" name="due_date" 
                                        id="due_date" value="{{ $invoice->due_date }}">
                                    @error('due_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <hr>

                            <!-- Invoice Items -->
                            <div class="col-md-12">
                                <label class="form-label">Invoice Items</label>
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
                                        @foreach ($invoiceItems as $item)
                                            <tr>
                                                <td>
                                                    <input type="text" name="particular[]" class="form-control" 
                                                        value="{{ $item->particular }}">
                                                </td>
                                                <td>
                                                    <input type="number" name="quantity[]" class="form-control quantity" 
                                                        min="1" value="{{ $item->quantity }}">
                                                </td>
                                                <td>
                                                    <input type="number" name="unit_price[]" class="form-control unit_price" 
                                                        min="0" step="0.01" value="{{ $item->unit_price }}">
                                                </td>
                                                <td>
                                                    <input type="number" name="total_price[]" class="form-control total_price" 
                                                        value="{{ number_format($item->quantity * $item->unit_price, 2, '.', '') }}" readonly>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger remove-item">X</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <button type="button" id="add_item" class="btn btn-primary">Add Items</button>

                                <div class="mt-3">
                                    <h5>Total Amount: <span id="invoice_total">{{ number_format($invoice->total_amount, 2) }}</span></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-2">
            <button type="submit" class="btn btn-primary w-md">Update Invoice</button>
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
            let quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            let unitPrice = parseFloat(row.querySelector('.unit_price').value) || 0;
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
            
            // Update the total display with proper formatting
            invoiceTotal.textContent = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(total);
        }

        // Calculate initial totals on page load
        function initializeCalculations() {
            document.querySelectorAll('tr').forEach(row => {
                if (row.querySelector('.quantity') && row.querySelector('.unit_price')) {
                    calculateTotalPrice(row);
                }
            });
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
                <td><input type="number" name="unit_price[]" class="form-control unit_price" min="0" step="0.01" value="0.00"></td>
                <td><input type="number" name="total_price[]" class="form-control total_price" value="0.00" readonly></td>
                <td><button type="button" class="btn btn-danger remove-item">X</button></td>
            `;
            invoiceItemsBody.appendChild(newRow);
            calculateTotalPrice(newRow);
        });

        invoiceItemsBody.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-item')) {
                let row = event.target.closest('tr');
                row.remove();
                updateInvoiceTotal();
            }
        });

        // Run calculations on page load
        initializeCalculations();
    });


   
</script>

@endpush