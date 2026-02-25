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
                    <table width="100%" style="text-align:center; border-bottom: 2px solid black;">
                        <tr>
                            <td style="width: 20%;">
                                <center><img src="{{ asset('assets/img/logo/logo_biovet.png') }}" width="40%"></center>
                            </td>
                            <td style="width:60%">
                                <h1 style="text-transform: uppercase;">{{ $company->company_name ?? 'BIOVET TECHNOLOGY LIMITED' }}</h1>
                                <h6>{{ $company->company_address ?? '' }}</h6>
                                <h6>Phone: {{ $company->company_phone ?? '' }} | Email: {{ $company->company_email ?? '' }}</h6>
                            </td>
                            <td style="width:20%">
                                <center><img src="{{ asset('assets/img/logo/logo_biovet.png') }}" width="40%"></center>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-12" style="margin-top: 10px;">
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
                                    <table width="70%" style="margin-left: auto; margin-right: 0;">
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
            
            <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="2" class="text-center">Payment Details</th>
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

            <div class="text-end">
                <a href="{{ route('invoices.print', $invoice->auto_id) }}" class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Print</a>
                <a href="{{ route('invoices.download', $invoice->auto_id) }}" class="btn btn-sm btn-secondary"><i class="fas fa-download"></i> Download</a>
            </div>

        </div>
    </div>
</div>
@stop
