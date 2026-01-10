<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Systems\BiovetTechInvoice;
use App\Models\Systems\BiovetTechPayment;
use App\Models\Systems\BiovetTechProduct;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Systems\BiovetTechCustomer;
use App\Models\Systems\BiovetTechInvoiceItem;
use App\Models\Systems\BiovetTechCompanySetting;

class InvoicesController extends Controller
{
    public function index()
    {
        // Load all invoices with customer info
        $invoices = BiovetTechInvoice::with('customer')->latest()->get();
        $customers = BiovetTechCustomer::all();
        return view('templates.admin.invoices', compact('invoices', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:biovet_tech_customers,auto_id',
            'products'    => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:biovet_tech_products,auto_id',
            'products.*.quantity'   => 'required|integer|min:1',
            'discount'    => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function() use ($request) {

            $subtotal = 0;

            foreach ($request->products as $p) {
                $product = BiovetTechProduct::findOrFail($p['product_id']);
                if ($p['quantity'] > $product->remain_quantity) {
                    abort(400, "Quantity for {$product->name} exceeds available stock.");
                }
                $subtotal += $product->selling_price * $p['quantity'];
            }

            $discount = $request->discount ?? 0;
            $total = $subtotal - $discount;

            $invoice = BiovetTechInvoice::create([
                'customer_id' => $request->customer_id,
                'user_id'     => session('biovet_user_id'),
                'invoice_date'=> now(),
                'subtotal'    => $subtotal,
                'tax_amount'  => 0,
                'discount_amount' => $discount,
                'total_amount'=> $total,
                'status'      => 'unpaid',
            ]);

            foreach ($request->products as $p) {
                $product = BiovetTechProduct::findOrFail($p['product_id']);
                BiovetTechInvoiceItem::create([
                    'invoice_id' => $invoice->auto_id,
                    'product_id' => $product->auto_id,
                    'quantity'   => $p['quantity'],
                    'unit_price' => $product->selling_price,
                    'total_price'=> $product->selling_price * $p['quantity'],
                ]);

                // Update remaining stock
                $product->decrement('remain_quantity', $p['quantity']);
            }
        });

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function cancel(Request $request, $id)
    {
        $invoice = BiovetTechInvoice::findOrFail($id);

        if ($invoice->status !== 'unpaid') {
            return redirect()->back()->with('error', 'Only unpaid invoices can be canceled.');
        }

        $invoice->update([
            'status' => 'cancelled',
        ]);

        return redirect()->back()->with('success', 'Invoice #' . str_pad($invoice->auto_id, 4, '0', STR_PAD_LEFT) . ' has been canceled.');
    }

    public function pay(Request $request)
    {
        $request->validate([
            'invoice_id'     => 'required|exists:biovet_tech_invoices,auto_id',
            'method'         => 'required|in:cash,mobile,bank',
            'reference_no'   => 'nullable|string|max:255',
            'amount'         => 'required|numeric|min:0.01',
        ]);

        if ($request->method !== 'cash' && empty($request->reference_no)) {
            return redirect()->back()->with('error', 'Reference number is required for non-cash payments.');
        }

        $invoice = BiovetTechInvoice::findOrFail($request->invoice_id);

        DB::transaction(function() use ($invoice, $request) {
            BiovetTechPayment::create([
                'invoice_id'     => $invoice->auto_id,
                'payment_method' => $request->method,
                'reference_no'   => $request->reference_no,
                'payment_date'   => now(),
                'amount_paid'         => $request->amount,
            ]);

            $totalPaid = $invoice->payments()->sum('amount_paid');
            $invoiceTotal = $invoice->total_amount - $invoice->discount_amount;

            if ($totalPaid >= $invoiceTotal) {
                $invoice->update(['status' => 'paid']);
            }
        });

        return redirect()->back()->with('success', 'Payment recorded successfully.');
    }

    public function view($invoiceId)
    {
     $invoice = BiovetTechInvoice::with([
        'customer',
        'items.product',
        'payments'
    ])->findOrFail($invoiceId);

     $company = BiovetTechCompanySetting::first();

     $invoiceNo = '#' . str_pad($invoice->auto_id, 4, '0', STR_PAD_LEFT);

       $qrCode = QrCode::size(180)
    ->margin(2)
    ->errorCorrection('H')
    ->generate($invoiceNo);


     return view('templates.admin.view-invoice', compact(
        'invoice',
        'company',
        'qrCode',
        'invoiceNo'
    ));
 }

 public function print($invoiceId)
 {
    $invoice = BiovetTechInvoice::with(['customer', 'items.product', 'payments'])->findOrFail($invoiceId);
    $company = BiovetTechCompanySetting::first();

    $invoiceNo = '#' . str_pad($invoice->auto_id, 4, '0', STR_PAD_LEFT);

       $qrCode = QrCode::size(180)
    ->margin(2)
    ->errorCorrection('H')
    ->generate($invoiceNo);

    $action = "print";

    return view('templates.admin.invoices.print', compact('invoice', 'company','qrCode', 'action'));
}

public function download($invoiceId)
{
    $invoice = BiovetTechInvoice::with(['customer', 'items.product', 'payments'])->findOrFail($invoiceId);
    $company = BiovetTechCompanySetting::first();

    $invoiceNo = '#' . str_pad($invoice->auto_id, 4, '0', STR_PAD_LEFT);

       $qrCode = QrCode::size(180)
    ->margin(2)
    ->errorCorrection('H')
    ->generate($invoiceNo);

    $action = "download";

    return view('templates.admin.invoices.print', compact('invoice', 'company','qrCode', 'action'));
}

}
