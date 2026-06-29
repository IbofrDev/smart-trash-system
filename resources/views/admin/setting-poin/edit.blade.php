@extends('layouts.admin')

@section('title', 'Edit Parameter Sistem')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama Form - Radius Dikurangi */
    .form-card { border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }
    .form-header { background-color: #ffffff; padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 1rem; }

    /* Styling Input Modern */
    .form-label { font-weight: 600; color: #475569; font-size: 0.85rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .input-group-custom { position: relative; margin-bottom: 1.5rem; }
    .input-group-custom i.input-icon { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.1rem; z-index: 10; transition: color 0.3s; pointer-events: none; }
    .input-group-custom i.textarea-icon { top: 1.2rem; transform: none; }
    
    .form-control-modern { width: 100%; padding: 0.8rem 1rem 0.8rem 3.2rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; color: #0f172a; background-color: #f8fafc; transition: all 0.3s; }
    textarea.form-control-modern { padding-top: 1rem; min-height: 100px; }
    
    .form-control-modern:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
    .form-control-modern:focus + i.input-icon, .input-group-custom textarea:focus ~ i.input-icon { color: #10b981; }

    /* Input Terkunci (Readonly) */
    .input-locked { background-color: #f1f5f9; color: #64748b; font-family: monospace; font-weight: bold; pointer-events: none; border-color: #e2e8f0; }
    
    /* Panel Informasi Kiri (Minimalist Emerald) */
    .info-panel { background-color: #ffffff; border: 1px solid #f1f5f9; border-left: 6px solid #10b981; border-radius: 12px; padding: 2.5rem 2rem; height: 100%; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); }
    .info-panel-icon { width: 64px; height: 64px; background-color: #ecfdf5; color: #047857; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 1.5rem; }
    .info-panel h4 { font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; line-height: 1.3; }
    .info-panel p { color: #64748b; font-size: 0.95rem; line-height: 1.6; }
    
    /* Box Peringatan di Panel Kiri - Grey Dashed Clean Style */
    .sys-box { background-color: #f8fafc; padding: 1.25rem; border-radius: 8px; margin-top: 2rem; border: 1px dashed #cbd5e1; }
    .sys-box strong { color: #0f172a; display: block; margin-bottom: 0.5rem; font-size: 0.95rem; font-weight: 700; }
    .sys-box p { margin: 0; font-size: 0.85rem; color: #64748b; line-height: 1.5; }
</style>

<div class="d-flex align-items-center mb-4 animate-fade-up">
    <div class="d-flex align-items-center">
        <a href="{{ route($routePrefix . '.setting-poin.index') }}" class="btn btn-light rounded-3 shadow-sm me-3 bg-white border" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Konfigurasi Parameter</h4>
            <p class="text-muted mb-0 small">Perbarui nilai pada sistem engine gamifikasi.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <div class="info-panel-icon">
                <i class="bi bi-sliders"></i>
            </div>
            <h4 class="mb-2">System Core</h4>
            <p>Halaman ini diperuntukkan untuk mengubah parameter dasar (*System Key*) yang dibutuhkan oleh kode program.</p>
            
            <div class="sys-box">
                <strong><i class="bi bi-shield-lock-fill text-success me-2"></i>Variabel Terkunci</strong>
                <p>Nama Setting tidak dapat diubah karena merupakan kata kunci bawaan (*identifier*) yang dipanggil langsung oleh kode sistem backend Laravel.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <h5 class="m-0 fw-bold" style="color: #0f172a;"><i class="bi bi-wrench-adjustable text-success me-2"></i>Formulir Update Value</h5>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="{{ route($routePrefix . '.setting-poin.update', $settingPoin) }}" method="POST">
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
                                <input type="number" name="value" class="form-control-modern fw-bold text-success @error('value') is-invalid @enderror" 
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

                    <div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top" style="border-color: #f1f5f9 !important;">
                        <a href="{{ route($routePrefix . '.setting-poin.index') }}" class="btn bg-white border rounded-3 px-4 fw-bold" style="color: #64748b;">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success rounded-3 px-5 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: #10b981; border: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="bi bi-check2-circle"></i> Terapkan Konfigurasi
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection