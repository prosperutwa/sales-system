<header class="header-bg py-3 shadow-unyama-lg">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <div class="user-avatar shadow-unyama-sm">
                        <img src="{{ asset('assets/img/logo/logo_biovet.png') }}" width="30px">
                    </div>
                </div>
                <h1 class="logo-text mb-0 d-none d-md-block">Biovet Technology Ltd</h1>
                <h1 class="logo-text mb-0 d-md-none">Biovet</h1>
            </div>

            <!-- Desktop User Dropdown -->
            <div class="dropdown d-none d-lg-block">
                <div class="d-flex align-items-center p-0 text-white" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar me-2">
                        <span>{{ substr(strtoupper($currentUser->first_name),0,1).substr(strtoupper($currentUser->last_name),0,1) }}</span>
                    </div>
                    <div class="d-flex flex-column me-2">
                        <span class="fw-bold">{{ $currentUser->first_name }} {{ $currentUser->last_name }}</span>
                        <small class="opacity-75">{{ $currentUser->role }}</small>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <ul class="dropdown-menu user-dropdown border-0 shadow" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fas fa-user-circle me-2"></i> Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="{{ route('logout') }}" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
            </div>

            <!-- Mobile User & Navbar Toggle -->
            <div class="d-lg-none d-flex align-items-center">
                <div class="dropdown me-3">
                    <div class="d-flex align-items-center p-0 text-white" type="button" id="mobileUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">
                            <span>{{ substr(strtoupper($currentUser->first_name),0,1).substr(strtoupper($currentUser->last_name),0,1) }}</span>
                        </div>
                    </div>
                    <ul class="dropdown-menu user-dropdown border-0 shadow" aria-labelledby="mobileUserDropdown">
                        <li class="px-3 py-2">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-2">
                                    <span>{{ substr(strtoupper($currentUser->first_name),0,1).substr(strtoupper($currentUser->last_name),0,1) }}</span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $currentUser->first_name }} {{ $currentUser->last_name }}</span>
                                    <small class="opacity-75">{{ $currentUser->role }}</small>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fas fa-user-circle me-2"></i> Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="{{ route('logout') }}" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
                <button class="navbar-toggler text-white border-0 p-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavbar" aria-controls="mobileNavbar">
                    <i class="fas fa-bars fs-4"></i>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Desktop Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-unyama-sm d-none d-lg-block">
    <div class="container">
        <div class="navbar-nav flex-row flex-wrap justify-content-center w-100">
            <a class="nav-link nav-link-custom {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
            <a class="nav-link nav-link-custom {{ request()->routeIs('products') ? 'active' : '' }}" href="{{ route('products') }}">
                <i class="fas fa-shopping-bag me-2"></i> Products
            </a>
            <a class="nav-link nav-link-custom {{ request()->routeIs('customers') ? 'active' : '' }}" href="{{ route('customers') }}">
                <i class="fas fa-users me-2"></i> Customers
            </a>
            <a class="nav-link nav-link-custom {{ request()->routeIs('invoices.*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">
                <i class="fas fa-file-invoice me-2"></i> Invoices
            </a>
            @if($currentUser->role == "admin")
            <a class="nav-link nav-link-custom {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i class="fas fa-user-shield me-2"></i> Users
            </a>
            @endif
        </div>
    </div>
</nav>

<!-- Mobile Offcanvas Navbar -->
<div class="offcanvas offcanvas-start d-lg-none border-0" tabindex="-1" id="mobileNavbar" aria-labelledby="mobileNavbarLabel">
    <div class="offcanvas-header border-bottom">
        <div class="d-flex align-items-center w-100">
            <div class="user-avatar me-3">
                <span>{{ substr(strtoupper($currentUser->first_name),0,1).substr(strtoupper($currentUser->last_name),0,1) }}</span>
            </div>
            <div class="d-flex flex-column">
                <h5 class="offcanvas-title fw-bold mb-0" id="mobileNavbarLabel">{{ $currentUser->first_name }} {{ $currentUser->last_name }}</h5>
                <small class="text-muted">{{ $currentUser->role }}</small>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="list-group list-group-flush">
            <a class="list-group-item list-group-item-action border-0 py-3 px-4 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt me-3"></i> Dashboard
            </a>
            <a class="list-group-item list-group-item-action border-0 py-3 px-4 {{ request()->routeIs('products') ? 'active' : '' }}" href="{{ route('products') }}">
                <i class="fas fa-shopping-bag me-3"></i> Products
            </a>
            <a class="list-group-item list-group-item-action border-0 py-3 px-4 {{ request()->routeIs('customers') ? 'active' : '' }}" href="{{ route('customers') }}">
                <i class="fas fa-users me-3"></i> Customers
            </a>
            <a class="list-group-item list-group-item-action border-0 py-3 px-4 {{ request()->routeIs('invoices.*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">
                <i class="fas fa-file-invoice me-3"></i> Invoices
            </a>
            @if($currentUser->role == "admin")
            <a class="list-group-item list-group-item-action border-0 py-3 px-4 {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i class="fas fa-user-shield me-3"></i> Users
            </a>
            @endif

            <div class="border-top mt-3">
                <a class="list-group-item list-group-item-action border-0 py-3 px-4" href="{{ route('profile.index') }}">
                    <i class="fas fa-user-circle me-3"></i> Profile
                </a>
                <a class="list-group-item list-group-item-action border-0 py-3 px-4 text-danger" href="{{ route('logout') }}" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class="fas fa-sign-out-alt me-3"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>
