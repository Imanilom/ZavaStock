@extends('layouts.app')

@section('title', 'Edit Produk')
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
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 5px;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 12px;
        box-sizing: border-box;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: none;
    }

    .btn-primary {
        background-color: #5C48EE;
        color: white;
    }

    .btn-outline {
        background-color: white;
        color: #5C48EE;
        border: 1px solid #5C48EE;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .varian-container, .detail-container {
        border: 1px dashed #ccc;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .varian-header, .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .preview-foto {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
        margin-top: 5px;
    }

    .select2-container {
        width: 100% !important;
    }
</style>

<div class="main-container">
    <div class="card">
        <div class="header-row">
            <h1>Edit Produk: {{ $produk->nama_produk }}</h1>
        </div>

        <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <!-- Informasi Dasar Produk -->
                    <div class="form-group">
                        <label for="sku">SKU Produk <span class="text-danger">*</span></label>
                        <input type="text" name="sku" id="sku" class="form-control" value="{{ $produk->sku }}" required readonly>
                    </div>

                    <div class="form-group">
                        <label for="nama_produk">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="nama_produk" id="nama_produk" class="form-control" value="{{ $produk->nama_produk }}" required>
                    </div>

                    <div class="form-group">
                        <label for="kategori">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" id="kategori" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoriProduks as $key => $value)
                                <option value="{{ $key }}" {{ $produk->kategori == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="bahan">Bahan</label>
                        <input type="text" name="bahan" id="bahan" class="form-control" value="{{ $produk->bahan }}">
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="AKTIF" {{ $produk->status == 'AKTIF' ? 'selected' : '' }}>AKTIF</option>
                            <option value="NONAKTIF" {{ $produk->status == 'NONAKTIF' ? 'selected' : '' }}>NONAKTIF</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Foto dan Deskripsi -->
                    <div class="form-group">
                        <label for="foto">Foto Produk</label>
                        <input type="file" name="foto" id="foto" class="form-control" accept="image/*" onchange="previewImage(this)">
                        @if($produk->foto)
                            <img id="foto-preview" class="preview-foto" src="{{ asset('storage/' . $produk->foto) }}" alt="Preview Foto">
                        @else
                            <img id="foto-preview" class="preview-foto" src="#" alt="Preview Foto" style="display:none;">
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3">{{ $produk->deskripsi }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="supplier_ids">Supplier</label>
                        <select name="supplier_ids[]" id="supplier_ids" class="form-control select2" multiple>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" 
                                    {{ in_array($supplier->id, $produk->suppliers->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $supplier->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Varian Produk -->
            <div id="varian-wrapper">
                <h3 style="font-size: 16px; margin: 20px 0 10px;">Varian Produk</h3>
                
                @foreach($produk->varian as $varianIndex => $varian)
                <div class="varian-container" id="varian-{{ $varianIndex + 1 }}">
                    <div class="varian-header">
                        <h4 style="font-size: 14px; margin: 0;">Varian #{{ $varianIndex + 1 }}</h4>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeVarian(this)">Hapus</button>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nama Varian <span class="text-danger">*</span></label>
                                <input type="text" name="varian[{{ $varianIndex + 1 }}][nama]" class="form-control" value="{{ $varian->varian }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Harga Beli <span class="text-danger">*</span></label>
                                <input type="number" name="varian[{{ $varianIndex + 1 }}][harga_beli]" class="form-control" value="{{ $varian->harga_beli }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Harga Jual <span class="text-danger">*</span></label>
                                <input type="number" name="varian[{{ $varianIndex + 1 }}][harga_jual]" class="form-control" value="{{ $varian->harga_jual }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Diskon</label>
                                <input type="text" name="varian[{{ $varianIndex + 1 }}][diskon]" class="form-control" value="{{ $varian->diskon }}">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Satuan</label>
                                <input type="text" name="varian[{{ $varianIndex + 1 }}][satuan]" class="form-control" value="{{ $varian->satuan }}">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Panjang</label>
                                <input type="text" name="varian[{{ $varianIndex + 1 }}][panjang]" class="form-control" value="{{ $varian->panjang }}">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Berat</label>
                                <input type="text" name="varian[{{ $varianIndex + 1 }}][berat]" class="form-control" value="{{ $varian->berat }}">
                            </div>
                        </div>
                    </div>

                    <!-- Detail untuk varian ini -->
                    <div id="detail-wrapper-{{ $varianIndex + 1 }}">
                        <h5 style="font-size: 13px; margin: 15px 0 10px;">Detail dan Stok</h5>
                        <input type="hidden" name="varian[{{ $varianIndex + 1 }}][id]" value="{{ $varian->id }}">


                        @foreach($varian->detail as $detailIndex => $detail)
                        <input type="hidden" name="varian[{{ $varianIndex + 1 }}][detail][{{ $detailIndex + 1 }}][id]" value="{{ $detail->id }}">
                        <input type="hidden" name="varian[{{ $varianIndex + 1 }}][detail][{{ $detailIndex + 1 }}][kode_detail]" value="{{ $detail->kode_detail }}">
                        <div class="detail-container">
                            <div class="detail-header">
                                <h6 style="font-size: 12px; margin: 0;">Detail #{{ $detailIndex + 1 }}</h6>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeDetail(this, {{ $varianIndex + 1 }})">Hapus</button>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Detail <span class="text-danger">*</span></label>
                                        <input type="text" name="varian[{{ $varianIndex + 1 }}][detail][{{ $detailIndex + 1 }}][nama]" class="form-control" value="{{ $detail->detail }}" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Stok <span class="text-danger">*</span></label>
                                        <input type="number" name="varian[{{ $varianIndex + 1 }}][detail][{{ $detailIndex + 1 }}][stok]" class="form-control" value="{{ $detail->stok }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        <button type="button" class="btn btn-outline btn-sm" onclick="addDetail({{ $varianIndex + 1 }})">+ Tambah Detail</button>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-outline" onclick="addVarian()">+ Tambah Varian</button>

            <div class="form-group text-right mt-4">
                <button type="submit" class="btn btn-primary">Update Produk</button>
                <a href="{{ route('produk.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Inisialisasi Select2
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Pilih Supplier",
            allowClear: true
        });
    });

    // Preview image sebelum upload
    function previewImage(input) {
        const preview = document.getElementById('foto-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.style.display = 'block';
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Counter untuk varian dan detail
    let varianCounter = {{ count($produk->varian) }};
    let detailCounters = {
        @foreach($produk->varian as $varianIndex => $varian)
            {{ $varianIndex + 1 }}: {{ count($varian->detail) }},
        @endforeach
    };

    // Tambah varian baru
    function addVarian() {
        varianCounter++;
        detailCounters[varianCounter] = 0; // Reset counter untuk varian baru
        
        const varianWrapper = document.getElementById('varian-wrapper');
        
        // Buat elemen varian baru
        const newVarian = document.createElement('div');
        newVarian.className = 'varian-container';
        newVarian.id = 'varian-' + varianCounter;
        
        newVarian.innerHTML = `
            <div class="varian-header">
                <h4 style="font-size: 14px; margin: 0;">Varian #${varianCounter}</h4>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeVarian(this)">Hapus</button>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nama Varian <span class="text-danger">*</span></label>
                        <input type="text" name="varian[${varianCounter}][nama]" class="form-control" required>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Harga Beli <span class="text-danger">*</span></label>
                        <input type="number" name="varian[${varianCounter}][harga_beli]" class="form-control" required>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Harga Jual <span class="text-danger">*</span></label>
                        <input type="number" name="varian[${varianCounter}][harga_jual]" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Diskon</label>
                        <input type="text" name="varian[${varianCounter}][diskon]" class="form-control">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Satuan</label>
                        <input type="text" name="varian[${varianCounter}][satuan]" class="form-control">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Panjang</label>
                        <input type="text" name="varian[${varianCounter}][panjang]" class="form-control">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Berat</label>
                        <input type="text" name="varian[${varianCounter}][berat]" class="form-control">
                    </div>
                </div>
            </div>

            <div id="detail-wrapper-${varianCounter}">
                <h5 style="font-size: 13px; margin: 15px 0 10px;">Detail dan Stok</h5>
                <button type="button" class="btn btn-outline btn-sm" onclick="addDetail(${varianCounter})">+ Tambah Detail</button>
            </div>
        `;
        
        varianWrapper.appendChild(newVarian);
        
        // Tambahkan detail pertama secara otomatis
        addDetail(varianCounter);
    }

    // Tambah detail baru untuk varian tertentu
    function addDetail(varianId) {
        detailCounters[varianId] = (detailCounters[varianId] || 0) + 1;
        const detailCounter = detailCounters[varianId];
        
        const detailWrapper = document.getElementById(`detail-wrapper-${varianId}`);
        
        // Buat elemen detail baru
        const newDetail = document.createElement('div');
        newDetail.className = 'detail-container';
        
        newDetail.innerHTML = `
            <div class="detail-header">
                <h6 style="font-size: 12px; margin: 0;">Detail #${detailCounter}</h6>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeDetail(this, ${varianId})">Hapus</button>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Detail <span class="text-danger">*</span></label>
                        <input type="text" name="varian[${varianId}][detail][${detailCounter}][nama]" class="form-control" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Stok <span class="text-danger">*</span></label>
                        <input type="number" name="varian[${varianId}][detail][${detailCounter}][stok]" class="form-control" required>
                    </div>
                </div>
            </div>
        `;
        
        // Temukan tombol "Tambah Detail" yang benar
        const addButtons = detailWrapper.getElementsByTagName('button');
        let addButton = null;
        
        // Cari tombol dengan teks "+ Tambah Detail"
        for (let i = 0; i < addButtons.length; i++) {
            if (addButtons[i].textContent.includes('Tambah Detail')) {
                addButton = addButtons[i];
                break;
            }
        }
        
        // Jika tombol ditemukan, sisipkan sebelum tombol
        if (addButton) {
            detailWrapper.insertBefore(newDetail, addButton);
        } else {
            // Jika tidak ditemukan, tambahkan ke akhir wrapper
            detailWrapper.appendChild(newDetail);
        }
    }

    // Hapus varian
    function removeVarian(button) {
        const varianContainer = button.closest('.varian-container');
        if (document.querySelectorAll('.varian-container').length > 1) {
            varianContainer.remove();
        } else {
            alert('Produk harus memiliki minimal 1 varian');
        }
    }

    // Hapus detail
    function removeDetail(button, varianId) {
        const detailContainer = button.closest('.detail-container');
        const detailContainers = document.querySelectorAll(`#detail-wrapper-${varianId} .detail-container`);
        
        if (detailContainers.length > 1) {
            detailContainer.remove();
            
            // Perbarui nomor detail yang tersisa
            const remainingDetails = document.querySelectorAll(`#detail-wrapper-${varianId} .detail-container`);
            remainingDetails.forEach((detail, index) => {
                const header = detail.querySelector('.detail-header h6');
                if (header) header.textContent = `Detail #${index + 1}`;
                
                // Perbarui nama input
                const inputs = detail.querySelectorAll('input');
                inputs.forEach(input => {
                    input.name = input.name.replace(/detail\]\[\d+\]/, `detail][${index + 1}]`);
                });
            });
            
            // Perbarui counter
            detailCounters[varianId] = remainingDetails.length;
        } else {
            alert('Varian harus memiliki minimal 1 detail');
        }
    }
</script>

@endsection