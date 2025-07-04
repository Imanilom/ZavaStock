@extends('layouts.app')

@section('title', 'Tambah Produk')
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
        display: none;
    }

    .select2-container {
        width: 100% !important;
    }
</style>

<div class="main-container">
    <div class="card">
        <div class="header-row">
            <h1>Tambah Produk Baru</h1>
        </div>

        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <!-- Informasi Dasar Produk -->
                    <div class="form-group">
                        <label for="sku">SKU Produk <span class="text-danger">*</span></label>
                        <input type="text" name="sku" id="sku" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="nama_produk">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="nama_produk" id="nama_produk" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="kategori">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" id="kategori" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoriProduks as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="bahan">Bahan</label>
                        <input type="text" name="bahan" id="bahan" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="AKTIF">AKTIF</option>
                            <option value="NONAKTIF">NONAKTIF</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Foto dan Deskripsi -->
                    <div class="form-group">
                        <label for="foto">Foto Produk</label>
                        <input type="file" name="foto" id="foto" class="form-control" accept="image/*" onchange="previewImage(this)">
                        <img id="foto-preview" class="preview-foto" src="#" alt="Preview Foto">
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="supplier_ids">Supplier</label>
                        <select name="supplier_ids[]" id="supplier_ids" class="form-control select2" multiple>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Varian Produk -->
            <div id="varian-wrapper">
                <h3 style="font-size: 16px; margin: 20px 0 10px;">Varian Produk</h3>
                
                <div class="varian-container" id="varian-template">
                   <div class="varian-header">
                        <h4 style="font-size: 14px; margin: 0;">Varian #1</h4>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeVarian(this)">Hapus</button>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nama Varian <span class="text-danger">*</span></label>
                                <input type="text" name="varian[0][nama]" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Harga Beli <span class="text-danger">*</span></label>
                                <input type="number" name="varian[0][harga_beli]" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Harga Jual <span class="text-danger">*</span></label>
                                <input type="number" name="varian[0][harga_jual]" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Diskon</label>
                                <input type="text" name="varian[0][diskon]" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Satuan</label>
                                <input type="text" name="varian[0][satuan]" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Panjang</label>
                                <input type="text" name="varian[0][panjang]" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Berat</label>
                                <input type="text" name="varian[0][berat]" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div id="detail-wrapper-0">
                        <h5 style="font-size: 13px; margin: 15px 0 10px;">Detail dan Stok</h5>

                        <div class="detail-container">
                            <div class="detail-header">
                                <h6 style="font-size: 12px; margin: 0;">Detail #1</h6>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removedetail(this, 0)">Hapus</button>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Detail <span class="text-danger">*</span></label>
                                        <input type="text" name="varian[0][detail][0][nama]" class="form-control" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Stok <span class="text-danger">*</span></label>
                                        <input type="number" name="varian[0][detail][0][stok]" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                     <button type="button" class="btn btn-outline btn-sm add-detail-btn" onclick="adddetail(0)">+ Tambah Detail</button>

                    </div>

                </div>
            </div>

            <button type="button" class="btn btn-outline" onclick="addVarian()">+ Tambah Varian</button>

            <div class="form-group text-right mt-4">
                <button type="submit" class="btn btn-primary">Simpan Produk</button>
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
    let varianCounter = 1;
    let detailCounters = {1: 1};

    // Tambah varian baru
    function addVarian() {
        varianCounter++;
        detailCounters[varianCounter] = 1;
        
        const newVarian = document.getElementById('varian-template').cloneNode(true);
        newVarian.id = 'varian-' + varianCounter;
        
        // Update semua nama input untuk varian baru
        const inputs = newVarian.querySelectorAll('input, select, textarea, button');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/varian\[1\]/g, `varian[${varianCounter}]`);
            }
            if (input.id) {
                input.id = input.id.replace(/_1_/g, `_${varianCounter}_`);
            }
        });
        
        // Update detail wrapper ID
        const detailWrapper = newVarian.querySelector('#detail-wrapper-1');
        if (detailWrapper) {
            detailWrapper.id = `detail-wrapper-${varianCounter}`;
            // Update fungsi adddetail untuk varian ini
            const adddetailBtn = detailWrapper.querySelector('button');
            if (adddetailBtn) {
                adddetailBtn.setAttribute('onclick', `adddetail(${varianCounter})`);
            }
        }
        
        // Update header varian
        const varianHeader = newVarian.querySelector('.varian-header h4');
        if (varianHeader) {
            varianHeader.textContent = `Varian #${varianCounter}`;
        }
        
        // Update tombol hapus varian
        const removeVarianBtn = newVarian.querySelector('.varian-header button');
        if (removeVarianBtn) {
            removeVarianBtn.setAttribute('onclick', `removeVarian(this)`);
        }
        
        // Update semua tombol hapus detail untuk varian ini
        const removedetailBtns = newVarian.querySelectorAll('.detail-header button');
        removedetailBtns.forEach(btn => {
            btn.setAttribute('onclick', `removedetail(this, ${varianCounter})`);
        });
        
        document.getElementById('varian-wrapper').appendChild(newVarian);
    }

    // Tambah detail baru untuk varian tertentu
    function adddetail(varianId) {
    detailCounters[varianId] = (detailCounters[varianId] || 1) + 1;
    const detailCounter = detailCounters[varianId];
    
    const detailWrapper = document.getElementById(`detail-wrapper-${varianId}`);
    const detailContainers = detailWrapper.querySelectorAll('.detail-container');
    const firstdetail = detailContainers[0];
    
    if (firstdetail) {
        const newdetail = firstdetail.cloneNode(true);

        // Reset input & update name
        const inputs = newdetail.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[detail\]\[\d+\]/g, `[detail][${detailCounter}]`);
                input.value = '';
            }
        });

        // Update header detail
        const detailHeader = newdetail.querySelector('.detail-header h6');
        if (detailHeader) {
            detailHeader.textContent = `Detail #${detailCounter}`;
        }

        // Update tombol hapus
        const removedetailBtn = newdetail.querySelector('.detail-header button');
        if (removedetailBtn) {
            removedetailBtn.setAttribute('onclick', `removedetail(this, ${varianId})`);
        }

        // Insert before tombol tambah detail
        const addButton = detailWrapper.querySelector('.add-detail-btn');
        if (addButton && addButton.parentNode === detailWrapper) {
            detailWrapper.insertBefore(newdetail, addButton);
        } else {
            detailWrapper.appendChild(newdetail);
        }
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
    function removedetail(button, varianId) {
        const detailContainer = button.closest('.detail-container');
        const detailContainers = document.querySelectorAll(`#detail-wrapper-${varianId} .detail-container`);
        
        if (detailContainers.length > 1) {
            detailContainer.remove();
        } else {
            alert('Varian harus memiliki minimal 1 detail');
        }
    }
</script>

@endsection