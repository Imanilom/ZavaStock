<!-- resources/views/layouts/sidebar.blade.php -->
<div class="sidebar bg-dark text-white" id="sidebar" style="position: fixed; top: 56px; bottom: 0; left: 0; width: 250px; padding-top: 20px;">
    <h3 class="text-center">
        @auth
            {{ auth()->user()->admin() ? 'Admin Panel' : 'Customer Panel' }}
        @endauth
    </h3>
    
    <ul class="nav flex-column">
        <!-- Dashboard/Beranda -->
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ auth()->user()->admin() ? route('admin.index') : route('produk.index') }}">
                {{ auth()->user()->admin() ? 'Dashboard' : 'Beranda' }}
            </a>
        </li>

        <!-- Manajemen Produk (Semua pengguna) -->
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('produk.index') }}">Manajemen Produk</a>
        </li>

        <!-- Manajemen Produk Hilang (Semua pengguna) -->
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('produk-hilang.index') }}">Manajemen Produk Hilang</a>
        </li>

        <!-- Menu khusus Admin -->
        @if(auth()->check() && auth()->user()->admin())
            <!-- Manajemen Pengguna -->
            <li class="nav-item dropdown">
                <a class="nav-link text-white dropdown-toggle" href="#" id="usersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Manajemen Pengguna
                </a>
                <ul class="dropdown-menu bg-dark" aria-labelledby="usersDropdown">
                    <li><a class="dropdown-item text-white" href="{{ route('admin.index') }}">Admin</a></li>
                    <li><a class="dropdown-item text-white" href="{{ route('customer.index') }}">Customer</a></li>
                    <li><a class="dropdown-item text-white" href="{{ route('supplier.index') }}">Supplier</a></li>
                </ul>
            </li>

            <!-- Manajemen Gudang -->
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('gudang.index') }}">Manajemen Gudang</a>
            </li>

            <!-- Manajemen Kategori -->
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('kategori_produk.index') }}">Kategori Produk</a>
            </li>

            <!-- Manajemen Stok -->
            <li class="nav-item dropdown">
                <a class="nav-link text-white dropdown-toggle" href="#" id="stokDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Manajemen Stok
                </a>
                <ul class="dropdown-menu bg-dark" aria-labelledby="stokDropdown">
                    <li><a class="dropdown-item text-white" href="{{ route('stok-masuk.index') }}">Stok Masuk</a></li>
                    <li><a class="dropdown-item text-white" href="{{ route('stok-keluar.index') }}">Stok Keluar</a></li>
                    <li><a class="dropdown-item text-white" href="{{ route('stok-opname.index') }}">Stok Opname</a></li>
                </ul>
            </li>

            <!-- Approvals -->
            <li class="nav-item dropdown">
                <a class="nav-link text-white dropdown-toggle" href="#" id="approvalsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Persetujuan
                </a>
                <ul class="dropdown-menu bg-dark" aria-labelledby="approvalsDropdown">
                    <li><a class="dropdown-item text-white" href="{{ route('produk-hilang.index') }}?status=pending">Produk Hilang</a></li>
                    <li><a class="dropdown-item text-white" href="{{ route('stok-masuk.index') }}?status=pending">Stok Masuk</a></li>
                    <li><a class="dropdown-item text-white" href="{{ route('stok-keluar.index') }}?status=pending">Stok Keluar</a></li>
                    <li><a class="dropdown-item text-white" href="{{ route('stok-opname.index') }}?status=pending">Stok Opname</a></li>
                </ul>
            </li>
        @endif
    </ul>
</div>