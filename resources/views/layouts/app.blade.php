<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ZavaStock Inventory Management System">
    <meta name="author" content="Your Name">

    <title>{{ $title ?? 'ZavaStock' }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary-color: #5954eb;
            --secondary-color: #f8f9fc;
            --accent-color: #36b9cc;
            --dark-color: #5a5c69;
            --light-color: #f8f9fa;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            padding-top: 0;
            margin-top: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Layout Structure */
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
            min-height: 100vh;
        }

        /* Fixed Sidebar */
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: linear-gradient(180deg, var(--primary-color) 0%, #5954eb 100%);
            color: white;
            transition: all 0.3s;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        /* Main Content Area */
        #content {
            width: 100%;
            padding-left: 250px; /* Same as sidebar width */
            transition: all 0.3s;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .topbar {
            background: white !important;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }

        .navbar {
            background: white !important;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            position: sticky;
            top: 0;
            z-index: 800;
            height: 4.375rem;
        }

        .navbar .navbar-nav .nav-item .nav-link {
            color: var(--dark-color);
        }

        .navbar .navbar-nav .nav-item .nav-link:hover {
            color: var(--primary-color);
        }

        /* Sidebar Content */
        .sidebar-brand {
            padding: 1.5rem;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-brand-icon {
            font-size: 1.5rem;
        }

        .sidebar-brand-text {
            margin-left: 0.75rem;
        }

        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.5);
            margin: 0 1rem;
        }

        .sidebar-heading {
            padding: 0 1.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: white !important;
        }

        .sidebar .nav {
            padding: 0 1rem;
             color: white !important;
        }

        .sidebar .nav-item {
            position: relative;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: white !important;
            transition: all 0.3s;
            border-radius: 0.35rem;
        }

        /* Paksa semua teks sidebar agar putih */
        #sidebar,
        #sidebar .nav-link,
        #sidebar .nav-link span,
        #sidebar .nav-link i,
        #sidebar .sidebar-heading,
        #sidebar .collapse .nav-link,
        #sidebar .nav-item,
        .sidebar-footer {
            color: white !important;
        }

        #sidebar .nav-link.active,
        #sidebar .nav-link:hover,
        #sidebar .collapse .nav-link:hover,
        #sidebar .collapse .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white !important;
        }

        .sidebar .nav-link:hover {
            color: var(--primary-color) !important;
            background: rgba(255, 255, 255, 0.9);
        }

        .sidebar .nav-link.active {
            color: var(--primary-color) !important;
            background: rgba(255, 255, 255, 0.9);
        }

        .sidebar .nav-link i {
            margin-right: 0.5rem;
            font-size: 0.85rem;
            width: 20px;
            text-align: center;
           color: white !important;
        }

        .sidebar .nav-link .fa-caret-down {
            margin-left: auto;
            transition: transform 0.3s;
        }

        .sidebar .nav-link[aria-expanded="true"] .fa-caret-down {
            transform: rotate(180deg);
        }

        /* Dropdown Menus */
        .sidebar .collapse {
            margin: 0 0 0 1.5rem;
        }

        .sidebar .collapse .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            color: white !important;
        }

        .sidebar .collapse .nav-link:hover,
        .sidebar .collapse .nav-link.active {
            color: var(--primary-color) !important;
            background: rgba(255, 255, 255, 0.9);
        }

        /* Breadcrumbs */
        .breadcrumb-container {
            background-color: white;
            padding: 1rem;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
        }

        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin: 0;
        }

        .breadcrumb-item {
            font-size: 0.9rem;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.3s;
        }

        .breadcrumb-item a:hover {
            color: #5954eb;
            text-decoration: underline;
        }

        .breadcrumb-item.active {
            color: var(--dark-color);
            font-weight: 500;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: var(--dark-color);
            padding: 0 0.5rem;
        }

        /* Toggle Button */
        .sidebar-toggle {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 9999;
            background-color: var(--primary-color);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.3);
            cursor: pointer;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -200px;
            }
            #sidebar.active {
                margin-left: 0;
            }
            #content {
                padding-left: 0;
            }
            #content.active {
                padding-left: 250px;
            }
            .sidebar-toggle {
                display: flex;
            }
        }

        @media (min-width: 769px) {
            .sidebar-toggle {
                display: none;
            }
        }

        /* Footer */
        .footer {
            margin-top: auto;
            background-color: white;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 -0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar" class="active">
        <div class="sidebar-brand">
            <img src="{{ asset('images/assets/logo.jpg') }}" alt="ZavaStock." style="max-width: 100x; height: auto;">
        </div>
        
        <hr class="sidebar-divider my-0">
        
        <div class="sidebar-heading py-3">
            @auth
                {{ auth()->user()->role === 'admin' ? 'Admin Panel' : 'User Panel' }}
            @endauth
        </div>
        
        <div class="nav flex-column">
            <!-- Dashboard/Beranda -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ auth()->user()->role === 'admin' ? route('dashboard') : route('produk.index') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>{{ auth()->user()->role === 'admin' ? 'Dashboard' : 'Beranda' }}</span>
                </a>
            </li>

            <!-- Manajemen Produk -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('produk.*') ? 'active' : '' }}" href="{{ route('produk.index') }}">
                    <i class="fas fa-fw fa-box-open"></i>
                    <span>Manajemen Produk</span>
                </a>
            </li>

            <!-- Manajemen Produk Hilang -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('produk-hilang.*') ? 'active' : '' }}" href="{{ route('produk-hilang.index') }}">
                    <i class="fas fa-fw fa-exclamation-triangle"></i>
                    <span>Manajemen Produk Hilang</span>
                </a>
            </li>

            <!-- Menu khusus Admin -->
            @if(auth()->check() && auth()->user()->role === 'admin')
                <!-- Manajemen Pengguna -->
                <li class="nav-item">
                    <a class="nav-link {{ (request()->routeIs('admin.*') || request()->routeIs('customer.*') || request()->routeIs('supplier.*')) ? 'active' : '' }}" 
                       data-bs-toggle="collapse" 
                       href="#usersCollapse" 
                       aria-expanded="{{ (request()->routeIs('admin.*') || request()->routeIs('customer.*') || request()->routeIs('supplier.*')) ? 'true' : 'false' }}" 
                       aria-controls="usersCollapse">
                        <i class="fas fa-fw fa-users"></i>
                        <span>Manajemen Pengguna</span>
                        <i class="fas fa-fw fa-caret-down"></i>
                    </a>
                    <div id="usersCollapse" class="collapse {{ (request()->routeIs('admin.*') || request()->routeIs('customer.*') || request()->routeIs('supplier.*')) ? 'show' : '' }}">
                        <div class="nav flex-column">
                            <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.index') }}">
                                <i class="fas fa-fw fa-user-shield"></i>
                                <span>Admin</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('customer.*') ? 'active' : '' }}" href="{{ route('customer.index') }}">
                                <i class="fas fa-fw fa-user-tie"></i>
                                <span>Customer</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('supplier.*') ? 'active' : '' }}" href="{{ route('supplier.index') }}">
                                <i class="fas fa-fw fa-truck"></i>
                                <span>Supplier</span>
                            </a>
                        </div>
                    </div>
                </li>

                <!-- Manajemen Gudang -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('gudang.*') ? 'active' : '' }}" href="{{ route('gudang.index') }}">
                        <i class="fas fa-fw fa-warehouse"></i>
                        <span>Manajemen Gudang</span>
                    </a>
                </li>

                <!-- Manajemen Kategori -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kategori_produk.*') ? 'active' : '' }}" href="{{ route('kategori_produk.index') }}">
                        <i class="fas fa-fw fa-tags"></i>
                        <span>Kategori Produk</span>
                    </a>
                </li>

                <!-- Manajemen Stok -->
                <li class="nav-item">
                    <a class="nav-link {{ (request()->routeIs('stok-masuk.*') || request()->routeIs('stok-keluar.*') || request()->routeIs('stok-opname.*')) ? 'active' : '' }}" 
                       data-bs-toggle="collapse" 
                       href="#stokCollapse" 
                       aria-expanded="{{ (request()->routeIs('stok-masuk.*') || request()->routeIs('stok-keluar.*') || request()->routeIs('stok-opname.*')) ? 'true' : 'false' }}" 
                       aria-controls="stokCollapse">
                        <i class="fas fa-fw fa-boxes"></i>
                        <span>Manajemen Stok</span>
                        <i class="fas fa-fw fa-caret-down"></i>
                    </a>
                    <div id="stokCollapse" class="collapse {{ (request()->routeIs('stok-masuk.*') || request()->routeIs('stok-keluar.*') || request()->routeIs('stok-opname.*')) ? 'show' : '' }}">
                        <div class="nav flex-column">
                            <a class="nav-link {{ request()->routeIs('stok-masuk.*') ? 'active' : '' }}" href="{{ route('stok-masuk.index') }}">
                                <i class="fas fa-fw fa-arrow-down"></i>
                                <span>Stok Masuk</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('stok-keluar.*') ? 'active' : '' }}" href="{{ route('stok-keluar.index') }}">
                                <i class="fas fa-fw fa-arrow-up"></i>
                                <span>Stok Keluar</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('stok-opname.*') ? 'active' : '' }}" href="{{ route('stok-opname.index') }}">
                                <i class="fas fa-fw fa-clipboard-check"></i>
                                <span>Stok Opname</span>
                            </a>
                        </div>
                    </div>
                </li>

                <!-- Approvals - Only visible to admin -->
                <li class="nav-item">
                    <a class="nav-link {{ (request()->get('status') == 'pending') ? 'active' : '' }}" 
                       data-bs-toggle="collapse" 
                       href="#approvalsCollapse" 
                       aria-expanded="{{ (request()->get('status') == 'pending') ? 'true' : 'false' }}" 
                       aria-controls="approvalsCollapse">
                        <i class="fas fa-fw fa-check-circle"></i>
                        <span>Persetujuan</span>
                        <i class="fas fa-fw fa-caret-down"></i>
                    </a>
                    <div id="approvalsCollapse" class="collapse {{ (request()->get('status') == 'pending') ? 'show' : '' }}">
                        <div class="nav flex-column">
                            <a class="nav-link {{ (request()->routeIs('produk-hilang.*') && request()->get('status') == 'pending') ? 'active' : '' }}" 
                               href="{{ route('produk-hilang.index') }}?status=pending">
                                <i class="fas fa-fw fa-exclamation-triangle"></i>
                                <span>Produk Hilang</span>
                            </a>
                            <a class="nav-link {{ (request()->routeIs('stok-masuk.*') && request()->get('status') == 'pending') ? 'active' : '' }}" 
                               href="{{ route('stok-masuk.index') }}?status=pending">
                                <i class="fas fa-fw fa-arrow-down"></i>
                                <span>Stok Masuk</span>
                            </a>
                            <a class="nav-link {{ (request()->routeIs('stok-keluar.*') && request()->get('status') == 'pending') ? 'active' : '' }}" 
                               href="{{ route('stok-keluar.index') }}?status=pending">
                                <i class="fas fa-fw fa-arrow-up"></i>
                                <span>Stok Keluar</span>
                            </a>
                            <a class="nav-link {{ (request()->routeIs('stok-opname.*') && request()->get('status') == 'pending') ? 'active' : '' }}" 
                               href="{{ route('stok-opname.index') }}?status=pending">
                                <i class="fas fa-fw fa-clipboard-check"></i>
                                <span>Stok Opname</span>
                            </a>
                        </div>
                    </div>
                </li>
            @endif
        </div>
        
        <!-- Sidebar Footer -->
        <div class="sidebar-footer text-center py-3">
            <small class="text-white-50">ZavaStock v1.0</small>
        </div>
    </nav>

    <!-- Page Content -->
    <div id="content" class="active">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggle" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="{{ route('profile') }}">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>
                        <a class="dropdown-item" href="{{ route('settings') }}">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Breadcrumbs -->
            @if (Breadcrumbs::exists())
                <div class="breadcrumb-container">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            {{ Breadcrumbs::render() }}
                        </ol>
                    </nav>
                </div>
            @endif

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                @hasSection('page-actions')
                    <div class="page-actions">
                        @yield('page-actions')
                    </div>
                @endif
            </div>

            <!-- Main Content -->
            @yield('content')
        </div>
        <!-- /.container-fluid -->

        <!-- Footer -->
        <footer class="footer">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; ZavaStock {{ date('Y') }}</span>
                </div>
            </div>
        </footer>
    </div>
</div>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Toggle sidebar
    $(document).ready(function() {
        // Toggle sidebar on button click
        $('.sidebar-toggle, #sidebarToggle').click(function() {
            $('#sidebar').toggleClass('active');
            $('#content').toggleClass('active');
        });
        
        // Auto-close sidebar on mobile when clicking a link
        if ($(window).width() < 768) {
            $('#sidebar .nav-link:not([data-bs-toggle="collapse"])').click(function() {
                $('#sidebar').removeClass('active');
                $('#content').removeClass('active');
            });
        }
        
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });
        
        // Add active class to current route in sidebar
        const currentRoute = window.location.pathname;
        $('#sidebar .nav-link').each(function() {
            const linkRoute = $(this).attr('href');
            if (currentRoute === linkRoute || 
                (linkRoute !== '/' && currentRoute.startsWith(linkRoute))) {
                $(this).addClass('active');
            }
        });
        
        // Initialize Bootstrap collapse for sidebar dropdowns
        $('[data-bs-toggle="collapse"]').on('click', function() {
            const target = $(this).attr('href');
            $(target).collapse('toggle');
            
            // Rotate caret icon
            $(this).find('.fa-caret-down').toggleClass('rotate-180');
        });
    });
    
    // Resize event
    $(window).resize(function() {
        if ($(window).width() < 768) {
            if (!$('#sidebar').hasClass('active')) {
                $('#content').addClass('active');
            }
        } else {
            $('#sidebar').addClass('active');
            $('#content').addClass('active');
        }
    });
</script>

@stack('scripts')

</body>
</html>