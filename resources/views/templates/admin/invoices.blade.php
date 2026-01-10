@extends('layouts.app.app')
@include('partials.side-nav')

@section('content')
<div class="container" id="container-main">
    <div class="page-actions" id="pageActions">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-header border-top border-primary border-0 bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Invoices</h6>
                <button class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
                    Create New Invoice
                </button>
            </div>

            <div class="card-body">
                <div class="tab-content" id="invoiceTabsContent">
                    <div class="tab-pane fade show active" id="invoice-list" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tableList">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Invoice No</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Discount</th>
                                        <th>Total After Discount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $key => $invoice)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>#{{ str_pad($invoice->auto_id, 4, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $invoice->customer->full_name ?? '-' }}</td>
                                        <td>Tsh {{ number_format($invoice->subtotal,2) }}</td>
                                        <td>Tsh {{ number_format($invoice->discount_amount,2) }}</td>
                                        <td>Tsh {{ number_format($invoice->total_amount,2) }}</td>
                                        <td>
                                            @php
                                            $statusClass = match($invoice->status) {
                                                'unpaid'   => 'bg-secondary',
                                                'paid'     => 'bg-success',
                                                'cancelled' => 'bg-danger',
                                                default    => 'bg-light',
                                            };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="mt-1 float-end text-nowrap">
                                                @if($invoice->status == "unpaid")
                                                <a href="javascript:void(0)"
                                                onclick="openPaymentModal({{ $invoice->auto_id }}, {{ $invoice->auto_id }}, {{ $invoice->total_amount }})"
                                                class="btn btn-sm btn-success" title="Pay">
                                                <i class="fas fa-credit-card"></i>
                                            </a>


                                            <a href="javascript:void(0)" 
                                            class="btn btn-sm btn-danger cancelInvoiceBtn" 
                                            data-id="{{ $invoice->auto_id }}" 
                                            title="Cancel Invoice">
                                            <i class="fas fa-times"></i>
                                        </a>

                                        <form id="cancelInvoiceForm" class="d-none" method="POST" action="{{ route('invoices.canceled', 0) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="invoice_id" id="cancelInvoiceId">
                                        </form>

                                        @endif
                                        <a href="{{ route('invoices.view', $invoice->auto_id) }}" class="btn btn-sm btn-warning" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('invoices.download', $invoice->auto_id) }}" class="btn btn-sm btn-info" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <a href="{{ route('invoices.print', $invoice->auto_id) }}" class="btn btn-sm btn-primary" title="Print" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
</div>

<div class="modal fade" id="createInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title">Create Invoice</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Customer</label>
                        <select class="form-select form-select-sm" name="customer_id" id="customerSelect" required>
                            <option value="">-- Select Customer --</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->auto_id }}">{{ $customer->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="invoiceProductsContainer"></div>

                    <div class="mb-3 mt-3">
                        <button type="button" class="btn btn-sm btn-success" id="addProductRowBtn">Add Product</button>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Discount</label>
                        <input type="number" step="0.01" name="discount" id="invoiceDiscount" class="form-control form-control-sm" value="0">
                    </div>

                    <div class="mt-2">
                        <p>Total: Tsh <span id="invoiceTotal">0.00</span></p>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom btn-custom btn-sm">Create Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="paymentForm" action="{{ route('invoices.pay') }}">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title">Pay Invoice <span id="modalInvoiceNo"></span></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="invoice_id" id="paymentInvoiceId">

                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select id="paymentMethod" name="method" class="form-select form-select-sm" required>
                            <option value="">-- Select Method --</option>
                            <option value="cash">Cash</option>
                            <option value="mobile">Mobile</option>
                            <option value="bank">Bank</option>
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="referenceContainer">
                        <label class="form-label">Reference No</label>
                        <input type="text" id="referenceNo" name="reference_no" class="form-control form-control-sm">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount: Tsh <span id="amount_here">0.00</span></label>
                        <input type="hidden" step="0.01" min="0.01" class="form-control" name="amount" id="paymentAmount" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm">Pay</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openPaymentModal(invoiceId, invoiceNo, totalAmount) {
    document.getElementById('paymentInvoiceId').value = invoiceId;
    document.getElementById('modalInvoiceNo').innerText = '#' + String(invoiceNo).padStart(4, '0');

    const amountInput = document.getElementById('paymentAmount');
    amountInput.value = totalAmount;
    document.getElementById('amount_here').innerText = totalAmount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    const methodSelect = document.getElementById('paymentMethod');
    methodSelect.value = '';

    const referenceContainer = document.getElementById('referenceContainer');
    referenceContainer.classList.add('d-none');
    document.getElementById('referenceNo').value = '';

    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();
}

document.getElementById('paymentMethod').addEventListener('change', function() {
    const refContainer = document.getElementById('referenceContainer');
    const refInput = document.getElementById('referenceNo');

    if (this.value === 'cash' || this.value === '') {
        refContainer.classList.add('d-none');
        refInput.value = '';
    } else {
        refContainer.classList.remove('d-none');
        refInput.focus();
    }
});
</script>



<style>
    .tom-select {
        width: 100%;
    }

    .ts-control {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        min-height: calc(1.5em + 0.5rem + 2px);
    }

    .ts-dropdown {
        font-size: 0.875rem;
    }

    .ts-dropdown .option {
        padding: 0.5rem;
    }

    .product-row {
        margin-bottom: 0.5rem;
        align-items: center;
    }

    .remove-btn {
        margin-top: 0.5rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let productsData = {};
        let rowIndex = 0;
    let tomSelectInstances = {}; // Store TomSelect instances

    // Initialize TomSelect for customer
    new TomSelect('#customerSelect', {
        create: false,
        sortField: { field: "text", direction: "asc" },
        placeholder: '-- Select Customer --',
        searchPlaceholder: 'Search customers...'
    });

    // On customer change: fetch products
    $('#customerSelect').on('change', function () {
        const customerId = $(this).val();
        if (!customerId) return;

        $.ajax({
            url: '/admin/products/json',
            dataType: 'json',
            success: function(data) {
                productsData = data;
                $('#invoiceProductsContainer').html('');
                rowIndex = 0;
                tomSelectInstances = {}; // Clear previous instances
                updateInvoiceTotal();
            }
        });
    });

    // Add product row
    $('#addProductRowBtn').on('click', function () {
        if (!Object.keys(productsData).length) {
            alert('Please select customer first');
            return;
        }

        const rowId = 'row' + rowIndex;
        
        // Prepare options for TomSelect
        const productOptions = [];
        
        // Add placeholder option
        productOptions.push({
            value: '',
            text: '-- Select Product --',
            disabled: true
        });

        // Add available products
        Object.values(productsData).forEach(p => {
            if (p.remain_quantity > 0) {
                productOptions.push({
                    value: p.auto_id,
                    text: `${p.name} (Stock: ${p.remain_quantity}) - Tsh ${Number(p.selling_price).toLocaleString()}`,
                    stock: p.remain_quantity,
                    price: p.selling_price
                });
            }
        });

        const html = `
        <div class="row product-row" id="${rowId}">
            <div class="col-md-6">
                <select class="product-select" 
                        name="products[${rowIndex}][product_id]" 
                        id="productSelect${rowIndex}" 
                        required>
                    <option value="">-- Select Product --</option>
                    ${productOptions.slice(1).map(p => 
                        `<option value="${p.value}" data-stock="${p.stock}" data-price="${p.price}">${p.text}</option>`
                        ).join('')}
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" 
                       min="1" 
                       max="1" 
                       class="form-control form-control-sm product-quantity" 
                       name="products[${rowIndex}][quantity]" 
                       placeholder="Quantity" 
                       required>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-sm btn-danger remove-btn removeProductBtn">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
        </div>
                    `;

                    $('#invoiceProductsContainer').append(html);

        // Initialize TomSelect for the new product select
                    const productSelect = document.getElementById(`productSelect${rowIndex}`);

                    if (productSelect) {
                        const tomSelect = new TomSelect(productSelect, {
                            create: false,
                            sortField: { field: "text", direction: "asc" },
                            placeholder: '-- Select Product --',
                            searchPlaceholder: 'Search products...',
                            valueField: 'value',
                            labelField: 'text',
                            searchField: ['text'],
                            options: productOptions,
                            render: {
                                option: function(data, escape) {
                                    return `
                            <div class="d-flex justify-content-between">
                                <div>${escape(data.text)}</div>
                                <div class="text-muted">
                                    Stock: ${data.stock || 0}
                                </div>
                            </div>
                                    `;
                                },
                                item: function(data, escape) {
                                    const parts = data.text.split(' - ');
                                    return `<div>${escape(parts[0])}</div>`;
                                }
                            },
                            onChange: function(value) {
                                if (value) {
                                    const selectedOption = this.options[value];
                                    if (selectedOption) {
                                        const row = $(this.input).closest('.product-row');
                                        const quantityInput = row.find('.product-quantity');

                            // Set max attribute based on stock
                                        quantityInput.attr('max', selectedOption.stock);

                            // Reset quantity if current quantity exceeds stock
                                        const currentQty = parseInt(quantityInput.val()) || 0;
                                        if (currentQty > selectedOption.stock) {
                                            quantityInput.val(selectedOption.stock);
                                        }

                            // If no quantity set, set to 1
                                        if (!quantityInput.val()) {
                                            quantityInput.val(1);
                                        }
                                    }
                                }
                                updateInvoiceTotal();
                            }
                        });

            // Store the instance
                        tomSelectInstances[rowIndex] = tomSelect;
                    }

                    rowIndex++;
                    updateInvoiceTotal();
                });

    // Remove product row
$('#invoiceProductsContainer').on('click', '.removeProductBtn', function () {
    const row = $(this).closest('.product-row');
    const selectId = row.find('.product-select').attr('id');
    const rowNumber = selectId.replace('productSelect', '');

        // Destroy TomSelect instance
    if (tomSelectInstances[rowNumber]) {
        tomSelectInstances[rowNumber].destroy();
        delete tomSelectInstances[rowNumber];
    }

    row.remove();
    updateInvoiceTotal();
});

    // Quantity input change
$('#invoiceProductsContainer').on('input', '.product-quantity', function () {
    const row = $(this).closest('.product-row');
    const select = row.find('.product-select');
    const selectedValue = select.val();

    if (!selectedValue) {
        alert('Please select a product first');
        $(this).val('');
        return;
    }

        // Find the TomSelect instance
    const selectId = select.attr('id');
    const instance = tomSelectInstances[selectId.replace('productSelect', '')];

    if (instance) {
        const selectedOption = instance.options[selectedValue];
        if (selectedOption) {
            const quantity = parseInt($(this).val()) || 0;
            const stock = parseInt(selectedOption.stock) || 0;

            if (quantity > stock) {
                alert('Quantity exceeds available stock');
                $(this).val(stock);
            }

            if (quantity < 1) {
                $(this).val(1);
            }
        }
    }

    updateInvoiceTotal();
});

    // Discount input change
$('#invoiceDiscount').on('input', function () {
    updateInvoiceTotal();
});

function updateInvoiceTotal() {
    let total = 0;

    $('#invoiceProductsContainer .product-row').each(function () {
        const select = $(this).find('.product-select');
        const selectedValue = select.val();

        if (selectedValue) {
            const quantity = parseInt($(this).find('.product-quantity').val()) || 0;

                // Get price from TomSelect instance or data attribute
            const selectId = select.attr('id');
            const instance = tomSelectInstances[selectId.replace('productSelect', '')];

            if (instance && instance.options[selectedValue]) {
                const price = parseFloat(instance.options[selectedValue].price) || 0;
                total += quantity * price;
            } else {
                    // Fallback to data attribute
                const price = parseFloat(select.find('option:selected').data('price') || 0);
                total += quantity * price;
            }
        }
    });

    const discount = parseFloat($('#invoiceDiscount').val()) || 0;
    const finalTotal = Math.max(0, total - discount);

    $('#invoiceTotal').text(finalTotal.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));
}
});

document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.cancelInvoiceBtn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const invoiceId = this.dataset.id;
            const invoiceNo = invoiceId.toString().padStart(4, '0');

            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to cancel invoice no #${invoiceNo}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    // set the invoice id in hidden form
                    const form = document.getElementById('cancelInvoiceForm');
                    form.action = form.action.replace(/0$/, invoiceId); // replace 0 with actual id
                    document.getElementById('cancelInvoiceId').value = invoiceId;
                    form.submit();
                }
            });
        });
    });

});

</script>
@stop