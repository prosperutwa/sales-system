@extends('layouts.app.app')
@include('partials.side-nav')
@section('content')
<div class="container" id="container-main">
    <div class="page-actions" id="pageActions">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-header border-top border-primary border-0 bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Invoices</h6>
                <button class="btn btn-sm btn-primary-custom" data-bs-toggle="collapse" data-bs-target="#addUser">
                    Add User
                </button>
            </div>
            <div class="collapse bg-light p-3" id="addUser">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-4"><input name="first_name" class="form-control form-control-sm" placeholder="First Name" required></div>
                        <div class="col-md-4"><input name="last_name" class="form-control form-control-sm" placeholder="Last Name" required></div>
                        <div class="col-md-4"><input name="phonenumber" class="form-control form-control-sm" placeholder="Phone" required></div>

                        <div class="col-md-3"><input name="username" class="form-control form-control-sm" placeholder="Username" required></div>
                        <div class="col-md-3"><input name="email" class="form-control form-control-sm" placeholder="Email" required></div>
                        <div class="col-md-3"><input type="password" name="password" class="form-control form-control-sm" placeholder="Password" required></div>
                        <div class="col-md-3">
                            <select name="role" class="form-select form-select-sm">
                                <option value="admin">Admin</option>
                                <option value="seller">Staff</option>
                            </select>
                        </div>
                    </div>

                    <button class="btn btn-sm btn-success mt-2">Save User</button>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">                    
                    <table class="table table-bordered table-hover" id="tableList">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th width="160">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $key => $user)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $user->full_name }}</td>
                                <td>{{ $user->auth->username }}</td>
                                <td>{{ ucfirst($user->role) }}</td>
                                <td>
                                    <span class="badge {{ $user->status=='active'?'bg-success':'bg-secondary' }}">
                                        {{ $user->status }}
                                    </span>
                                </td>
                                <td>
                                 @if($currentAuth->user_id !== $user->auto_id)
                                 <a href="{{ route('users.toggle', $user->auto_id) }}"
                                     class="btn btn-sm {{ $user->status === 'active' ? 'btn-success' : 'btn-secondary' }}"
                                     title="{{ $user->status === 'active' ? 'Deactivate User' : 'Activate User' }}">

                                     <i class="fas fa-toggle-{{ $user->status === 'active' ? 'on' : 'off' }}"></i>
                                 </a>


                                 <button class="btn btn-sm btn-info"
                                 onclick="openPasswordModal({{ $user->auth->auto_id }})">
                                 <i class="fas fa-key"></i>
                             </button>


                             <form action="{{ route('users.destroy', $user->auto_id) }}" method="POST" class="d-inline deleteUserForm">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger deleteUserBtn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @else
                            <a href="{{ route('profile.index') }}" class="btn btn-sm btn-primary">
                                Go to profile
                            </a>
                            @endif


                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="passwordModal">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('users.changePassword') }}" class="modal-content">
            @csrf
            <input type="hidden" name="auth_id" id="authId">
            <div class="modal-header">
                <h6>Change Password</h6>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="password" name="password" class="form-control mb-2" placeholder="New Password" required>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success btn-sm">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPasswordModal(authId){
        document.getElementById('authId').value = authId;
        new bootstrap.Modal(document.getElementById('passwordModal')).show();
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.deleteUserBtn').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.deleteUserForm');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you really want to delete this user?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

@stop

