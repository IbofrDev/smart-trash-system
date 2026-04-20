@extends('layouts.admin')

@section('title', 'Edit Lokasi')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama Form */
    .form-card { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03); background-color: #ffffff; overflow: hidden; }
    .form-header { background-color: #f9fafb; padding: 1.5rem 2rem; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; gap: 1rem; }

    /* Styling Input Modern */
    .form-label { font-weight: 600; color: #4b5563; font-size: 0.9rem; margin-bottom: 0.5rem; }
    .input-group-custom { position: relative; margin-bottom: 1.5rem; }
    .input-group-custom i { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 1.1rem; z-index: 10; transition: color 0.3s; }
    .input-group-custom i.textarea-icon { top: 1.2rem; transform: none; }
    
    .form-control-modern { width: 100%; padding: 0.8rem 1rem 0.8rem 3.2rem; border: 1.5px solid #e5e7eb; border-radius: 12px; font-size: 0.95rem; color: #1f2937; background-color: #fcfcfc; transition: all 0.3s; }
    textarea.form-control-modern { padding-top: 1rem; min-height: 100px; }
    
    .form-control-modern:focus { outline: none; border-color: #14b8a6; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.1); }
    .form-control-modern:focus + i, .input-group-custom input:focus ~ i, .input-group-custom textarea:focus ~ i { color: #14b8a6; }

    /* Panel Informasi Kiri (Teal) */
    .info-panel { background: linear-gradient(135deg, #14b8a6 0%, #0f766e 100%); border-radius: 16px; padding: 2.5rem 2rem; height: 100%; color: #ffffff; box-shadow: 0 10px 25px rgba(13, 148, 136, 0.2); }
    .info-panel-icon { width: 80px; height: 80px; background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin-bottom: 1.5rem; }
    .info-panel h4 { font-weight: 800; margin-bottom: 1rem; line-height: 1.3; }
    .info-panel p { color: rgba(255, 255, 255, 0.9); font-size: 0.95rem; line-height: 1.6; }
    
    /* Box Peringatan di Panel Kiri */
    .warning-box { background-color: rgba(0,0,0,0.15); padding: 1.25rem; border-radius: 12px; margin-top: 1.5rem; border: 1px solid rgba(255,255,255,0.2); border-left: 4px solid #fde047; }
    .warning-box strong { color: #fde047; display: block; margin-bottom: 0.5rem; }
</style>

<div class="d-flex align-items-center mb-4 animate-fade-up">
    <a href="{{ route('admin.lokasi.index') }}" class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
        <i class="bi bi-arrow-left fs-5"></i>
    </a>
    <div>
        <h4 class="fw-bold text-dark mb-1">Perbarui Data Lokasi</h4>
        <p class="text-muted mb-0 small">Ubah informasi nama gedung, patokan, atau titik koordinat GPS.</p>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <div class="info-panel-icon">
                <i class="bi bi-geo-alt text-white"></i>
            </div>
            <h4 class="mb-2">{{ $lokasi->nama_lokasi }}</h4>
            <span class="badge bg-white text-teal rounded-pill px-3 py-1 mb-3" style="color: #0f766e;"><i class="bi bi-clock-history me-1"></i> Dibuat: {{ $lokasi->created_at->format('M Y') }}</span>
            
            <p>Anda sedang mengubah data referensi fisik untuk titik penempatan alat IoT.</p>
            
            <div class="warning-box">
                <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Perhatian</strong>
                <span class="small text-white-50">Mengubah titik koordinat tidak akan mereset alat secara otomatis, namun akan mengubah visual titik merah pada peta monitoring admin. Pastikan koordinat yang dimasukkan akurat.</span>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <i class="bi bi-pencil-square fs-3 text-teal" style="color: #14b8a6;"></i>
                <h5 class="m-0 fw-bold text-dark">Formulir Edit Lokasi</h5>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('admin.lokasi.update', $lokasi) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Nama Lokasi / Gedung <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="text" name="nama_lokasi" class="form-control-modern @error('nama_lokasi') is-invalid @enderror" 
                                    value="{{ old('nama_lokasi', $lokasi->nama_lokasi) }}" required>
                                <i class="bi bi-building"></i>
                                @error('nama_lokasi') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Alamat / Patokan Area</label>
                            <div class="input-group-custom">
                                <textarea name="alamat" class="form-control-modern @error('alamat') is-invalid @enderror" 
                                    rows="3">{{ old('alamat', $lokasi->alamat) }}</textarea>
                                <i class="bi bi-map textarea-icon"></i>
                                @error('alamat') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Titik Koordinat (Latitude, Longitude)</label>
                            <div class="input-group-custom">
                                <input type="text" name="koordinat" class="form-control-modern @error('koordinat') is-invalid @enderror" 
                                    value="{{ old('koordinat', $lokasi->koordinat) }}" placeholder="-3.316694, 114.590111">
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
                        <button type="submit" class="btn rounded-pill px-5 fw-bold shadow-sm d-flex align-items-center gap-2" style="background-color: #14b8a6; color: white; border: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="bi bi-save2"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection