@extends('layouts.app.app')
@include('partials.side-nav')

@section('content')
<div class="container w-75 my-4" id="container-main">

    <div class="card border-0 shadow-sm rounded">
        <div class="card-header border-top border-primary border-0 bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Invoice #{{ str_pad($invoice->auto_id, 4, '0', STR_PAD_LEFT) }}</h6>
            <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-12">
                    <img src="{{ asset('assets/img/header/header.png') }}" width="100%">
                </div>
                <div class="col-md-6">
                    <h5>{{ $company->company_name ?? 'Company Name' }}</h5>
                    <p>
                        {{ $company->company_address ?? '' }} <br>
                        Phone: {{ $company->company_phone ?? '' }} <br>
                        Email: {{ $company->company_email ?? '' }}
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <h6>Invoice To:</h6>
                    <p>
                        {{ $invoice->customer->full_name ?? '-' }} <br>
                        {{ $invoice->customer->company_name ?? '' }} <br>
                        <b>Phone</b>: {{ $invoice->customer->phone ?? '-' }} <br>
                        <b>Email</b>: {{ $invoice->customer->email ?? '-' }}<br>
                        <b>Tin Number</b>: {{ $invoice->customer->tin_number ?? '-' }}<br>
                        <b>Vat Number</b>: {{ $invoice->customer->vat_number ?? '-' }}
                    </p>
                    <p><strong>Date:</strong> {{ $invoice->created_at->format('d M Y') }}</p>
                    <p><strong>Status:</strong> 
                        @php
                        $statusClass = match($invoice->status) {
                            'unpaid'   => 'badge bg-secondary',
                            'paid'     => 'badge bg-success',
                            'cancelled' => 'badge bg-danger',
                            default    => 'badge bg-light',
                        };
                        @endphp
                        <span class="{{ $statusClass }}">{{ ucfirst($invoice->status) }}</span>
                    </p>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Price (Tsh)</th>
                            <th>Quantity</th>
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
                            <td>{{ number_format($item->product->selling_price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row mb-4">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th>Total:</th>
                            <td>Tsh {{ number_format($total, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Discount:</th>
                            <td>Tsh {{ number_format($invoice->discount_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Amount Due:</th>
                            <td><strong>Tsh {{ number_format($invoice->total_amount, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($invoice->status == "paid")
            <h6>Payments</h6>
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead class="table-light">
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
                            <td>{{ number_format($payment->amount_paid, 2) }}</td>
                            <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No payments yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif
            <center>
                <div class="qr-code">
                    {!! $qrCode !!}
                </div>
            </center>


            <div class="text-end">
                <a href="{{ route('invoices.print', $invoice->auto_id) }}" class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Print</a>
                <a href="{{ route('invoices.download', $invoice->auto_id) }}" class="btn btn-sm btn-secondary"><i class="fas fa-download"></i> Download</a>
            </div>

        </div>
    </div>
</div>
@stop
