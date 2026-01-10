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

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table th,
    table td {
        border: 1px solid #ddd;
        padding: 8px;
    }

    table th {
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
        <img src="{{ asset('assets/img/header/header.png') }}">
    </div>

    <div class="invoice-header">
        <div class="company-info">
            <h4>{{ $company->company_name ?? 'Company Name' }}</h4>
            <p>{{ $company->company_address ?? '' }}</p>
            <p>Phone: {{ $company->company_phone ?? '' }}</p>
            <p>Email: {{ $company->company_email ?? '' }}</p>
        </div>

        <div class="customer-info">
            <p><strong>Invoice To</strong></p>
            <p>{{ $invoice->customer->full_name ?? '-' }}</p>
            <p>{{ $invoice->customer->company_name ?? '' }}</p>
            <p>Phone: {{ $invoice->customer->phone ?? '-' }}</p>
            <p>Email: {{ $invoice->customer->email ?? '-' }}</p>
            <p><strong>Date:</strong> {{ $invoice->created_at->format('d M Y') }}</p>

            <span class="status {{ $invoice->status }}">
                {{ ucfirst($invoice->status) }}
            </span>
        </div>
    </div>

    <div class="invoice-meta">
        <div class="invoice-title">
            Invoice #{{ str_pad($invoice->auto_id, 4, '0', STR_PAD_LEFT) }}
        </div>
    </div>

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

    @if($invoice->status === 'paid')
        <h4>Payments</h4>
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
                        <td>{{ $payment->reference_no ?? '-' }}</td>
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

    <div class="qr-section">
        {!! $qrCode !!}
    </div>

</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

<script>
async function generatePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'mm', 'a4');
    
    const element = document.querySelector('.invoice-container');
    
    const canvas = await html2canvas(element, {
        scale: 2,
        useCORS: true,
        logging: false,
        backgroundColor: '#ffffff'
    });
    
    const imgData = canvas.toDataURL('image/jpeg', 1.0);
    
    const pageWidth = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();
    
    const imgWidth = pageWidth - 20; 
    const imgHeight = (canvas.height * imgWidth) / canvas.width;
    
    doc.addImage(imgData, 'JPEG', 10, 10, imgWidth, imgHeight);
    
    doc.save(`invoice_{{ str_pad($invoice->auto_id, 4, '0', STR_PAD_LEFT) }}.pdf`);

    setTimeout(()=>{
        window.history.back();
    }, 100);
}
</script>


@if($action == "print")
<script>
    window.print();
</script>
@else
<script>
    generatePDF()
</script>
@endif

@stop
