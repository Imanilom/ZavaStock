@extends('layouts.app')
@section('title', 'Manajemen Admin')
@section('content')

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #eef0ff;
        margin: 0;
        padding: 30px 20px;
    }

    .main-container {
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
    }

    .card {
        background-color: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        width: 100%;
        box-sizing: border-box;
        transition: all 0.4s ease;
        margin-top: 40px;
    }

    .header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .header-row h1 {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
        transition: all 0.3s ease;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn {
        font-size: 10px;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #5C48EE;
        color: white;
    }

    .btn-outline {
        border: 1px solid #5C48EE;
        background-color: white;
        color: #5C48EE;
    }

    .search-bar {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
        background-color: white;
    }

    table thead {
        background-color: #f9f9f9;
    }

    th, td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }

    th {
        font-weight: 600;
        color: #333;
    }

    .btn-edit,
    .btn-delete {
        border: none;
        background: none;
        font-size: 14px;
        cursor: pointer;
    }

    .btn-edit {
        color: #28a745;
    }

    .btn-delete {
        color: #dc3545;
    }

    .form-supplier {
        display: none;
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.4s ease;
    }

    .form-supplier.show {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .admin-foto {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 6px;
        vertical-align: middle;
    }

    .form-supplier label {
        font-size: 10px;
        font-weight: 500;
    }

    .form-supplier .form-group {
        margin-bottom: 12px;
    }

    .form-supplier .form-control {
        padding: 8px 10px;
        font-size: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        width: 100%;
        box-sizing: border-box;
    }

    .form-supplier textarea.form-control {
        resize: vertical;
        min-height: 60px;
    }
</style>

<div class="main-container">
    <div class="card">
        <div class="header-row">
            <h1 id="judulSupplier">Admin</h1>
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="showFormSupplier('add')" id="btnAdd">+ Add</button>
                <a href="#" class="btn btn-outline" onclick="exportData()">Download</a>
            </div>
        </div>

        <!-- Form Tambah/Edit -->
        <div id="formSupplier" class="form-supplier">
            <form method="POST" id="supplierForm" action="{{ route('admin.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="supplierId">

                <div class="form-group">
                    <label>Foto Admin</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="name" id="namaInput" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="emailInput" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Telepon</label>
                    <input type="text" name="telepon" id="teleponInput" class="form-control">
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" id="alamatInput" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="catatan" id="catatanInput" class="form-control"></textarea>
                </div>

                <div class="form-group text-right mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-outline" onclick="hideFormSupplier()">Batal</button>
                </div>
            </form>
        </div>


        <!-- Tabel Data -->
        <div id="tabelSupplier">
            <input type="search" class="search-bar" placeholder="Cari admin...">

            <form method="POST" action="{{ route('admin.delete.multiple') }}">
                @csrf
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" onclick="toggleAll(this)"></th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Catatan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $admin)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $admin->id }}" class="row-checkbox"></td>
                                <td>
                                    @if ($admin->foto)
                                        <img src="{{ asset('images/admin/' . $admin->foto) }}" class="admin-foto" alt="Foto">
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                                <td>{{ $admin->nama }}</td>
                                <td>{{ optional($admin->user)->email ?? '-' }}</td>
                                <td>{{ $admin->telepon }}</td>
                                <td>{{ $admin->alamat }}</td>
                                <td>{{ $admin->catatan }}</td>
                                <td>
                                    <button type="button" class="btn-edit"
                                        onclick='editSupplier({{ json_encode($admin) }})'>
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.destroy', $admin->id) }}" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus admin ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Belum ada data admin.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
<script>
    function showFormSupplier(mode) {
        const form = document.getElementById('formSupplier');
        const table = document.getElementById('tabelSupplier');
        const title = document.getElementById('judulSupplier');
        const btn = document.getElementById('btnAdd');
        const supplierForm = document.getElementById('supplierForm');

        supplierForm.reset();
        document.getElementById('supplierId').value = '';

        // Hapus _method lama jika ada
        const existingMethod = supplierForm.querySelector('input[name="_method"]');
        if (existingMethod) existingMethod.remove();

        if (mode === 'add') {
            title.innerText = 'Tambah Admin';
            supplierForm.action = "{{ route('admin.store') }}";
            supplierForm.querySelector('input[name=password]').required = true;
            supplierForm.querySelector('input[name=password_confirmation]').required = true;
        } else {
            title.innerText = 'Edit Admin';
            supplierForm.action = "{{ url('admin') }}/" + mode;

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            supplierForm.appendChild(methodInput);

            supplierForm.querySelector('input[name=password]').required = false;
            supplierForm.querySelector('input[name=password_confirmation]').required = false;
        }

        form.style.display = 'block';
        setTimeout(() => form.classList.add('show'), 10);
        table.style.display = 'none';
        btn.innerText = 'Batal';
        btn.setAttribute('onclick', 'hideFormSupplier()');
    }

    function hideFormSupplier() {
        const form = document.getElementById('formSupplier');
        const table = document.getElementById('tabelSupplier');
        const title = document.getElementById('judulSupplier');
        const btn = document.getElementById('btnAdd');
        const supplierForm = document.getElementById('supplierForm');

        // Hapus _method saat reset
        const existingMethod = supplierForm.querySelector('input[name="_method"]');
        if (existingMethod) existingMethod.remove();

        form.classList.remove('show');
        setTimeout(() => form.style.display = 'none', 300);
        table.style.display = 'block';
        title.innerText = 'Admin';
        btn.innerText = '+ Add';
        btn.setAttribute('onclick', 'showFormSupplier("add")');
    }

    function editSupplier(data) {
        showFormSupplier(data.id);

        document.getElementById('supplierId').value = data.id;
        document.getElementById('namaInput').value = data.nama || '';
        document.getElementById('teleponInput').value = data.telepon || '';
        document.getElementById('alamatInput').value = data.alamat || '';
        document.getElementById('emailInput').value = data.user?.email || '';
        document.getElementById('catatanInput').value = data.catatan || '';

        // Kosongkan password saat edit
        const pass = document.querySelector('input[name="password"]');
        const conf = document.querySelector('input[name="password_confirmation"]');
        if (pass) pass.value = '';
        if (conf) conf.value = '';
    }

    document.querySelector('.search-bar').addEventListener('keyup', function () {
        const value = this.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');

        rows.forEach(row => {
            const nama = row.cells[2].textContent.toLowerCase();
            const email = row.cells[3].textContent.toLowerCase();
            const telepon = row.cells[4].textContent.toLowerCase();

            if (nama.includes(value) || email.includes(value) || telepon.includes(value)) {
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

    function exportData() {
        alert('Fitur download belum tersedia di versi ini.');
    }
</script>


@endsection