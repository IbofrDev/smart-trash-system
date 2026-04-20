@extends('layouts.admin')

@section('title', 'Tambah Peringkat Baru')

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
    .input-group-custom i.input-icon { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 1.1rem; z-index: 10; transition: color 0.3s; }
    
    .form-control-modern { width: 100%; padding: 0.8rem 1rem 0.8rem 3.2rem; border: 1.5px solid #e5e7eb; border-radius: 12px; font-size: 0.95rem; color: #1f2937; background-color: #fcfcfc; transition: all 0.3s; }
    
    .form-control-modern:focus { outline: none; border-color: #4f46e5; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
    .form-control-modern:focus + i.input-icon { color: #4f46e5; }

    /* Input Group Khusus Angka (Poin) */
    .input-group-text-right {
        position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
        color: #9ca3af; font-weight: 600; font-size: 0.85rem; pointer-events: none; letter-spacing: 0.5px;
    }
    .input-with-text-right { padding-right: 3.5rem !important; }

    /* Panel Informasi Kiri (Tema Indigo) */
    .info-panel { background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%); border-radius: 16px; padding: 2.5rem 2rem; height: 100%; color: #ffffff; box-shadow: 0 10px 25px rgba(67, 56, 202, 0.2); position: relative; overflow: hidden; }
    .info-panel::after { content: '\F5D6'; font-family: 'bootstrap-icons'; position: absolute; right: -20px; bottom: -20px; font-size: 12rem; opacity: 0.1; transform: rotate(-15deg); }
    
    .info-panel-icon { width: 80px; height: 80px; background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin-bottom: 1.5rem; }
    .info-panel h4 { font-weight: 800; margin-bottom: 0.5rem; line-height: 1.3; }
    
    /* Box Edukasi di Panel Kiri */
    .guide-box { background-color: rgba(0,0,0,0.15); padding: 1.25rem; border-radius: 12px; margin-top: 2rem; border: 1px solid rgba(255,255,255,0.2); border-left: 4px solid #fef08a; }
    .guide-box strong { color: #fef08a; display: block; margin-bottom: 0.5rem; font-size: 0.95rem; }
    .guide-box p { margin: 0; font-size: 0.85rem; color: #e0e7ff; line-height: 1.5; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4 animate-fade-up">
    <div class="d-flex align-items-center">
        <a href="{{ route('admin.level.index') }}" class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-1">Registrasi Tier Baru</h4>
            <p class="text-muted mb-0 small">Tambahkan peringkat baru ke dalam sistem gamifikasi.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <div class="info-panel-icon">
                <i class="bi bi-stars text-white"></i>
            </div>
            <h4 class="mb-2">Hierarki Level</h4>
            <p class="text-white-50 text-sm">Buat jenjang pangkat yang memotivasi mahasiswa untuk terus menyetorkan sampah.</p>
            
            <div class="guide-box">
                <strong><i class="bi bi-info-circle-fill me-2"></i>Hindari Overlap Poin</strong>
                <p>Pastikan batas <code>Min Poin</code> pada level baru ini lebih besar dari batas <code>Max Poin</code> level sebelumnya agar sistem tidak bingung dalam menentukan pangkat mahasiswa.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <i class="bi bi-plus-square-dotted fs-3" style="color: #4f46e5;"></i>
                <h5 class="m-0 fw-bold text-dark">Formulir Setup Peringkat</h5>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('admin.level.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Nama Peringkat / Level <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="text" name="nama_level" class="form-control-modern fw-bold text-dark @error('nama_level') is-invalid @enderror" 
                                    value="{{ old('nama_level') }}" placeholder="Contoh: Eco Champion, Silver, Gold..." required autofocus>
                                <i class="bi bi-award-fill input-icon text-warning"></i>
                                @error('nama_level') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mt-2">
                            <label class="form-label">Syarat Minimal <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="number" name="min_poin" class="form-control-modern input-with-text-right fw-semibold @error('min_poin') is-invalid @enderror" 
                                    value="{{ old('min_poin', 0) }}" min="0" required>
                                <i class="bi bi-arrow-down-circle input-icon"></i>
                                <span class="input-group-text-right">PTS</span>
                                @error('min_poin') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mt-2">
                            <label class="form-label">Batas Maksimal <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="number" name="max_poin" class="form-control-modern input-with-text-right fw-semibold @error('max_poin') is-invalid @enderror" 
                                    value="{{ old('max_poin') }}" placeholder="0" min="0" required>
                                <i class="bi bi-arrow-up-circle input-icon"></i>
                                <span class="input-group-text-right">PTS</span>
                                @error('max_poin') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mt-2">
                            <label class="form-label">Urutan Hierarki <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="number" name="urutan" class="form-control-modern @error('urutan') is-invalid @enderror" 
                                    value="{{ old('urutan', isset($maxUrutan) ? $maxUrutan + 1 : 1) }}" min="1" required>
                                <i class="bi bi-sort-numeric-down input-icon"></i>
                                @error('urutan') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top">
                        <a href="{{ route('admin.level.index') }}" class="btn btn-light rounded-pill px-4 fw-medium border shadow-sm">
                            Batal
                        </a>
                        <button type="submit" class="btn rounded-pill px-5 fw-bold shadow-sm d-flex align-items-center gap-2" style="background-color: #4f46e5; color: white; border: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="bi bi-plus-circle-fill"></i> Tambah Peringkat
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection