@extends('layouts.app')
@section('title', 'Manajemen Kategori Produk')
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

    .form-kategori-produk {
        display: none;
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.4s ease;
    }

    .form-kategori-produk.show {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .form-control {
        padding: 8px 10px;
        font-size: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        width: 100%;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #5C48EE;
    }

    .form-group {
        margin-bottom: 12px;
    }

</style>

<div class="main-container">
    <div class="card">
        <div class="header-row">
            <h1 id="judulKategori">Kategori Produk</h1>
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="showFormKategori('add')" id="btnAddKategori">+ Add</button>
                <a href="#" class="btn btn-outline" onclick="exportData()">Download</a>
            </div>
        </div>

        <!-- Form Tambah/Edit -->
        <div id="formKategori" class="form-kategori-produk">
            <form method="POST" id="kategoriForm" action="{{ route('kategori_produk.store') }}">
                @csrf
                <input type="hidden" name="id" id="kategoriId">

                <div class="form-group">
                    <label>ID Kategori</label>
                    <input type="text" name="id_kategori" id="idKategoriInput" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="namaKategoriInput" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsiInput" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label>Jenis Kategori</label>
                    <select name="jenis_kategori" id="jenisKategoriSelect" class="form-control" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Makanan">Makanan</option>
                        <option value="Minuman">Minuman</option>
                        <option value="Alat">Alat</option>
                    </select>
                </div>

                <div class="form-group text-right mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-outline" onclick="hideFormKategori()">Batal</button>
                </div>
            </form>
        </div>

        <!-- Tabel Data -->
        <div id="tabelKategori">
            <input type="search" class="search-bar" placeholder="Cari kategori...">

            <form method="POST" action="{{ route('kategori_produk.delete.multiple') }}">
                @csrf
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" onclick="toggleAll(this)"></th>
                            <th>ID Kategori</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Jenis Kategori</th>
                            <th>Tanggal Dibuat</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoriProduks as $kategori)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $kategori->id }}" class="row-checkbox"></td>
                                <td>{{ $kategori->id_kategori }}</td>
                                <td>{{ $kategori->nama_kategori }}</td>
                                <td>{{ $kategori->deskripsi ?? '-' }}</td>
                                <td>{{ $kategori->jenis_kategori }}</td>
                                <td>{{ $kategori->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <button type="button" class="btn-edit"
                                        onclick='editKategori({{ json_encode($kategori) }})'>
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <form method="POST"
                                        action="{{ route('kategori_produk.destroy', $kategori->id) }}"
                                        style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
                                <td colspan="7" class="text-center">Belum ada data kategori produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<script>
    function showFormKategori(mode) {
        const form = document.getElementById('formKategori');
        const table = document.getElementById('tabelKategori');
        const title = document.getElementById('judulKategori');
        const btn = document.getElementById('btnAddKategori');
        const kategoriForm = document.getElementById('kategoriForm');

        kategoriForm.reset();
        document.getElementById('kategoriId').value = '';
        document.getElementById('idKategoriInput').disabled = false;

        // Hapus method PUT jika sebelumnya edit
        const existingMethod = kategoriForm.querySelector('input[name="_method"]');
        if (existingMethod) existingMethod.remove();

        if (mode === 'add') {
            title.innerText = 'Tambah Kategori Produk';
            kategoriForm.action = "{{ route('kategori_produk.store') }}";
        } else {
            title.innerText = 'Edit Kategori Produk';
            kategoriForm.action = "{{ url('kategori-produk') }}/" + mode;

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            kategoriForm.appendChild(methodInput);

            document.getElementById('idKategoriInput').disabled = true; // ID tidak bisa diubah saat edit
        }

        form.style.display = 'block';
        setTimeout(() => form.classList.add('show'), 10);
        table.style.display = 'none';
        btn.innerText = 'Batal';
        btn.setAttribute('onclick', 'hideFormKategori()');
    }

    function hideFormKategori() {
        const form = document.getElementById('formKategori');
        const table = document.getElementById('tabelKategori');
        const title = document.getElementById('judulKategori');
        const btn = document.getElementById('btnAddKategori');
        const kategoriForm = document.getElementById('kategoriForm');

        // Hapus method PUT jika ada
        const existingMethod = kategoriForm.querySelector('input[name="_method"]');
        if (existingMethod) existingMethod.remove();

        form.classList.remove('show');
        setTimeout(() => form.style.display = 'none', 300);
        table.style.display = 'block';
        title.innerText = 'Kategori Produk';
        btn.innerText = '+ Add';
        btn.setAttribute('onclick', 'showFormKategori(\"add\")');
    }

    function editKategori(data) {
        showFormKategori(data.id);

        document.getElementById('kategoriId').value = data.id;
        document.getElementById('idKategoriInput').value = data.id_kategori;
        document.getElementById('namaKategoriInput').value = data.nama_kategori;
        document.getElementById('deskripsiInput').value = data.deskripsi || '';
        document.getElementById('jenisKategoriSelect').value = data.jenis_kategori;
    }

    document.querySelector('.search-bar').addEventListener('keyup', function () {
        const value = this.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');

        rows.forEach(row => {
            const nama = row.cells[2].textContent.toLowerCase();
            const jenis = row.cells[4].textContent.toLowerCase();
            const id = row.cells[1].textContent.toLowerCase();

            if (id.includes(value) || nama.includes(value) || jenis.includes(value)) {
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