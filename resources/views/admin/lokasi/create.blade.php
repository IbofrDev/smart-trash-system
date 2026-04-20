@extends('layouts.admin')

@section('title', 'Tambah Lokasi Baru')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up {
        opacity: 0;
        transform: translateY(15px);
        animation: fadeUp 0.5s ease-out forwards;
    }
    @keyframes fadeUp {
        to { opacity: 1; transform: translateY(0); }
    }

    /* Card Utama Form */
    .form-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        background-color: #ffffff;
        overflow: hidden;
    }
    
    .form-header {
        background-color: #f9fafb;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    /* Styling Input Modern */
    .form-label {
        font-weight: 600;
        color: #4b5563;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .input-group-custom {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .input-group-custom i {
        position: absolute;
        left: 1.2rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 1.1rem;
        z-index: 10;
        transition: color 0.3s;
    }

    /* Khusus untuk Textarea agar ikon berada di atas, bukan di tengah */
    .input-group-custom i.textarea-icon {
        top: 1.2rem;
        transform: none;
    }
    
    .form-control-modern {
        width: 100%;
        padding: 0.8rem 1rem 0.8rem 3.2rem; 
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.95rem;
        color: #1f2937;
        background-color: #fcfcfc;
        transition: all 0.3s;
    }

    textarea.form-control-modern {
        padding-top: 1rem;
        min-height: 100px;
    }
    
    .form-control-modern:focus {
        outline: none;
        border-color: #14b8a6; /* Warna Teal Focus */
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.1);
    }
    
    .form-control-modern:focus + i,
    .input-group-custom input:focus ~ i,
    .input-group-custom textarea:focus ~ i {
        color: #14b8a6;
    }

    /* Alert / Bantuan Info Area Kiri (Tema Teal/Map) */
    .info-panel {
        background: linear-gradient(135deg, #14b8a6 0%, #0f766e 100%);
        border-radius: 16px;
        padding: 2.5rem 2rem;
        height: 100%;
        color: #ffffff;
        box-shadow: 0 10px 25px rgba(13, 148, 136, 0.2);
    }
    .info-panel-icon {
        width: 80px;
        height: 80px;
        background-color: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin-bottom: 1.5rem;
    }
    .info-panel h4 { font-weight: 800; margin-bottom: 1rem; }
    .info-panel p { color: rgba(255, 255, 255, 0.9); font-size: 0.95rem; line-height: 1.6; }
    
    .coord-example {
        background-color: rgba(0,0,0,0.15);
        padding: 1rem;
        border-radius: 12px;
        margin-top: 1.5rem;
        border: 1px dashed rgba(255,255,255,0.3);
    }
    .coord-example code {
        color: #a7f3d0;
        font-size: 0.9rem;
        display: block;
        margin-top: 0.5rem;
    }
</style>

<div class="d-flex align-items-center mb-4 animate-fade-up">
    <a href="{{ route('admin.lokasi.index') }}" class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
        <i class="bi bi-arrow-left fs-5"></i>
    </a>
    <div>
        <h4 class="fw-bold text-dark mb-1">Tambah Data Lokasi</h4>
        <p class="text-muted mb-0 small">Daftarkan titik area baru untuk penempatan bak sampah cerdas.</p>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <div class="info-panel-icon">
                <i class="bi bi-geo-alt-fill text-white"></i>
            </div>
            <h4>Mapping Area</h4>
            <p>Data lokasi ini akan digunakan sebagai titik referensi fisik di mana alat (Bak Sampah IoT) ditempatkan di lingkungan kampus.</p>
            
            <div class="coord-example">
                <div class="fw-bold mb-1"><i class="bi bi-crosshair me-2"></i>Format Koordinat</div>
                <small class="text-white-50">Gunakan format Latitude dan Longitude yang dipisahkan oleh koma. Anda bisa mendapatkannya melalui Google Maps.</small>
                <code>-3.316694, 114.590111</code>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <i class="bi bi-pin-map-fill fs-3 text-teal" style="color: #14b8a6;"></i>
                <h5 class="m-0 fw-bold text-dark">Formulir Detail Lokasi</h5>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('admin.lokasi.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Nama Lokasi / Gedung <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="text" name="nama_lokasi" class="form-control-modern @error('nama_lokasi') is-invalid @enderror" 
                                    value="{{ old('nama_lokasi') }}" placeholder="Contoh: Gedung A Poliban..." required autofocus>
                                <i class="bi bi-building"></i>
                                @error('nama_lokasi') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Alamat / Deskripsi Area</label>
                            <div class="input-group-custom">
                                <textarea name="alamat" class="form-control-modern @error('alamat') is-invalid @enderror" 
                                    placeholder="Jelaskan patokan lokasi bak sampah secara spesifik (Contoh: Di dekat tangga lantai 1)...">{{ old('alamat') }}</textarea>
                                <i class="bi bi-map textarea-icon"></i>
                                @error('alamat') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Titik Koordinat (Opsional)</label>
                            <div class="input-group-custom">
                                <input type="text" name="koordinat" class="form-control-modern @error('koordinat') is-invalid @enderror" 
                                    value="{{ old('koordinat') }}" placeholder="-3.316694, 114.590111">
                                <i class="bi bi-crosshair"></i>
                                @error('koordinat') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                        <a href="{{ route('admin.lokasi.index') }}" class="btn btn-light rounded-pill px-4 fw-medium border shadow-sm">
                            Batal
                        </a>
                        <button type="submit" class="btn rounded-pill px-5 fw-bold shadow-sm d-flex align-items-center gap-2" style="background-color: #14b8a6; color: white; border: none;">
                            <i class="bi bi-save2"></i> Simpan Lokasi
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection