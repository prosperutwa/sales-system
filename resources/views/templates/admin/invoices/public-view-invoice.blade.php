@extends('layouts.app.app')

@section('content')

<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 13px;
        background: #f4f6f8;
        margin: 0;
        padding: 0;
    }

    .invoice-container {
        width: 850px;
        margin: 30px auto;
        background: #ffffff;
        padding: 20px;
        border-radius: 6px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    .header-image img {
        width: 100%;
        margin-bottom: 15px;
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .company-info h4 {
        margin: 0 0 5px 0;
        font-size: 16px;
    }

    .company-info p,
    .customer-info p {
        margin: 2px 0;
        line-height: 1.4;
    }

    .customer-info {
        text-align: right;
    }

    .invoice-meta {
        margin: 15px 0;
    }

    .invoice-title {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .status {
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 12px;
        display: inline-block;
        color: #fff;
    }

    .status.paid { background: #28a745; }
    .status.unpaid { background: #6c757d; }
    .status.cancelled { background: #dc3545; }

    .content-invoice table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .content-invoice table th,
    .content-invoice table td {
        border: 1px solid #ddd;
        padding: 4px;
    }

    .content-invoice table th {
        background: #f2f2f2;
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    .totals-table {
        width: 300px;
        margin-left: auto;
    }

    .totals-table td {
        border: none;
        padding: 5px 0;
    }

    .totals-table tr:last-child td {
        font-weight: bold;
        border-top: 1px solid #ddd;
        padding-top: 8px;
    }

    .qr-section {
        text-align: center;
        margin-top: 25px;
    }

    .qr-section p {
        margin-top: 8px;
        font-size: 12px;
        color: #555;
    }

    @media print {
        body {
            background: #ffffff;
        }

        .invoice-container {
            width: 100%;
            margin: 0;
            box-shadow: none;
            border-radius: 0;
        }
    }
</style>

<div class="invoice-container">

    <div class="header-image">
        <table width="100%" style="text-align:center; border-bottom: 2px solid black;">
            <tr>
                <td style="width: 20%;">
                    <center><img src="{{ asset('assets/img/logo/logo_biovet.png') }}" width="30%"></center>
                </td>
                <td style="width:60%">
                    <h2 style="text-transform: uppercase;">{{ $company->company_name ?? 'BIOVET TECHNOLOGY LIMITED' }}</h2>
                    <h6>{{ $company->company_address ?? '' }}</h6>
                    <h6>Phone: {{ $company->company_phone ?? '' }} | Email: {{ $company->company_email ?? '' }}</h6>
                </td>
                <td style="width:20%">
                    <center><img src="{{ asset('assets/img/logo/logo_biovet.png') }}" width="30%"></center>
                </td>
            </tr>
        </table>
    </div>

    <div class="invoice-header" style="margin-top: 10px;">
        <table width="100%">
            <tr>
                <td style="width:40%">
                    <h5 style="text-transform: capitalize;">{{ $company->company_name ?? 'Company Name' }}</h5>
                    <p>
                        <b>Address</b>{{ $company->company_address ?? '' }} <br>
                        <b>TIN:</b> 187-710-855<br>
                        <b>VRN:</b> N/R<br>
                        <b>Phone:</b>{{ $company->company_phone ?? '' }} <br>
                        <b>Email:</b>{{ $company->company_email ?? '' }}
                    </p>
                </td>
                <td style="width:20%">
                 <div class="qr-code">
                     {!! $qrCode !!}
                 </div>   
             </td>
             <td style="width:40%">

                <div style="display: flex; justify-content: left;">
                    <table width="100%" style="margin-left: auto; margin-right: 0;">
                        <tr>
                            <td colspan="2"><h6 style="font-weight: bold;">Invoice To:</h6></td>
                        </tr>
                        <tr>
                            <td><b>Contact Person:</b></td>
                            <td>{{ $invoice->customer->full_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><b>Company Name:</b></td>
                            <td>{{ $invoice->customer->company_name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td><b>Phone:</b></td>
                            <td>{{ $invoice->customer->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><b>Email:</b></td>
                            <td>{{ $invoice->customer->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><b>Tin Number:</b></td>
                            <td>{{ $invoice->customer->tin_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><b>Vat Number</b></td>
                            <td>{{ $invoice->customer->vat_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><b>Date:</b></td>
                            <td>{{ $invoice->created_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td><b>Status:</b></td>
                            <td>
                                @php
                                $statusClass = match($invoice->status) {
                                    'unpaid'   => 'badge bg-secondary',
                                    'paid'     => 'badge bg-success',
                                    'cancelled' => 'badge bg-danger',
                                    default    => 'badge bg-light',
                                };
                                @endphp
                                <span class="{{ $statusClass }}">{{ ucfirst($invoice->status) }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</div>

<div class="invoice-meta">
    <div class="invoice-title">
        Invoice #{{ str_pad($invoice->auto_id, 4, '0', STR_PAD_LEFT) }}
    </div>
</div>
<div class="content-invoice">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Price (Tsh)</th>
                <th>Qty</th>
                <th>Subtotal (Tsh)</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($invoice->items as $key => $item)
            @php
            $subtotal = $item->quantity * $item->product->selling_price;
            $total += $subtotal;
            @endphp
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->product->name ?? '-' }}</td>
                <td class="text-right">{{ number_format($item->product->selling_price, 2) }}</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<table class="totals-table">
    <tr>
        <td>Total</td>
        <td class="text-right">Tsh {{ number_format($total, 2) }}</td>
    </tr>
    <tr>
        <td>Discount</td>
        <td class="text-right">Tsh {{ number_format($invoice->discount_amount, 2) }}</td>
    </tr>
    <tr>
        <td>Amount Due</td>
        <td class="text-right">Tsh {{ number_format($invoice->total_amount, 2) }}</td>
    </tr>
</table>

<div class="content-invoice">
    @if($invoice->status === 'paid')
    <span style="font-weight: bold;">Payments</span>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Method</th>
                <th>Reference</th>
                <th>Amount (Tsh)</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoice->payments as $key => $payment)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ ucfirst($payment->payment_method) }}</td>
                <td>{{ $payment->reference_number ?? '-' }}</td>
                <td class="text-right">{{ number_format($payment->amount_paid, 2) }}</td>
                <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;">No payments found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @endif
</div>

<div class="row">
    <div class="col-md-6">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th colspan="2" style="text-align:center;">Payment Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b>Account Name: </b></td>
                    <td>BIOVET TECHNOLOGY LIMITED</td>
                </tr>
                <tr>
                    <td><b>NMB Bank: (TZS)</b></td>
                    <td>AC 23510094380</td>
                </tr>
                <tr>
                    <td><b>Lipa Number: Vodacom</b></td>
                    <td>350298588</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

@stop
