@extends('layouts.app.app')
@include('partials.side-nav')

@section('content')
<div class="container" id="container-main">
    <div class="page-actions" id="pageActions">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-header border-top border-primary border-0 bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Customers</h6>

                <button class="btn btn-sm btn-primary-custom"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#addCustomerCollapse"
                aria-expanded="false"
                aria-controls="addCustomerCollapse">
                Add Customer
            </button>
        </div>

        <div class="card-body">
            <div class="collapse bg-light p-2 mb-4" id="addCustomerCollapse">
                <form action="{{ route('customers.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control form-control-sm" placeholder="Enter Full Name" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control form-control-sm" placeholder="Enter Phone Number" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control form-control-sm" placeholder="Optional">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control form-control-sm" placeholder="Optional">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">TIN Number</label>
                            <input type="text" name="tin_number" class="form-control form-control-sm" placeholder="Optional">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control form-control-sm" placeholder="Optional" rows="2"></textarea>
                        </div>

                        <div class="col-md-6 mt-3">
                            <button type="submit" class="btn btn-primary-custom btn-custom btn-sm">
                                Save Customer
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
                            <th>Full Name</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Email</th>
                            <th>TIN</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $key => $customer)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $customer->full_name }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->company_name ?? 'N/A'}}</td>
                            <td>{{ $customer->email ?? 'N/A' }}</td>
                            <td>{{ $customer->tin_number ?? 'N/A' }}</td>
                            <td class="text-nowrap">
                                <button type="button"
                                class="btn btn-sm btn-primary editCustomerBtn"
                                data-id="{{ $customer->auto_id }}"
                                data-full_name="{{ $customer->full_name }}"
                                data-phone="{{ $customer->phone }}"
                                data-company="{{ $customer->company_name }}"
                                data-email="{{ $customer->email }}"
                                data-address="{{ $customer->address }}"
                                data-tin="{{ $customer->tin_number }}">
                                <i class="fas fa-pen"></i> Edit
                            </button>



                            <a href="javascript:void(0)"
                            class="btn btn-sm btn-danger"
                            onclick="confirmDeleteCustomer({{ $customer->auto_id }})">
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

<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('customers.update') }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="auto_id" id="editCustomerId">

                <div class="modal-header">
                    <h6 class="modal-title">Edit Customer</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" id="edit_full_name" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" id="edit_company_name" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">TIN Number</label>
                            <input type="text" name="tin_number" id="edit_tin_number" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" id="edit_address" class="form-control form-control-sm" rows="2"></textarea>
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

<form id="deleteCustomerForm" method="POST" action="{{ route('customers.delete') }}">
    @csrf
    @method('DELETE')
    <input type="hidden" name="auto_id" id="deleteCustomerId">
</form>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.body.addEventListener('click', function (e) {

            const btn = e.target.closest('.editCustomerBtn');
            if (!btn) return;

            document.getElementById('editCustomerId').value = btn.dataset.id;
            document.getElementById('edit_full_name').value = btn.dataset.full_name ?? '';
            document.getElementById('edit_phone').value = btn.dataset.phone ?? '';
            document.getElementById('edit_company_name').value = btn.dataset.company ?? '';
            document.getElementById('edit_email').value = btn.dataset.email ?? '';
            document.getElementById('edit_address').value = btn.dataset.address ?? '';
            document.getElementById('edit_tin_number').value = btn.dataset.tin ?? '';

            new bootstrap.Modal(
                document.getElementById('editCustomerModal')
                ).show();
        });

    });
</script>


<script>

    function confirmDeleteCustomer(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This customer will be deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteCustomerId').value = id;
                document.getElementById('deleteCustomerForm').submit();
            }
        });
    }
</script>

@stop
