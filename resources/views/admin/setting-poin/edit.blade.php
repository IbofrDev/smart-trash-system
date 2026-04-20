@extends('layouts.admin')

@section('title', 'Edit Parameter Sistem')

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
    .input-group-custom i.input-icon { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 1.1rem; z-index: 10; transition: color 0.3s; pointer-events: none; }
    .input-group-custom i.textarea-icon { top: 1.2rem; transform: none; }
    
    .form-control-modern { width: 100%; padding: 0.8rem 1rem 0.8rem 3.2rem; border: 1.5px solid #e5e7eb; border-radius: 12px; font-size: 0.95rem; color: #1f2937; background-color: #fcfcfc; transition: all 0.3s; }
    textarea.form-control-modern { padding-top: 1rem; min-height: 100px; }
    
    .form-control-modern:focus { outline: none; border-color: #2563eb; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); }
    .form-control-modern:focus + i.input-icon, .input-group-custom textarea:focus ~ i.input-icon { color: #2563eb; }

    /* Input Terkunci (Readonly) */
    .input-locked { background-color: #f1f5f9; color: #64748b; font-family: monospace; font-weight: bold; pointer-events: none; border-color: #e2e8f0; }
    
    /* Panel Informasi Kiri (Tema Cobalt Blue) */
    .info-panel { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 16px; padding: 2.5rem 2rem; height: 100%; color: #ffffff; box-shadow: 0 10px 25px rgba(29, 78, 216, 0.2); position: relative; overflow: hidden; }
    .info-panel::after { content: '\F3E5'; font-family: 'bootstrap-icons'; position: absolute; right: -20px; bottom: -20px; font-size: 12rem; opacity: 0.1; transform: rotate(-15deg); }
    .info-panel-icon { width: 80px; height: 80px; background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin-bottom: 1.5rem; }
    .info-panel h4 { font-weight: 800; margin-bottom: 0.5rem; line-height: 1.3; }
    
    /* Box Peringatan di Panel Kiri */
    .sys-box { background-color: rgba(0,0,0,0.15); padding: 1.25rem; border-radius: 12px; margin-top: 2rem; border: 1px solid rgba(255,255,255,0.2); border-left: 4px solid #93c5fd; }
    .sys-box strong { color: #93c5fd; display: block; margin-bottom: 0.5rem; font-size: 0.95rem; }
    .sys-box p { margin: 0; font-size: 0.85rem; color: #dbeafe; line-height: 1.5; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4 animate-fade-up">
    <div class="d-flex align-items-center">
        <a href="{{ route('admin.setting-poin.index') }}" class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-1">Konfigurasi Parameter</h4>
            <p class="text-muted mb-0 small">Perbarui nilai pada sistem engine gamifikasi.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <div class="info-panel-icon">
                <i class="bi bi-sliders text-white"></i>
            </div>
            <h4 class="mb-2">System Core</h4>
            <p class="text-white-50 text-sm">Halaman ini diperuntukkan untuk mengubah parameter dasar (*System Key*) yang dibutuhkan oleh kode program.</p>
            
            <div class="sys-box">
                <strong><i class="bi bi-shield-lock-fill me-2"></i>Variabel Terkunci</strong>
                <p><strong>Nama Setting</strong> tidak dapat diubah karena merupakan kata kunci bawaan (*identifier*) yang dipanggil langsung oleh kode sistem backend Laravel.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <i class="bi bi-wrench-adjustable fs-3" style="color: #2563eb;"></i>
                <h5 class="m-0 fw-bold text-dark">Formulir Update Value</h5>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('admin.setting-poin.update', $settingPoin) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">System Key (Nama Setting)</label>
                            <div class="input-group-custom">
                                <input type="text" class="form-control-modern input-locked" value="{{ $settingPoin->nama_setting }}" tabindex="-1">
                                <i class="bi bi-lock-fill input-icon text-secondary"></i>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Nilai Parameter (Value) <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="number" name="value" class="form-control-modern fw-bold text-primary @error('value') is-invalid @enderror" 
                                    value="{{ old('value', $settingPoin->value) }}" min="0" required autofocus>
                                <i class="bi bi-input-cursor-text input-icon"></i>
                                @error('value') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Deskripsi Fungsional</label>
                            <div class="input-group-custom">
                                <textarea name="deskripsi" class="form-control-modern @error('deskripsi') is-invalid @enderror" 
                                    rows="3" placeholder="Tuliskan catatan fungsi dari parameter ini...">{{ old('deskripsi', $settingPoin->deskripsi) }}</textarea>
                                <i class="bi bi-info-square input-icon textarea-icon"></i>
                                @error('deskripsi') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top">
                        <a href="{{ route('admin.setting-poin.index') }}" class="btn btn-light rounded-pill px-4 fw-medium border shadow-sm">
                            Batal
                        </a>
                        <button type="submit" class="btn rounded-pill px-5 fw-bold shadow-sm d-flex align-items-center gap-2" style="background-color: #2563eb; color: white; border: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="bi bi-check2-circle"></i> Terapkan Konfigurasi
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection