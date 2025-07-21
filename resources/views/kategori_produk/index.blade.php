@extends('layouts.app')
@section('title', 'Manajemen Kategori Produk')
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
        --border-radius: 12px;
        --box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        --transition: all 0.3s ease;
    }

    .main-container {
        max-width: 1400px;
        margin: 30px auto;
        padding: 0 25px;
    }

    .card {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 40px;
        margin-top: 30px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--dark-color);
        margin: 0;
    }

    .search-filter-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        gap: 15px;
    }

    .search-box {
        flex: 1;
        max-width: 400px;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 14px 15px 14px 45px;
        border-radius: var(--border-radius);
        border: 2px solid #e0e0e0;
        font-size: 16px;
        transition: var(--transition);
        background-color: #f8f9fa;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-color);
        background-color: white;
        box-shadow: 0 0 0 4px rgba(92, 72, 238, 0.15);
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--secondary-color);
        font-size: 18px;
    }

    .btn {
        padding: 14px 28px;
        border-radius: var(--border-radius);
        font-weight: 500;
        font-size: 16px;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 10px;
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
        border: 2px solid var(--primary-color);
        background-color: white;
        color: var(--primary-color);
    }

    .btn-outline:hover {
        background-color: var(--primary-light);
    }

    .btn-danger {
        background-color: var(--danger-color);
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .table-responsive {
        overflow-x: auto;
        margin-top: 30px;
    }

    .kategori-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 16px;
    }

    .kategori-table thead {
        background-color: #f8f9fa;
    }

    .kategori-table th {
        padding: 18px;
        text-align: left;
        font-weight: 600;
        color: var(--dark-color);
        border-bottom: 2px solid #e9ecef;
    }

    .kategori-table td {
        padding: 18px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .kategori-table tr:hover {
        background-color: rgba(92, 72, 238, 0.03);
    }

    .action-cell {
        display: flex;
        gap: 12px;
    }

    .btn-action {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: none;
        cursor: pointer;
        transition: var(--transition);
        font-size: 16px;
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
        padding: 60px 20px;
    }

    .empty-icon {
        font-size: 3.5rem;
        color: #adb5bd;
        margin-bottom: 20px;
    }

    .empty-text {
        color: #6c757d;
        margin-bottom: 30px;
        font-size: 1.2rem;
    }

    .pagination-container {
        display: flex;
        justify-content: flex-end;
        margin-top: 30px;
    }

    .form-kategori {
        background-color: white;
        border-radius: var(--border-radius);
        padding: 40px;
        margin-bottom: 40px;
        display: none;
        border: 1px solid #e0e0e0;
        box-shadow: var(--box-shadow);
    }

    .form-header {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .form-header i {
        color: var(--primary-color);
        font-size: 1.8rem;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 12px;
        color: #555;
    }

    .form-label.required:after {
        content: " *";
        color: var(--danger-color);
    }

    .form-control {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e0e0e0;
        border-radius: var(--border-radius);
        font-size: 16px;
        transition: var(--transition);
        background-color: #fff;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(92, 72, 238, 0.15);
        outline: none;
    }

    textarea.form-control {
        min-height: 150px;
        resize: vertical;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 20px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .checkbox-cell {
        width: 50px;
        text-align: center;
    }

    .checkbox-cell input[type="checkbox"] {
        width: 18px;
        height: 18px;
    }

    @media (max-width: 768px) {
        .search-filter-container {
            flex-direction: column;
        }
        
        .search-box {
            max-width: 100%;
        }
        
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .action-buttons {
            width: 100%;
            justify-content: flex-end;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
        }

        .form-kategori {
            padding: 30px 20px;
        }
    }

    @media (min-width: 1200px) {
        .form-kategori {
            padding: 50px;
        }
        
        .form-control {
            padding: 16px 20px;
        }
    }
</style>

<div class="main-container">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Manajemen Kategori Produk</h1>
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="toggleForm('add')" id="btnAddKategori">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </button>
            </div>
        </div>

        <!-- Enhanced Form -->
        <div id="formKategori" class="form-kategori">
            <div class="form-header">
                <i class="fas fa-tags"></i>
                <span id="formTitle">Tambah Kategori Baru</span>
            </div>
            
            <form method="POST" id="kategoriForm">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="kategoriId">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_kategori" class="form-label required">ID Kategori</label>
                            <input type="text" name="id_kategori" id="idKategoriInput" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jenis_kategori" class="form-label required">Jenis Kategori</label>
                            <select name="jenis_kategori" id="jenisKategoriSelect" class="form-control" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Makanan">Makanan</option>
                                <option value="Minuman">Minuman</option>
                                <option value="Alat">Alat</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nama_kategori" class="form-label required">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="namaKategoriInput" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsiInput" class="form-control" rows="5"></textarea>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="toggleForm()">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Kategori
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="search-filter-container">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="search" class="search-input" placeholder="Cari kategori..." id="searchInput">
            </div>
        </div>

        <div class="table-responsive">
            <form method="POST" action="{{ route('kategori_produk.delete.multiple') }}" id="deleteForm">
                @csrf
                <table class="kategori-table">
                    <thead>
                        <tr>
                            <th class="checkbox-cell"><input type="checkbox" id="selectAll"></th>
                            <th>ID Kategori</th>
                            <th>Nama Kategori</th>
                            <th>Jenis Kategori</th>
                            <th>Deskripsi</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoriProduks as $kategori)
                            <tr>
                                <td class="checkbox-cell"><input type="checkbox" name="ids[]" value="{{ $kategori->id }}" class="row-checkbox"></td>
                                <td>{{ $kategori->id_kategori }}</td>
                                <td>{{ $kategori->nama_kategori }}</td>
                                <td>{{ $kategori->jenis_kategori }}</td>
                                <td>{{ $kategori->deskripsi ? Str::limit($kategori->deskripsi, 50) : '-' }}</td>
                                <td>{{ $kategori->created_at->format('d M Y H:i') }}</td>
                                <td class="action-cell">
                                    <button type="button" class="btn-action btn-edit" onclick="editKategori({{ json_encode($kategori) }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('kategori_produk.destroy', $kategori->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" onclick="return confirm('Yakin ingin menghapus kategori ini?')">
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
                                            <i class="fas fa-box-open"></i>
                                        </div>
                                        <h4 class="empty-text">Belum ada data kategori produk</h4>
                                        <button class="btn btn-primary" onclick="toggleForm('add')">
                                            <i class="fas fa-plus"></i> Tambah Kategori
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if(count($kategoriProduks) > 0)
                    <div class="form-actions">
                        <button type="button" class="btn btn-danger" onclick="confirmDeleteMultiple()">
                            <i class="fas fa-trash"></i> Hapus yang Dipilih
                        </button>
                    </div>
                @endif
            </form>

            @if($kategoriProduks->hasPages())
                <div class="pagination-container">
                    {{ $kategoriProduks->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Toggle form visibility
    function toggleForm(mode = null, data = null) {
        const form = document.getElementById('formKategori');
        const btnAdd = document.getElementById('btnAddKategori');
        const formTitle = document.getElementById('formTitle');
        
        if (form.style.display === 'none' || !form.style.display) {
            // Show form
            form.style.display = 'block';
            
            // Reset form
            document.getElementById('kategoriForm').reset();
            document.getElementById('kategoriId').value = '';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('kategoriForm').action = "{{ route('kategori_produk.store') }}";
            document.getElementById('idKategoriInput').disabled = false;
            
            if (mode === 'add') {
                formTitle.textContent = 'Tambah Kategori Baru';
                btnAdd.innerHTML = '<i class="fas fa-times"></i> Batal';
                btnAdd.setAttribute('onclick', 'toggleForm()');
            } else if (data) {
                // Fill form with data for editing
                formTitle.textContent = 'Edit Kategori';
                document.getElementById('kategoriId').value = data.id;
                document.getElementById('idKategoriInput').value = data.id_kategori;
                document.getElementById('namaKategoriInput').value = data.nama_kategori;
                document.getElementById('deskripsiInput').value = data.deskripsi || '';
                document.getElementById('jenisKategoriSelect').value = data.jenis_kategori;
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('kategoriForm').action = "{{ url('kategori-produk') }}/" + data.id;
                document.getElementById('idKategoriInput').disabled = true;
                
                btnAdd.innerHTML = '<i class="fas fa-plus"></i> Tambah Kategori';
                btnAdd.setAttribute('onclick', 'toggleForm("add")');
            }
        } else {
            // Hide form
            form.style.display = 'none';
            btnAdd.innerHTML = '<i class="fas fa-plus"></i> Tambah Kategori';
            btnAdd.setAttribute('onclick', 'toggleForm("add")');
        }
    }

    // Edit kategori
    function editKategori(data) {
        toggleForm(null, data);
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const value = this.value.toLowerCase();
        const rows = document.querySelectorAll('.kategori-table tbody tr');
        
        rows.forEach(row => {
            if (row.querySelector('.empty-state')) return;
            
            const id = row.cells[1].textContent.toLowerCase();
            const nama = row.cells[2].textContent.toLowerCase();
            const jenis = row.cells[3].textContent.toLowerCase();
            const deskripsi = row.cells[4].textContent.toLowerCase();
            
            if (id.includes(value) || nama.includes(value) || jenis.includes(value) || deskripsi.includes(value)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Select all checkboxes
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Confirm multiple delete
    function confirmDeleteMultiple() {
        if (confirm('Yakin ingin menghapus kategori yang dipilih?')) {
            document.getElementById('deleteForm').submit();
        }
    }

    // Auto focus on form input when shown
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formKategori');
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'style') {
                    if (form.style.display === 'block') {
                        document.getElementById('idKategoriInput').focus();
                    }
                }
            });
        });
        
        observer.observe(form, {
            attributes: true
        });
    });
</script>

@endsection