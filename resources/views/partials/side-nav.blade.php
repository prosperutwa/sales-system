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
            
            <div class="dropdown d-none d-lg-block">
                <div class="d-flex align-items-center p-0 text-white" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar me-2">
                        <span>{{ substr(strtoupper($currentUser->first_name), 0,1).''.substr(strtoupper($currentUser->last_name), 0,1) }}</span>
                    </div>
                    <div class="d-flex flex-column me-2">
                        <span class="fw-bold">{{ $currentUser->first_name }} {{ $currentUser->last_name }}</span>
                        <small class="opacity-75">{{ $currentUser->role }}</small>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <ul class="dropdown-menu user-dropdown border-0 shadow" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href=""><i class="fas fa-user-circle me-2"></i> Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal" id="logoutBtn"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
            </div>
            
            <div class="d-lg-none d-flex align-items-center">
                <div class="dropdown me-3">
                    <div class="d-flex align-items-center p-0 text-white" type="button" id="mobileUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">
                            <span>{{ substr(strtoupper($currentUser->first_name), 0,1).''.substr(strtoupper($currentUser->last_name), 0,1) }}</span>
                        </div>
                    </div>
                    <ul class="dropdown-menu user-dropdown border-0 shadow" aria-labelledby="mobileUserDropdown">
                        <li class="px-3 py-2">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-2">
                                    <span>{{ substr(strtoupper($currentUser->first_name), 0,1).''.substr(strtoupper($currentUser->last_name), 0,1) }}</span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $currentUser->first_name }} {{ $currentUser->last_name }}</span>
                                    <small class="opacity-75">{{ $currentUser->role }}</small>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href=""><i class="fas fa-user-circle me-2"></i> Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal" id="logoutBtn"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
                
                <button class="navbar-toggler text-white border-0 p-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavbar" aria-controls="mobileNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars fs-4"></i>
                </button>
            </div>
        </div>
    </div>
</header>

<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-unyama-sm d-none d-lg-block">
    <div class="container">
        <div class="navbar-nav flex-row flex-wrap justify-content-center w-100">
            <a class="nav-link nav-link-custom active" href="{{ route('admin.dashboard') }}" data-page="dashboard">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
            <a class="nav-link nav-link-custom" href="{{ route('products') }}" data-page="products">
                <i class="fas fa-shopping-bag me-2"></i> Products
            </a>
            <a class="nav-link nav-link-custom" href="{{ route('customers') }}" data-page="customers">
                <i class="fas fa-users me-2"></i> Customers
            </a>
            <a class="nav-link nav-link-custom" href="" data-page="invoices">
                <i class="fas fa-file-invoice me-2"></i> Invoices
            </a>
            <a class="nav-link nav-link-custom" href="" data-page="users">
                <i class="fas fa-user-shield me-2"></i> Users
            </a>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-start d-lg-none border-0" tabindex="-1" id="mobileNavbar" aria-labelledby="mobileNavbarLabel">
    <div class="offcanvas-header border-bottom">
        <div class="d-flex align-items-center w-100">
            <div class="user-avatar me-3">
                <span>{{ substr(strtoupper($currentUser->first_name), 0,1).''.substr(strtoupper($currentUser->last_name), 0,1) }}</span>
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
            <a class="list-group-item list-group-item-action border-0 py-3 px-4 active" href="{{ route('admin.dashboard') }}" data-page="dashboard">
                <i class="fas fa-tachometer-alt me-3"></i> Dashboard
            </a>
            <a class="list-group-item list-group-item-action border-0 py-3 px-4" href="{{ route('products') }}" data-page="products">
                <i class="fas fa-shopping-bag me-3"></i> Products
            </a>
            <a class="list-group-item list-group-item-action border-0 py-3 px-4" href="{{ route('customers') }}" data-page="customers">
                <i class="fas fa-users me-3"></i> Customers
            </a>
            <a class="list-group-item list-group-item-action border-0 py-3 px-4" href="" data-page="invoices">
                <i class="fas fa-file-invoice me-3"></i> Invoices
            </a>
            <a class="list-group-item list-group-item-action border-0 py-3 px-4" href="" data-page="users">
                <i class="fas fa-user-shield me-3"></i> Users
            </a>
            <div class="border-top mt-3">
                <a class="list-group-item list-group-item-action border-0 py-3 px-4" href="">
                    <i class="fas fa-user-circle me-3"></i> Profile
                </a>
                <a class="list-group-item list-group-item-action border-0 py-3 px-4 text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal" id="logoutBtn">
                    <i class="fas fa-sign-out-alt me-3"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>
