@extends('layouts.admin')

@section('title', 'Tambah Lokasi Baru')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama Form - Minimalis */
    .form-card { border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }
    .form-header { background-color: #ffffff; padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 1rem; }

    /* Styling Input Modern */
    .form-label { font-weight: 600; color: #475569; font-size: 0.85rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .input-group-custom { position: relative; margin-bottom: 1.5rem; }
    .input-group-custom i { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.1rem; z-index: 10; transition: color 0.3s; }
    .input-group-custom i.textarea-icon { top: 1.2rem; transform: none; }
    
    .form-control-modern { width: 100%; padding: 0.8rem 1rem 0.8rem 3.2rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; color: #0f172a; background-color: #f8fafc; transition: all 0.3s; }
    textarea.form-control-modern { padding-top: 1rem; min-height: 100px; }
    
    .form-control-modern:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
    .form-control-modern:focus + i, .input-group-custom input:focus ~ i, .input-group-custom textarea:focus ~ i { color: #10b981; }

    /* Alert / Bantuan Info Area Kiri (Minimalist Emerald) */
    .info-panel { background-color: #ffffff; border: 1px solid #f1f5f9; border-left: 6px solid #10b981; border-radius: 12px; padding: 2.5rem 2rem; height: 100%; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); }
    .info-panel-icon { width: 64px; height: 64px; background-color: #ecfdf5; color: #047857; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 1.5rem; }
    .info-panel h4 { font-weight: 800; color: #0f172a; margin-bottom: 1rem; }
    .info-panel p { color: #64748b; font-size: 0.95rem; line-height: 1.6; }
    
    .coord-example { background-color: #f8fafc; padding: 1.25rem; border-radius: 8px; margin-top: 1.5rem; border: 1px dashed #cbd5e1; }
    .coord-example code { color: #059669; font-size: 0.9rem; font-weight: 700; display: block; margin-top: 0.5rem; font-family: 'Courier New', monospace; }
</style>

<div class="d-flex align-items-center mb-4 animate-fade-up">
    <a href="{{ route('admin.lokasi.index') }}" class="btn btn-light rounded-3 shadow-sm me-3 bg-white border" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a;">
        <i class="bi bi-arrow-left fs-5"></i>
    </a>
    <div>
        <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Tambah Data Lokasi</h4>
        <p class="text-muted mb-0 small">Daftarkan titik area baru untuk penempatan bak sampah cerdas.</p>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <div class="info-panel-icon">
                <i class="bi bi-geo-alt-fill"></i>
            </div>
            <h4>Mapping Area</h4>
            <p>Data lokasi ini akan digunakan sebagai titik referensi fisik di mana alat (Bak Sampah IoT) ditempatkan di lingkungan kampus.</p>
            
            <div class="coord-example">
                <div class="fw-bold mb-1" style="color: #0f172a;"><i class="bi bi-crosshair me-2 text-success"></i>Format Koordinat</div>
                <small class="text-muted">Gunakan format Latitude dan Longitude yang dipisahkan oleh koma. Anda bisa mendapatkannya melalui Google Maps.</small>
                <code>-3.316694, 114.590111</code>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <h5 class="m-0 fw-bold" style="color: #0f172a;"><i class="bi bi-pin-map-fill text-success me-2"></i>Formulir Detail Lokasi</h5>
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

                    <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top" style="border-color: #f1f5f9 !important;">
                        <a href="{{ route('admin.lokasi.index') }}" class="btn bg-white border rounded-3 px-4 fw-bold" style="color: #64748b;">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success rounded-3 px-5 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: #10b981; border: none;">
                            <i class="bi bi-save2"></i> Simpan Lokasi
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection