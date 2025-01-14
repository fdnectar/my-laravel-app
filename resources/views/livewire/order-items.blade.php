<div>
    <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
        <div class="row">
            <div class="col-sm-12">
                <table id="datatable"
                    class="table table-bordered dt-responsive nowrap w-100 dataTable no-footer dtr-inline"
                    aria-describedby="datatable_info" style="width: 1169px;">
                    <thead>
                        <tr>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1"
                                colspan="1" style="width: 186.2px;" aria-sort="ascending"
                                aria-label="Name: activate to sort column descending">Sr No</th>
                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                                style="width: 279.2px;" aria-label="Position: activate to sort column ascending">
                                Order ID</th>
                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                                style="width: 136.2px;" aria-label="Office: activate to sort column ascending">Customer Name
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                                style="width: 116.2px;" aria-label="Salary: activate to sort column ascending">Status
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                                style="width: 116.2px;" aria-label="Salary: activate to sort column ascending">Order Date
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                                style="width: 116.2px;" aria-label="Salary: activate to sort column ascending">Products
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                                style="width: 116.2px;" aria-label="Salary: activate to sort column ascending">Payment Status
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                                style="width: 116.2px;" aria-label="Salary: activate to sort column ascending">Check Out Date
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                                style="width: 65.2px;" aria-label="Age: activate to sort column ascending">Phone Number</th>
                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                                style="width: 129.2px;" aria-label="Start date: activate to sort column ascending">Shipping Address</th>
                        </tr>
                    </thead>


                    <tbody>

                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->user->name ?? 'Guest' }}</td>
                                <td>
                                    <span class="badge badge-text
                                        @if($order->status === 'completed') bg-success-subtle text-success
                                        @elseif($order->status === 'cart') bg-info-subtle text-info
                                        @elseif($order->status === 'pending') bg-warning-subtle text-warning
                                        @elseif($order->status === 'cancelled') bg-danger-subtle text-danger
                                        @else bg-secondary-subtle text-secondary
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                <td>
                                    <ul>
                                        @foreach ($order->items as $item)
                                            <li>{{ $item->product->product_name }} (x{{ $item->quantity }}) - ₹{{ $item->subtotal }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    @if($order->payment)
                                        <ul>
                                            <li>
                                                @if($order->payment->payment_method == 'Debit Card')
                                                    @if($order->payment->status == 'success')
                                                        <span class="badge badge-text bg-success-subtle text-success">Completed</span>
                                                    @else
                                                        <span class="badge badge-text bg-danger-subtle text-danger">Payment Failed</span>
                                                    @endif
                                                @elseif($order->payment->payment_method == 'Cash on Delivery')
                                                    @if($order->payment->status == 'pending')
                                                        <span class="badge badge-text bg-warning-subtle text-warning">Pending (COD)</span>
                                                    @else
                                                        <span class="badge badge-text bg-success-subtle text-success">Payment Done (COD)</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-text bg-secondary-subtle text-secondary">No Payment Method</span>
                                                @endif
                                            </li>
                                        </ul>
                                    @else
                                        <ul>
                                            <li><span class="badge badge-text bg-secondary-subtle text-secondary">Product is in cart</span></li>
                                        </ul>
                                    @endif
                                </td>
                                <td>
                                    <ul>
                                        <li>
                                            @if($order->checked_out_at)
                                                {{ $order->checked_out_at->format('d M Y, h:i A') }}
                                            @else
                                                N/A
                                            @endif
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <li>{{ $order->phone_number }}</li>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <li>{{ $order->shipping_address }}</li>
                                    </ul>
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
