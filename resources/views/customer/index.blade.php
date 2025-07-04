@extends('layouts.app')
@section('title', 'Manajemen Customer')
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

    .form-customer {
        display: none;
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.4s ease;
    }

    .form-customer.show {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .customer-foto {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 6px;
        vertical-align: middle;
    }

    .form-customer label {
        font-size: 10px;
        font-weight: 500;
    }

    .form-customer .form-group {
        margin-bottom: 12px;
    }

    .form-customer .form-control {
        padding: 8px 10px;
        font-size: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        width: 100%;
        box-sizing: border-box;
    }

    .form-customer textarea.form-control {
        resize: vertical;
        min-height: 60px;
    }
</style>

<div class="main-container">
    <div class="card">
        <div class="header-row">
            <h1 id="judulCustomer">Customer</h1>
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="showFormCustomer('add')" id="btnAddCustomer">+ Add</button>
                <a href="#" class="btn btn-outline" onclick="exportData()">Download</a>
            </div>
        </div>

        <!-- Form Tambah/Edit -->
        <div id="formCustomer" class="form-customer">
            <form method="POST" id="customerForm" action="{{ route('customer.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="customerId">

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="name" id="namaInputCustomer" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="emailInputCustomer" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="passwordInputCustomer" class="form-control"
                        placeholder="Kosongkan jika tidak diubah">
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <div class="form-group">
                    <label>Telepon</label>
                    <input type="text" name="telepon" id="teleponInputCustomer" class="form-control">
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" id="alamatInputCustomer" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="catatan" id="catatanInputCustomer" class="form-control"></textarea>
                </div>

                <div class="form-group text-right mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-outline" onclick="hideFormCustomer()">Batal</button>
                </div>
            </form>
        </div>

        <!-- Tabel Data -->
        <div id="tabelCustomer">
            <input type="search" class="search-bar" placeholder="Cari customer...">

            <form method="POST" action="{{ route('customer.delete.multiple') }}">
                @csrf
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" onclick="toggleAll(this)"></th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Catatan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $customer->id }}"
                                        class="row-checkbox"></td>
                                <td>{{ $customer->nama }}</td>
                                <td>{{ optional($customer->user)->email ?? '-' }}</td>
                                <td>{{ $customer->telepon }}</td>
                                <td>{{ $customer->alamat }}</td>
                                <td>{{ $customer->catatan }}</td>
                                <td>
                                    <button type="button" class="btn-edit"
                                        onclick='editCustomer({{ json_encode($customer) }})'>
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <form method="POST"
                                        action="{{ route('customer.destroy', $customer->id) }}"
                                        style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus customer ini?')">
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
                                <td colspan="7" class="text-center">Belum ada data customer.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
<script>
    function showFormCustomer(mode) {
        const form = document.getElementById('formCustomer');
        const table = document.getElementById('tabelCustomer');
        const title = document.getElementById('judulCustomer');
        const btn = document.getElementById('btnAddCustomer');
        const customerForm = document.getElementById('customerForm');

        // Reset form
        customerForm.reset();
        document.getElementById('customerId').value = '';

        // Hapus input method _method jika ada sebelumnya
        const existingMethod = customerForm.querySelector('input[name="_method"]');
        if (existingMethod) existingMethod.remove();

        if (mode === 'add') {
            title.innerText = 'Tambah Customer';
            customerForm.action = "{{ route('customer.store') }}";
            document.getElementById('passwordInputCustomer').required = true;
        } else {
            title.innerText = 'Edit Customer';
            customerForm.action = "{{ url('customer') }}/" + mode;

            // Tambahkan input method PUT
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            customerForm.appendChild(methodInput);

            document.getElementById('passwordInputCustomer').required = false;
        }

        // Tampilkan form, sembunyikan tabel
        form.style.display = 'block';
        setTimeout(() => form.classList.add('show'), 10);
        table.style.display = 'none';
        btn.innerText = 'Batal';
        btn.setAttribute('onclick', 'hideFormCustomer()');
    }

    function hideFormCustomer() {
        const form = document.getElementById('formCustomer');
        const table = document.getElementById('tabelCustomer');
        const title = document.getElementById('judulCustomer');
        const btn = document.getElementById('btnAddCustomer');
        const customerForm = document.getElementById('customerForm');

        // Hapus _method saat kembali ke add
        const existingMethod = customerForm.querySelector('input[name="_method"]');
        if (existingMethod) existingMethod.remove();

        form.classList.remove('show');
        setTimeout(() => form.style.display = 'none', 300);
        table.style.display = 'block';
        title.innerText = 'Customer';
        btn.innerText = '+ Add';
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

    document.querySelector('.search-bar').addEventListener('keyup', function () {
        const value = this.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');

        rows.forEach(row => {
            const nama = row.cells[1].textContent.toLowerCase();
            const email = row.cells[2].textContent.toLowerCase();
            const telepon = row.cells[3].textContent.toLowerCase();

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