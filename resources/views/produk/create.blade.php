@extends('layouts.app')

@section('title', 'Tambah Produk')
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
        --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--primary-light);
        color: #333;
        line-height: 1.6;
    }

    .main-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .card {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 30px;
        margin-top: 20px;
        transition: var(--transition);
    }

    .card:hover {
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .header-row h1 {
        font-size: 24px;
        font-weight: 600;
        color: var(--dark-color);
        margin: 0;
    }

    .form-section-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--primary-color);
        margin: 25px 0 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--primary-light);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 8px;
        color: #555;
    }

    .form-group label.required:after {
        content: " *";
        color: var(--danger-color);
    }

    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: var(--border-radius);
        font-size: 14px;
        transition: var(--transition);
        background-color: #fff;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(92, 72, 238, 0.25);
        outline: none;
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    .btn {
        padding: 10px 20px;
        border-radius: var(--border-radius);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn i {
        margin-right: 8px;
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
        background-color: white;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
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

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
    }

    .variant-container, .detail-container {
        border: 1px dashed rgba(92, 72, 238, 0.3);
        padding: 20px;
        border-radius: var(--border-radius);
        margin-bottom: 20px;
        background-color: rgba(238, 240, 255, 0.3);
        transition: var(--transition);
    }

    .variant-container:hover, .detail-container:hover {
        border-color: var(--primary-color);
    }

    .variant-header, .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .variant-header h4, .detail-header h5 {
        font-size: 16px;
        font-weight: 600;
        color: var(--dark-color);
        margin: 0;
    }

    .preview-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 4px;
        margin-top: 10px;
        border: 1px solid #eee;
        display: none;
    }

    .image-upload-container {
        position: relative;
        display: inline-block;
    }

    .image-upload-label {
        display: block;
        padding: 10px;
        background-color: var(--primary-light);
        border: 1px dashed var(--primary-color);
        border-radius: var(--border-radius);
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
    }

    .image-upload-label:hover {
        background-color: rgba(92, 72, 238, 0.1);
    }

    .image-upload-label i {
        font-size: 24px;
        color: var(--primary-color);
        display: block;
        margin-bottom: 8px;
    }

    .image-upload-input {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        cursor: pointer;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ddd;
        border-radius: var(--border-radius);
        min-height: 42px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: var(--primary-color);
        border: none;
        border-radius: 4px;
        color: white;
    }

    @media (max-width: 768px) {
        .card {
            padding: 20px 15px;
        }
        
        .header-row h1 {
            font-size: 20px;
        }
        
        .variant-container, .detail-container {
            padding: 15px;
        }
    }
</style>

<div class="main-container">
    <div class="card">
        <div class="header-row">
            <h1><i class="fas fa-plus-circle mr-2"></i>Tambah Produk Baru</h1>
        </div>

        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <!-- Basic Product Information -->
                    <h3 class="form-section-title"><i class="fas fa-info-circle mr-2"></i>Informasi Dasar Produk</h3>
                    
                    <div class="form-group">
                        <label for="sku" class="required">SKU Produk</label>
                        <input type="text" name="sku" id="sku" class="form-control" required>
                        <small class="text-muted">Kode unik untuk identifikasi produk</small>
                    </div>

                    <div class="form-group">
                        <label for="nama_produk" class="required">Nama Produk</label>
                        <input type="text" name="nama_produk" id="nama_produk" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="kategori" class="required">Kategori</label>
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
                        <small class="text-muted">Material utama pembuatan produk</small>
                    </div>

                    <div class="form-group">
                        <label for="status">Status Produk</label>
                        <select name="status" id="status" class="form-control">
                            <option value="AKTIF">AKTIF</option>
                            <option value="NONAKTIF">NONAKTIF</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Product Media & Description -->
                    <h3 class="form-section-title"><i class="fas fa-image mr-2"></i>Media & Deskripsi</h3>
                    
                    <div class="form-group">
                        <label for="foto">Foto Produk</label>
                        <div class="image-upload-container">
                            <label class="image-upload-label" for="foto">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Pilih atau tarik gambar</span>
                                <small class="d-block text-muted">Format: JPG, PNG (Maks. 2MB)</small>
                            </label>
                            <input type="file" name="foto" id="foto" class="image-upload-input" accept="image/*" onchange="previewImage(this)">
                        </div>
                        <img id="foto-preview" class="preview-image" src="#" alt="Preview Foto">
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Produk</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"></textarea>
                        <small class="text-muted">Deskripsi lengkap tentang produk</small>
                    </div>

                    <div class="form-group">
                        <label for="supplier_ids">Supplier</label>
                        <select name="supplier_ids[]" id="supplier_ids" class="form-control select2" multiple>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih supplier yang menyediakan produk ini</small>
                    </div>
                </div>
            </div>

            <!-- Product Variants -->
            <div id="variant-wrapper">
                <h3 class="form-section-title"><i class="fas fa-layer-group mr-2"></i>Varian Produk</h3>
                
                <div class="variant-container" id="variant-template">
                    <div class="variant-header">
                        <h4><i class="fas fa-cube mr-2"></i>Varian #1</h4>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeVariant(this)">
                            <i class="fas fa-trash mr-1"></i>Hapus
                        </button>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="required">Nama Varian</label>
                                <input type="text" name="varian[0][nama]" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="required">Harga Beli (Rp)</label>
                                <input type="number" name="varian[0][harga_beli]" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="required">Harga Jual (Rp)</label>
                                <input type="number" name="varian[0][harga_jual]" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Diskon (%)</label>
                                <input type="number" name="varian[0][diskon]" class="form-control" min="0" max="100">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Satuan</label>
                                <input type="text" name="varian[0][satuan]" class="form-control" placeholder="pcs, kg, etc">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Panjang (cm)</label>
                                <input type="number" name="varian[0][panjang]" class="form-control" step="0.01">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Berat (gram)</label>
                                <input type="number" name="varian[0][berat]" class="form-control" step="0.01">
                            </div>
                        </div>
                    </div>

                    <div id="detail-wrapper-0">
                        <h5 class="form-section-title" style="font-size: 15px;">
                            <i class="fas fa-list-ul mr-2"></i>Detail Produk
                    
                        </h5>

                        <div class="detail-container">
                            <div class="detail-header">
                                <h5><i class="fas fa-info-circle mr-2"></i>Detail #1</h5>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeDetail(this, 0)">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Nama Detail</label>
                                        <input type="text" name="varian[0][detail][0][nama]" class="form-control" required>
                                        <small class="text-muted">Contoh: Warna, Ukuran, dll</small>
                                    </div>
                                </div>
                                
                                <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Stok Awal</label>
                                        <input type="number" name="varian[0][detail][0][stok]" class="form-control" required min="0">
                                    </div>
                                </div> -->
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline btn-sm add-detail-btn" onclick="addDetail(0)">
                            <i class="fas fa-plus mr-1"></i>Tambah Detail
                        </button>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-outline" onclick="addVariant()">
                <i class="fas fa-plus mr-1"></i>Tambah Varian
            </button>

            <div class="form-actions">
                <a href="{{ route('produk.index') }}" class="btn btn-outline">
                    <i class="fas fa-times mr-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i>Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Initialize Select2
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Pilih Supplier",
            allowClear: true,
            width: '100%'
        });
    });

    // Preview image before upload
    function previewImage(input) {
        const preview = document.getElementById('foto-preview');
        const label = document.querySelector('.image-upload-label');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileSize = file.size / 1024 / 1024; // in MB
            
            // Validate file size
            if (fileSize > 2) {
                alert('Ukuran file maksimal 2MB');
                input.value = '';
                return;
            }
            
            // Validate file type
            if (!file.type.match('image.*')) {
                alert('Hanya file gambar yang diizinkan');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.style.display = 'block';
                preview.src = e.target.result;
                label.innerHTML = `<i class="fas fa-check-circle"></i><span>${file.name}</span><small class="d-block text-muted">${(fileSize).toFixed(2)} MB</small>`;
            }
            reader.readAsDataURL(file);
        }
    }

    // Counters for variants and details
    let variantCounter = 1;
    let detailCounters = {0: 1};

    // Add new variant
    function addVariant() {
        variantCounter++;
        detailCounters[variantCounter] = 1;
        
        const newVariant = document.getElementById('variant-template').cloneNode(true);
        newVariant.id = 'variant-' + variantCounter;
        
        // Update all input names for the new variant
        const inputs = newVariant.querySelectorAll('input, select, textarea, button');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/varian\[0\]/g, `varian[${variantCounter}]`);
            }
            if (input.id) {
                input.id = input.id.replace(/_0_/g, `_${variantCounter}_`);
            }
        });
        
        // Update detail wrapper ID
        const detailWrapper = newVariant.querySelector('#detail-wrapper-0');
        if (detailWrapper) {
            detailWrapper.id = `detail-wrapper-${variantCounter}`;
            // Update addDetail function for this variant
            const addDetailBtn = detailWrapper.querySelector('button');
            if (addDetailBtn) {
                addDetailBtn.setAttribute('onclick', `addDetail(${variantCounter})`);
            }
        }
        
        // Update variant header
        const variantHeader = newVariant.querySelector('.variant-header h4');
        if (variantHeader) {
            variantHeader.innerHTML = `<i class="fas fa-cube mr-2"></i>Varian #${variantCounter}`;
        }
        
        // Update remove variant button
        const removeVariantBtn = newVariant.querySelector('.variant-header button');
        if (removeVariantBtn) {
            removeVariantBtn.setAttribute('onclick', `removeVariant(this)`);
        }
        
        // Update all remove detail buttons for this variant
        const removeDetailBtns = newVariant.querySelectorAll('.detail-header button');
        removeDetailBtns.forEach(btn => {
            btn.setAttribute('onclick', `removeDetail(this, ${variantCounter})`);
        });
        
        // Reset all input values
        newVariant.querySelectorAll('input').forEach(input => {
            if (input.type !== 'button') input.value = '';
        });
        
        document.getElementById('variant-wrapper').appendChild(newVariant);
        
        // Scroll to the new variant
        newVariant.scrollIntoView({behavior: 'smooth', block: 'nearest'});
    }

    // Add new detail for a specific variant
    function addDetail(variantId) {
        detailCounters[variantId] = (detailCounters[variantId] || 1) + 1;
        const detailCounter = detailCounters[variantId];
        
        const detailWrapper = document.getElementById(`detail-wrapper-${variantId}`);
        const detailContainers = detailWrapper.querySelectorAll('.detail-container');
        const firstDetail = detailContainers[0];
        
        if (firstDetail) {
            const newDetail = firstDetail.cloneNode(true);

            // Reset input values & update names
            const inputs = newDetail.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.name) {
                    input.name = input.name.replace(/\[detail\]\[\d+\]/g, `[detail][${detailCounter}]`);
                    input.value = '';
                }
            });

            // Update detail header
            const detailHeader = newDetail.querySelector('.detail-header h5');
            if (detailHeader) {
                detailHeader.innerHTML = `<i class="fas fa-info-circle mr-2"></i>Detail #${detailCounter}`;
            }

            // Update remove button
            const removeDetailBtn = newDetail.querySelector('.detail-header button');
            if (removeDetailBtn) {
                removeDetailBtn.setAttribute('onclick', `removeDetail(this, ${variantId})`);
            }

            // Insert before the add detail button
            const addButton = detailWrapper.querySelector('.add-detail-btn');
            if (addButton && addButton.parentNode === detailWrapper) {
                detailWrapper.insertBefore(newDetail, addButton);
            } else {
                detailWrapper.appendChild(newDetail);
            }
            
            // Scroll to the new detail
            newDetail.scrollIntoView({behavior: 'smooth', block: 'nearest'});
        }
    }

    // Remove variant
    function removeVariant(button) {
        const variantContainer = button.closest('.variant-container');
        if (document.querySelectorAll('.variant-container').length > 1) {
            variantContainer.remove();
            
            // Renumber remaining variants
            const variants = document.querySelectorAll('.variant-container');
            variants.forEach((variant, index) => {
                const header = variant.querySelector('.variant-header h4');
                if (header) {
                    header.innerHTML = `<i class="fas fa-cube mr-2"></i>Varian #${index + 1}`;
                }
            });
        } else {
            alert('Produk harus memiliki minimal 1 varian');
        }
    }

    // Remove detail
    function removeDetail(button, variantId) {
        const detailContainer = button.closest('.detail-container');
        const detailContainers = document.querySelectorAll(`#detail-wrapper-${variantId} .detail-container`);
        
        if (detailContainers.length > 1) {
            detailContainer.remove();
            
            // Renumber remaining details
            const details = document.querySelectorAll(`#detail-wrapper-${variantId} .detail-container`);
            details.forEach((detail, index) => {
                const header = detail.querySelector('.detail-header h5');
                if (header) {
                    header.innerHTML = `<i class="fas fa-info-circle mr-2"></i>Detail #${index + 1}`;
                }
            });
        } else {
            alert('Varian harus memiliki minimal 1 detail');
        }
    }
</script>

@endsection