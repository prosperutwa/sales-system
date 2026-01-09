@extends('layouts.app.app')
@include('partials.side-nav')

@section('content')
<div class="container" id="container-main">
    <div class="page-actions" id="pageActions">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-header border-top border-primary border-0 bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Products</h6>

                <button class="btn btn-sm btn-primary-custom btn-custom"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#addProductCollapse"
                aria-expanded="false"
                aria-controls="addProductCollapse">
                Add Product
            </button>
        </div>

        <div class="card-body">
            <div class="collapse bg-light p-2 mb-4" id="addProductCollapse">
                <form action="{{ route('products.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control form-control-sm" placeholder="Enter Product Name" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Buying Price</label>
                            <input type="text" class="form-control form-control-sm price-format" data-target="buying_price" placeholder="0" autocomplete="off" required>
                            <input type="hidden" name="buying_price" id="buying_price">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Selling Price</label>
                            <input type="text" class="form-control form-control-sm price-format" data-target="selling_price" placeholder="0"autocomplete="off" required>
                            <input type="hidden" name="selling_price" id="selling_price">
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">Stock Quantity</label>
                            <input type="number" name="stock_quantinty" placeholder="Enter Stock Quantity" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control form-control-sm" placeholder="Optional.." rows="3"></textarea>
                        </div>
                        <div class="col-md-6 mt-3">
                            <button type="submit" class="btn btn-primary-custom btn-custom btn-sm">
                                Save Product
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tableList">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Buying</th>
                            <th>Selling</th>
                            <th>Stock Available</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $key => $product)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $product->name }}</td>
                            <td>Tsh {{ number_format($product->buying_price,2) }}</td>
                            <td>Tsh {{ number_format($product->selling_price,2) }}</td>
                            <td>{{ $product->remain_quantity }}</td>
                            <td class="text-nowrap float-end">
                                <a href="javascript:void(0)"
                                class="btn btn-sm btn-primary"
                                onclick="openEditModal(
                                   {{ $product->auto_id }},
                                   '{{ e($product->name) }}',
                                   '{{ $product->buying_price }}',
                                   '{{ $product->selling_price }}',
                                   '{{ $product->stock_quantinty }}',
                                   '{{ e($product->description) }}'
                                   )">
                                   <i class="fas fa-pen"></i> Edit
                               </a>
                               <a href="javascript:void(0)"
                               class="btn btn-sm btn-success"
                               onclick="openAddQtyModal(
                                {{ $product->auto_id }},
                                '{{ e($product->name) }}'
                                )">
                                <i class="fas fa-cart-plus"></i> Add Qnt
                            </a>

                            <a href="javascript:void(0)"
                            class="btn btn-sm btn-danger"
                            onclick="confirmDelete({{ $product->auto_id }})">
                            <i class="fas fa-trash"></i> Delete
                        </a>

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
</div>

<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('products.update') }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h6 class="modal-title">Edit Product</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="auto_id" id="edit_auto_id">

                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Buying Price</label>
                            <input type="number" step="0.01" name="buying_price" id="edit_buying_price" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Selling Price</label>
                            <input type="number" step="0.01" name="selling_price" id="edit_selling_price" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-6 d-none">
                            <label class="form-label">Stock Quantity</label>
                            <input type="number" name="stock_quantinty" id="edit_stock_quantinty" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="edit_description" class="form-control form-control-sm" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom btn-custom btn-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="addQtyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('products.addQty') }}">
                @csrf

                <input type="hidden" name="product_id" id="addQtyProductId">

                <div class="modal-header">
                    <h6 class="modal-title">
                        Add Quantity - <span id="addQtyProductName"></span>
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">New Quantity</label>
                    <input type="number"
                    name="quantity"
                    class="form-control form-control-sm"
                    min="1"
                    required>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success">
                        Update Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<form id="deleteProductForm" method="POST" action="{{ route('products.delete') }}">
    @csrf
    @method('DELETE')
    <input type="hidden" name="auto_id" id="delete_auto_id">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.querySelectorAll('.price-format').forEach(input => {
            input.addEventListener('input', function () {

                let raw = this.value
                .replace(/,/g, '')
                .replace(/[^\d.]/g, '');

                const target = document.getElementById(this.dataset.target);

                if (!raw) {
                    target.value = '';
                    this.value = '';
                    return;
                }

                target.value = raw;

                let parts = raw.split('.');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                this.value = parts.join('.');
            });
        });

    });

    function openEditModal(id, name, buying, selling, stock, description) {

        document.getElementById('edit_auto_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_buying_price').value = buying;
        document.getElementById('edit_selling_price').value = selling;
        document.getElementById('edit_stock_quantinty').value = stock;
        document.getElementById('edit_description').value = description ?? '';

        const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
        modal.show();
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This product will be permanently deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete_auto_id').value = id;
                document.getElementById('deleteProductForm').submit();
            }
        });
    }

    function openAddQtyModal(productId, productName) {
        document.getElementById('addQtyProductId').value = productId;
        document.getElementById('addQtyProductName').innerText = productName;

        let modal = new bootstrap.Modal(document.getElementById('addQtyModal'));
        modal.show();
    }
</script>

@stop
