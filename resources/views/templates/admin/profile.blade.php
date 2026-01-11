@extends('layouts.app.app')
@include('partials.side-nav')
@section('content')
<div class="container" id="container-main">
	<div class="page-actions" id="pageActions">
		<div class="card border-0 shadow-sm rounded">
			<div class="card-header border-top border-primary border-0 bg-white d-flex justify-content-between align-items-center">
				<h6 class="mb-0">Profile</h6>
				<div>
					<button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
						 Edit Profile
					</button>
					<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
						 Change Password
					</button>
				</div>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="card mb-4 border-light">
							<div class="card-header bg-light">
								<h5 class="mb-0">
									<i class="fas fa-user me-2"></i>Personal Information
								</h5>
							</div>
							<div class="card-body">
								<div class="row mb-3">
									<div class="col-sm-4 fw-bold text-muted">First Name:</div>
									<div class="col-sm-8">{{ $user->first_name }}</div>
								</div>
								<div class="row mb-3">
									<div class="col-sm-4 fw-bold text-muted">Last Name:</div>
									<div class="col-sm-8">{{ $user->last_name }}</div>
								</div>
								<div class="row mb-3">
									<div class="col-sm-4 fw-bold text-muted">Email:</div>
									<div class="col-sm-8">
										<i class="fas fa-envelope me-2 text-muted"></i>{{ $user->email }}
									</div>
								</div>
								<div class="row">
									<div class="col-sm-4 fw-bold text-muted">Phone:</div>
									<div class="col-sm-8">
										<i class="fas fa-phone me-2 text-muted"></i>{{ $user->phonenumber }}
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="card mb-4 border-light">
							<div class="card-header bg-light">
								<h5 class="mb-0">
									<i class="fas fa-shield-alt me-2"></i>Auth Information
								</h5>
							</div>
							<div class="card-body">
								<div class="row mb-3">
									<div class="col-sm-4 fw-bold text-muted">Username:</div>
									<div class="col-sm-8">
										<i class="fas fa-user-tag me-2 text-muted"></i>{{ $auth->username ?? '-' }}
									</div>
								</div>
								<div class="row mb-3">
									<div class="col-sm-4 fw-bold text-muted">Role:</div>
									<div class="col-sm-8">
										<i class="fas fa-user-shield me-2 text-muted"></i>{{ $user->role ?? '-' }}
									</div>
								</div>
								<div class="row">
									<div class="col-sm-4 fw-bold text-muted">Status:</div>
									<div class="col-sm-8">
										@if($auth->status ?? false)
										<span class="badge bg-{{ $auth->status === 1 ? 'success' : 'warning' }}">
											Active
										</span>
										@else
										-
										@endif
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<form method="POST" action="{{ route('profile.update') }}">
					@csrf
					<div class="modal-header">
						<h5 class="modal-title">
							Edit Profile
						</h5>
						<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6 mb-3">
								<label class="form-label">First Name</label>
								<input type="text" class="form-control" name="first_name" value="{{ $user->first_name }}" required>
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label">Last Name</label>
								<input type="text" class="form-control" name="last_name" value="{{ $user->last_name }}" required>
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label">Email</label>
								<input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label">Phone Number</label>
								<input type="text" class="form-control" name="phonenumber" value="{{ $user->phonenumber }}" required>
							</div>
							<div class="col-md-12 mb-3">
								<label class="form-label">Username</label>
								<input type="text" class="form-control" name="username" value="{{ $auth->username ?? '' }}" required>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
							Cancel
						</button>
						<button type="submit" class="btn btn-success btn-sm">
							Save Changes
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-md modal-dialog-centered">
			<div class="modal-content">
				<form method="POST" action="{{ route('profile.change-password') }}">
					@csrf
					<div class="modal-header">
						<h5 class="modal-title">
							Change Password
						</h5>
						<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">New Password (8-12 characters)</label>
							<input type="password" class="form-control" name="password" placeholder="Enter new password" required>
						</div>
						<div class="mb-3">
							<label class="form-label">Confirm Password</label>
							<input type="password" class="form-control" name="password_confirmation" placeholder="Confirm new password" required>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
							Cancel
						</button>
						<button type="submit" class="btn btn-success btn-sm">
							Change Password
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	@stop



