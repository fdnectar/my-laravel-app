<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function index(Request $request) {
        $data = [
            'pageTitle' => 'All Invoice',
            'invoices' => Invoice::all()
        ];

        return view('back.pages.invoice', $data);
    }

    public function create(Request $request) {
        $lastInvoice = Invoice::latest('id')->first();
        $nextInvoiceNumber = $lastInvoice ? ($lastInvoice->invoice_number + 1) : 1001;
        $data = [
            'pageTitle' => 'Create Invoice',
            'clients' => Client::all(),
            'invoiceNumber' => $nextInvoiceNumber,
        ];

        return view('back.pages.create-invoice', $data);
    }

    public function storeInvoice(Request $request) {
        // Validation outside try-catch
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'invoice_number' => 'required',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'particular' => 'required|array',
            'particular.*' => 'required|string',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric|min:0',
            'total_price' => 'required|array',
            'total_price.*' => 'required|numeric|min:0',
        ]);
        
        try {
            $invoice = Invoice::create([
                'client_id' => $request->client_id,
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'status' => 'pending',
                'total_amount' => array_sum($request->total_price),
            ]);
        
            foreach ($request->particular as $key => $particular) {
                $item = InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'particular' => $particular,
                    'quantity' => $request->quantity[$key],
                    'unit_price' => $request->unit_price[$key],
                    'total_price' => $request->total_price[$key],
                ]);
            }
        
            return redirect()->route('admin.all-invoices')->with('success', 'Invoice Created Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create invoice: ' . $e->getMessage())->withInput();
        }
    }

    public function downloadPDF($id)
    {
        $invoice = Invoice::with(['invoiceItems', 'getClient'])->findOrFail($id);

        $pdf = Pdf::loadView('back.pdf.invoice-pdf', compact('invoice'));

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function editInvoice(Request $request) {
        $invoice_id = $request->id;
        $invoice = Invoice::with('invoiceItems')->findOrFail($invoice_id);
        $clients = Client::all();

        $data = [
            'pageTitle' => 'Edit Invoice',
            'invoice' => $invoice,
            'clients' => $clients,
            'invoiceItems' => $invoice->invoiceItems,
        ];

        return view('back.pages.edit-invoice', $data);
    }

    public function updateInvoice(Request $request) {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::findOrFail($invoice_id);

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'invoice_number' => 'required|unique:invoices,invoice_number,' . $invoice_id,
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'particular' => 'required|array',
            'particular.*' => 'required|string',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric|min:0',
            'total_price' => 'required|array',
            'total_price.*' => 'required|numeric|min:0',
        ]);

        try {
            $invoice->update([
                'client_id' => $request->client_id,
                'invoice_date' => $request->invoice_date,
                'invoice_number' => $request->invoice_number,
                'due_date' => $request->due_date,
                'total_amount' => array_sum($request->total_price),
            ]);

            InvoiceItem::where('invoice_id', $invoice_id)->delete();

            foreach ($request->particular as $key => $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'particular' => $item,
                    'quantity' => $request->quantity[$key],
                    'unit_price' => $request->unit_price[$key],
                    'total_price' => $request->total_price[$key],
                ]);
            }
            return redirect()->route('admin.all-invoices')->with('success', 'Invoice Updated Successfully');
        } catch(\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update invoice: ' . $e->getMessage())->withInput();
        }   
    }
    
}
