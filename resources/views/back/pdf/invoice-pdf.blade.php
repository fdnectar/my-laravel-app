<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ddd; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h2>Invoice #{{ $invoice->invoice_number }}</h2>
        <p><strong>Client:</strong> {{ $invoice->getClient->name }}</p>
        <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</p>
        <p><strong>Due Date:</strong> {{ $invoice->due_date }}</p>

        <h3>Invoice Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Particular</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->invoiceItems as $item)
                    <tr>
                        <td>{{ $item->particular }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3>Total Amount: ₹ {{ number_format($invoice->invoiceItems->sum('total_price'), 2) }}</h3>
    </div>
</body>
</html>
