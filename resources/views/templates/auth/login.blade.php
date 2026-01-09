@extends('layouts.auth.auth')

@section('content')
<div class="login-container">
        <div class="card login-card shadow">
            <div class="login-body">
                <center>
                    <img src="{{ asset('assets/img/logo/logo_biovet.png') }}" class="logo-login">
                </center>
                <form id="loginForm" class="needs-validation" method="POST" action="{{ route('login.auth') }}" novalidate>
                	@csrf
                    <div class="mb-4">
                        <label for="username" class="form-label fw-bold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" class="form-control form-control-custom" 
                                   id="username" name="username" 
                                   placeholder="Enter your username" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control form-control-custom" 
                                   id="password" name="password" 
                                   placeholder="Enter your password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">
                                Remember me on this device
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-grid mb-3">
                        <button type="submit" onclick="clickLogin()" class="btn btn-primary-custom btn-custom login-btn shadow-unyama">
                            <i class="fas fa-sign-in-alt me-2"></i> Login to Dashboard
                        </button>
                    </div>
                    
                    <div class="text-center">
                        <a href="#" class="text-decoration-none text-primary" id="forgotPassword">
                            <i class="fas fa-key me-1"></i> Forgot Password?
                        </a>
                    </div>
                    
                </form>
            </div>
            <div class="login-footer text-center py-3">
                <small class="text-muted">
                    &copy; 2026 Biove Tech Ltd. All rights reserved.
                </small>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bg-transparent">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-white mt-3">Authenticating...</p>
                </div>
            </div>
        </div>
    </div>

@stop