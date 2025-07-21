@extends('layouts.app')

@section('title', 'Manajemen Customer')
@section('content')

<style>
    :root {
        --primary-color: #5C48EE;
        --primary-light: #eef0ff;
        --secondary-color: #6c757d;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --info-color: #17a2b8;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
        --border-radius: 8px;
        --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    .customer-container {
        max-width: 1400px;
        margin: 20px auto;
        padding: 0 15px;
    }

    .card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .card-header {
        padding: 20px 25px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: white;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--dark-color);
        margin: 0;
    }

    .card-body {
        padding: 25px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn {
        padding: 10px 20px;
        border-radius: var(--border-radius);
        font-weight: 500;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: #4a3ac4;
        transform: translateY(-2px);
    }

    .btn-outline {
        border: 1px solid var(--primary-color);
        background-color: white;
        color: var(--primary-color);
    }

    .btn-outline:hover {
        background-color: var(--primary-light);
    }

    .search-box {
        flex: 1;
        max-width: 400px;
        position: relative;
        margin-bottom: 20px;
    }

    .search-input {
        width: 100%;
        padding: 12px 15px 12px 40px;
        border-radius: var(--border-radius);
        border: 1px solid #e0e0e0;
        font-size: 0.875rem;
        transition: var(--transition);
        background-color: #f8f9fa;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-color);
        background-color: white;
        box-shadow: 0 0 0 3px rgba(92, 72, 238, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--secondary-color);
    }

    .table-responsive {
        overflow-x: auto;
    }

    .customer-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .customer-table thead {
        background-color: #f8f9fa;
    }

    .customer-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: var(--dark-color);
        border-bottom: 2px solid #e9ecef;
    }

    .customer-table td {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .customer-table tr:hover {
        background-color: rgba(92, 72, 238, 0.03);
    }

    .action-cell {
        display: flex;
        gap: 10px;
    }

    .btn-action {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: none;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-edit {
        color: var(--success-color);
        background-color: rgba(40, 167, 69, 0.1);
    }

    .btn-edit:hover {
        background-color: rgba(40, 167, 69, 0.2);
    }

    .btn-delete {
        color: var(--danger-color);
        background-color: rgba(220, 53, 69, 0.1);
    }

    .btn-delete:hover {
        background-color: rgba(220, 53, 69, 0.2);
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-icon {
        font-size: 3rem;
        color: #adb5bd;
        margin-bottom: 15px;
    }

    .empty-text {
        color: #6c757d;
        margin-bottom: 20px;
    }

    .pagination-container {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
    }

    /* Form Styles */
    .form-customer {
        display: none;
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.4s ease;
        padding: 20px;
        background: #f8f9fa;
        border-radius: var(--border-radius);
        margin-top: 20px;
    }

    .form-customer.show {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: var(--dark-color);
        font-size: 0.875rem;
    }

    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
        border-radius: var(--border-radius);
        font-size: 0.875rem;
        transition: var(--transition);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(92, 72, 238, 0.1);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .action-buttons {
            width: 100%;
            justify-content: flex-end;
        }
    }
</style>

<div class="customer-container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Manajemen Petugas</h2>
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="showFormCustomer('add')" id="btnAddCustomer">
                    <i class="fas fa-plus"></i> Tambah Petugas
                </button>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Search Box -->
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="search" class="search-input" placeholder="Cari Petugas..." id="searchInputCustomer">
            </div>

            <!-- Form Tambah/Edit -->
            <form method="POST" id="customerForm" action="{{ route('customer.store') }}" enctype="multipart/form-data" class="form-customer">
                @csrf
                <input type="hidden" name="id" id="customerId">

                <div class="form-group">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" id="namaInputCustomer" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="emailInputCustomer" class="form-control" required>
                </div>

                 <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" id="passwordInputCustomer" class="form-control" required minlength="6">
                    <div class="error-message" id="passwordError">Password harus minimal 6 karakter</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="confirmPasswordInput" class="form-control" required>
                    <div class="error-message" id="confirmPasswordError">Konfirmasi password tidak cocok</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" id="teleponInputCustomer" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" id="alamatInputCustomer" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" id="catatanInputCustomer" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-outline" onclick="hideFormCustomer()">Batal</button>
                </div>
            </form>

            <!-- Tabel Data -->
            <div id="tableCustomer" class="table-responsive">
                <form method="POST" action="{{ route('customer.delete.multiple') }}" id="deleteCustomerForm">
                    @csrf
                    @method('DELETE')
                    <table class="customer-table">
                        <thead>
                            <tr>
                                <th width="5%"><input type="checkbox" onclick="toggleAll(this)"></th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Catatan</th>
                                <th width="12%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customers as $customer)
                                <tr>
                                    <td><input type="checkbox" name="ids[]" value="{{ $customer->id }}" class="row-checkbox"></td>
                                    <td>{{ $customer->nama }}</td>
                                    <td>{{ optional($customer->user)->email ?? '-' }}</td>
                                    <td>{{ $customer->telepon }}</td>
                                    <td>{{ $customer->alamat }}</td>
                                    <td>{{ $customer->catatan }}</td>
                                    <td class="action-cell">
                                        <button type="button" class="btn-action btn-edit" title="Edit"
                                            onclick='editCustomer({{ json_encode($customer) }})'>
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" action="{{ route('customer.destroy', $customer->id) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Hapus" onclick="return confirm('Yakin ingin menghapus Petugas ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <div class="empty-icon">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <h4 class="empty-text">Belum ada data Petugas</h4>
                                            <button class="btn btn-primary" onclick="showFormCustomer('add')">
                                                <i class="fas fa-plus"></i> Tambah Petugas Pertama
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </form>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-danger" onclick="confirmDeleteSelected()">
                    <i class="fas fa-trash"></i> Hapus yang Dipilih
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function showFormCustomer(mode) {
        const form = document.getElementById('customerForm');
        const table = document.getElementById('tableCustomer');
        const btn = document.getElementById('btnAddCustomer');
        const cardTitle = document.querySelector('.card-title');

        form.reset();
        document.getElementById('customerId').value = '';

        // Remove existing _method if any
        const existingMethod = form.querySelector('input[name="_method"]');
        if (existingMethod) existingMethod.remove();

        if (mode === 'add') {
            cardTitle.innerText = 'Tambah Petugas';
            form.action = "{{ route('customer.store') }}";
            document.getElementById('passwordInputCustomer').required = true;
        } else {
            cardTitle.innerText = 'Edit Petugas';
            form.action = "{{ url('customer') }}/" + mode;

            // Add PUT method
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);

            document.getElementById('passwordInputCustomer').required = false;
        }

        form.style.display = 'block';
        setTimeout(() => form.classList.add('show'), 10);
        table.style.display = 'none';
        btn.innerHTML = '<i class="fas fa-times"></i> Batal';
        btn.setAttribute('onclick', 'hideFormCustomer()');
    }

    function hideFormCustomer() {
        const form = document.getElementById('customerForm');
        const table = document.getElementById('tableCustomer');
        const btn = document.getElementById('btnAddCustomer');
        const cardTitle = document.querySelector('.card-title');

        // Remove _method when going back to add
        const existingMethod = form.querySelector('input[name="_method"]');
        if (existingMethod) existingMethod.remove();

        form.classList.remove('show');
        setTimeout(() => form.style.display = 'none', 300);
        table.style.display = 'block';
        cardTitle.innerText = 'Manajemen Petugas';
        btn.innerHTML = '<i class="fas fa-plus"></i> Tambah Petugas';
        btn.setAttribute('onclick', 'showFormCustomer("add")');
    }

    function editCustomer(data) {
        showFormCustomer(data.id);
        document.getElementById('customerId').value = data.id;
        document.getElementById('namaInputCustomer').value = data.nama;
        document.getElementById('teleponInputCustomer').value = data.telepon || '';
        document.getElementById('alamatInputCustomer').value = data.alamat || '';
        document.getElementById('emailInputCustomer').value = data.user?.email || '';
        document.getElementById('catatanInputCustomer').value = data.catatan || '';
        document.getElementById('passwordInputCustomer').value = '';
    }

    document.getElementById('searchInputCustomer').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('.customer-table tbody tr');
        
        rows.forEach(row => {
            if (row.querySelector('.empty-state')) return;
            
            const nama = row.cells[1].textContent.toLowerCase();
            const email = row.cells[2].textContent.toLowerCase();
            const telepon = row.cells[3].textContent.toLowerCase();
            
            if (nama.includes(searchValue) || email.includes(searchValue) || telepon.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    function toggleAll(source) {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => cb.checked = source.checked);
    }

    function confirmDeleteSelected() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        if (checkedBoxes.length === 0) {
            alert('Pilih setidaknya satu Petugas untuk dihapus');
            return;
        }

        if (confirm(`Yakin ingin menghapus ${checkedBoxes.length} Petugas yang dipilih?`)) {
            document.getElementById('deleteCustomerForm').submit();
        }
    }

</script>

@endsection