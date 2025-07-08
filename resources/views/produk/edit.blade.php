@extends('layouts.app')

@section('title', 'Edit Produk')
@section('content')

<style>
    .product-form {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        padding: 2rem;
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e3e6f0;
    }

    .form-header h1 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #4e73df;
        margin: 0;
    }

    .form-section {
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 0.5rem;
        color: #4e73df;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #5a5c69;
    }

    .form-group label.required:after {
        content: " *";
        color: #e74a3b;
    }

    .form-control {
        width: 100%;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        color: #6e707e;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        color: #6e707e;
        background-color: #fff;
        border-color: #bac8f3;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .form-control[readonly] {
        background-color: #f8f9fc;
    }

    .variant-container, .detail-container {
        border: 1px dashed #d1d3e2;
        padding: 1.25rem;
        border-radius: 0.35rem;
        margin-bottom: 1.25rem;
        background-color: #f8f9fc;
    }

    .variant-header, .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e3e6f0;
    }

    .variant-header h4, .detail-header h6 {
        font-size: 0.95rem;
        font-weight: 600;
        color: #4e73df;
        margin: 0;
    }

    .preview-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 0.35rem;
        border: 1px solid #e3e6f0;
        margin-top: 0.5rem;
        display: block;
    }

    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.5;
        border-radius: 0.35rem;
        transition: all 0.15s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }

    .btn i {
        margin-right: 0.375rem;
    }

    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
    }

    .btn-outline {
        background-color: transparent;
        border: 1px solid #4e73df;
        color: #4e73df;
    }

    .btn-outline:hover {
        background-color: #f8f9fc;
    }

    .btn-danger {
        background-color: #e74a3b;
        border-color: #e74a3b;
        color: white;
    }

    .btn-danger:hover {
        background-color: #e02d1b;
        border-color: #d52a1a;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e3e6f0;
    }

    .select2-container--default .select2-selection--multiple {
        border: 1px solid #d1d3e2 !important;
        border-radius: 0.35rem !important;
        min-height: calc(1.5em + 0.75rem + 2px) !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #4e73df !important;
        border-color: #4e73df !important;
    }

    @media (max-width: 768px) {
        .form-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Produk</h1>
        <a href="{{ route('produk.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-outline shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Daftar Produk
        </a>
    </div>

    <div class="product-form">
        <div class="form-header">
            <h1><i class="fas fa-box-open mr-2"></i>Edit Produk: {{ $produk->nama_produk }}</h1>
        </div>

        <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <!-- Basic Product Information -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-info-circle"></i>
                            <span>Informasi Dasar Produk</span>
                        </div>

                        <div class="form-group">
                            <label for="sku" class="required">SKU Produk</label>
                            <input type="text" name="sku" id="sku" class="form-control" value="{{ $produk->sku }}" required readonly>
                        </div>

                        <div class="form-group">
                            <label for="nama_produk" class="required">Nama Produk</label>
                            <input type="text" name="nama_produk" id="nama_produk" class="form-control" value="{{ $produk->nama_produk }}" required>
                        </div>

                        <div class="form-group">
                            <label for="kategori" class="required">Kategori</label>
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
                </div>

                <div class="col-md-6">
                    <!-- Photo and Description -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-image"></i>
                            <span>Foto & Deskripsi</span>
                        </div>

                        <div class="form-group">
                            <label for="foto">Foto Produk</label>
                            <input type="file" name="foto" id="foto" class="form-control" accept="image/*" onchange="previewImage(this)">
                            @if($produk->foto)
                                <img id="foto-preview" class="preview-image mt-2" src="{{ asset('storage/' . $produk->foto) }}" alt="Preview Foto">
                            @else
                                <img id="foto-preview" class="preview-image mt-2" src="#" alt="Preview Foto" style="display:none;">
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4">{{ $produk->deskripsi }}</textarea>
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
            </div>

            <!-- Product Variants -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-layer-group"></i>
                    <span>Varian Produk</span>
                </div>

                <div id="varian-wrapper">
                    @foreach($produk->varian as $varianIndex => $varian)
                    <div class="variant-container" id="varian-{{ $varianIndex + 1 }}">
                        <div class="variant-header">
                            <h4><i class="fas fa-cube mr-2"></i>Varian #{{ $varianIndex + 1 }}</h4>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeVarian(this)">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required">Nama Varian</label>
                                    <input type="text" name="varian[{{ $varianIndex + 1 }}][nama]" class="form-control" value="{{ $varian->varian }}" required>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required">Harga Beli</label>
                                    <input type="number" name="varian[{{ $varianIndex + 1 }}][harga_beli]" class="form-control" value="{{ $varian->harga_beli }}" required>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required">Harga Jual</label>
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

                        <!-- Variant Details -->
                        <div id="detail-wrapper-{{ $varianIndex + 1 }}">
                            <div class="section-title" style="margin-bottom: 1rem;">
                                <i class="fas fa-list-ul"></i>
                                <span>Detail dan Stok</span>
                            </div>
                            <input type="hidden" name="varian[{{ $varianIndex + 1 }}][id]" value="{{ $varian->id }}">

                            @foreach($varian->detail as $detailIndex => $detail)
                            <input type="hidden" name="varian[{{ $varianIndex + 1 }}][detail][{{ $detailIndex + 1 }}][id]" value="{{ $detail->id }}">
                            <input type="hidden" name="varian[{{ $varianIndex + 1 }}][detail][{{ $detailIndex + 1 }}][kode_detail]" value="{{ $detail->kode_detail }}">
                            <div class="detail-container">
                                <div class="detail-header">
                                    <h6><i class="fas fa-circle-notch mr-2"></i>Detail #{{ $detailIndex + 1 }}</h6>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeDetail(this, {{ $varianIndex + 1 }})">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="required">Nama Detail</label>
                                            <input type="text" name="varian[{{ $varianIndex + 1 }}][detail][{{ $detailIndex + 1 }}][nama]" class="form-control" value="{{ $detail->detail }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="required">Stok</label>
                                            <input type="number" name="varian[{{ $varianIndex + 1 }}][detail][{{ $detailIndex + 1 }}][stok]" class="form-control" value="{{ $detail->stok }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            
                            <button type="button" class="btn btn-outline btn-sm" onclick="addDetail({{ $varianIndex + 1 }})">
                                <i class="fas fa-plus"></i> Tambah Detail
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-outline" onclick="addVarian()">
                    <i class="fas fa-plus"></i> Tambah Varian
                </button>
            </div>

            <div class="action-buttons">
                <a href="{{ route('produk.index') }}" class="btn btn-outline">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Produk
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
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.style.display = 'block';
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Counter for variants and details
    let varianCounter = {{ count($produk->varian) }};
    let detailCounters = {
        @foreach($produk->varian as $varianIndex => $varian)
            {{ $varianIndex + 1 }}: {{ count($varian->detail) }},
        @endforeach
    };

    // Add new variant
    function addVarian() {
        varianCounter++;
        detailCounters[varianCounter] = 0; // Reset counter for new variant
        
        const varianWrapper = document.getElementById('varian-wrapper');
        
        // Create new variant element
        const newVarian = document.createElement('div');
        newVarian.className = 'variant-container';
        newVarian.id = 'varian-' + varianCounter;
        
        newVarian.innerHTML = `
            <div class="variant-header">
                <h4><i class="fas fa-cube mr-2"></i>Varian #${varianCounter}</h4>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeVarian(this)">
                    <i class="fas fa-trash-alt"></i> Hapus
                </button>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required">Nama Varian</label>
                        <input type="text" name="varian[${varianCounter}][nama]" class="form-control" required>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required">Harga Beli</label>
                        <input type="number" name="varian[${varianCounter}][harga_beli]" class="form-control" required>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required">Harga Jual</label>
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
                <div class="section-title" style="margin-bottom: 1rem;">
                    <i class="fas fa-list-ul"></i>
                    <span>Detail dan Stok</span>
                </div>
                <button type="button" class="btn btn-outline btn-sm" onclick="addDetail(${varianCounter})">
                    <i class="fas fa-plus"></i> Tambah Detail
                </button>
            </div>
        `;
        
        varianWrapper.appendChild(newVarian);
        
        // Add first detail automatically
        addDetail(varianCounter);
    }

    // Add new detail for specific variant
    function addDetail(varianId) {
        detailCounters[varianId] = (detailCounters[varianId] || 0) + 1;
        const detailCounter = detailCounters[varianId];
        
        const detailWrapper = document.getElementById(`detail-wrapper-${varianId}`);
        
        // Create new detail element
        const newDetail = document.createElement('div');
        newDetail.className = 'detail-container';
        
        newDetail.innerHTML = `
            <div class="detail-header">
                <h6><i class="fas fa-circle-notch mr-2"></i>Detail #${detailCounter}</h6>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeDetail(this, ${varianId})">
                    <i class="fas fa-trash-alt"></i> Hapus
                </button>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required">Nama Detail</label>
                        <input type="text" name="varian[${varianId}][detail][${detailCounter}][nama]" class="form-control" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required">Stok</label>
                        <input type="number" name="varian[${varianId}][detail][${detailCounter}][stok]" class="form-control" required>
                    </div>
                </div>
            </div>
        `;
        
        // Find the correct "Add Detail" button
        const addButtons = detailWrapper.getElementsByTagName('button');
        let addButton = null;
        
        // Find button with "+ Tambah Detail" text
        for (let i = 0; i < addButtons.length; i++) {
            if (addButtons[i].textContent.includes('Tambah Detail')) {
                addButton = addButtons[i];
                break;
            }
        }
        
        // If button found, insert before it
        if (addButton) {
            detailWrapper.insertBefore(newDetail, addButton);
        } else {
            // If not found, append to wrapper
            detailWrapper.appendChild(newDetail);
        }
    }

    // Remove variant
    function removeVarian(button) {
        const varianContainer = button.closest('.variant-container');
        if (document.querySelectorAll('.variant-container').length > 1) {
            varianContainer.remove();
        } else {
            alert('Produk harus memiliki minimal 1 varian');
        }
    }

    // Remove detail
    function removeDetail(button, varianId) {
        const detailContainer = button.closest('.detail-container');
        const detailContainers = document.querySelectorAll(`#detail-wrapper-${varianId} .detail-container`);
        
        if (detailContainers.length > 1) {
            detailContainer.remove();
            
            // Update numbers for remaining details
            const remainingDetails = document.querySelectorAll(`#detail-wrapper-${varianId} .detail-container`);
            remainingDetails.forEach((detail, index) => {
                const header = detail.querySelector('.detail-header h6');
                if (header) header.innerHTML = `<i class="fas fa-circle-notch mr-2"></i>Detail #${index + 1}`;
                
                // Update input names
                const inputs = detail.querySelectorAll('input');
                inputs.forEach(input => {
                    input.name = input.name.replace(/detail\]\[\d+\]/, `detail][${index + 1}]`);
                });
            });
            
            // Update counter
            detailCounters[varianId] = remainingDetails.length;
        } else {
            alert('Varian harus memiliki minimal 1 detail');
        }
    }
</script>

@endsection