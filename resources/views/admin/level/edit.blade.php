@extends('layouts.admin')

@section('title', 'Edit Level Gamifikasi')

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
    .input-group-custom i.input-icon { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.1rem; z-index: 10; transition: color 0.3s; }
    
    .form-control-modern { width: 100%; padding: 0.8rem 1rem 0.8rem 3.2rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; color: #0f172a; background-color: #f8fafc; transition: all 0.3s; }
    
    .form-control-modern:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
    .form-control-modern:focus + i.input-icon { color: #10b981; }

    /* Input Group Khusus Angka (Poin) */
    .input-group-text-right {
        position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
        color: #64748b; font-weight: 700; font-size: 0.85rem; pointer-events: none;
    }
    .input-with-text-right { padding-right: 3.5rem !important; }

    /* Panel Informasi Kiri (Minimalist Emerald) */
    .info-panel { background-color: #ffffff; border: 1px solid #f1f5f9; border-left: 6px solid #10b981; border-radius: 12px; padding: 2.5rem 2rem; height: 100%; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); }
    .info-panel-icon { width: 64px; height: 64px; background-color: #ecfdf5; color: #047857; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 1.5rem; }
    .info-panel h4 { font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; line-height: 1.3; }
    .info-panel p { color: #64748b; font-size: 0.95rem; line-height: 1.6; }
    
    /* Box Warning di Panel Kiri - Danger Zone Style */
    .warning-box { background-color: #fff1f2; padding: 1.25rem; border-radius: 8px; margin-top: 2rem; border: 1px dashed #fecaca; }
    .warning-box strong { color: #dc2626; display: block; margin-bottom: 0.5rem; font-size: 0.95rem; font-weight: 700; }
    .warning-box p { margin: 0; font-size: 0.85rem; color: #64748b; line-height: 1.5; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4 animate-fade-up">
    <div class="d-flex align-items-center">
        <a href="{{ route('admin.level.index') }}" class="btn btn-light rounded-3 shadow-sm me-3 bg-white border" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Perbarui Pengaturan Tier</h4>
            <p class="text-muted mb-0 small">Ubah nama peringkat, urutan, atau syarat batas poinnya.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <div class="info-panel-icon">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <h4 class="mb-2">{{ $level->nama_level }}</h4>
            <div class="mb-3">
                <span class="badge" style="background: #f8fafc; color: #0f172a; border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 6px; display: inline-flex; align-items: center;">
                    <i class="bi bi-bar-chart-steps text-success me-2"></i> Tier Urutan Ke-{{ $level->urutan }}
                </span>
            </div>
            
            <p>Anda sedang mengubah parameter peringkat yang menjadi target pencapaian para mahasiswa.</p>
            
            <div class="warning-box">
                <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Dampak Perubahan</strong>
                <p>Mengubah batas Min/Max poin dapat menyebabkan beberapa mahasiswa otomatis naik atau turun peringkat (Rank Drop/Up) pada saat sistem mengkalkulasi ulang total poin mereka.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <h5 class="m-0 fw-bold" style="color: #0f172a;"><i class="bi bi-pencil-square text-success me-2"></i>Formulir Konfigurasi Peringkat</h5>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('admin.level.update', $level) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Nama Peringkat / Level <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="text" name="nama_level" class="form-control-modern fw-bold text-dark @error('nama_level') is-invalid @enderror" 
                                    value="{{ old('nama_level', $level->nama_level) }}" required autofocus>
                                <i class="bi bi-award-fill input-icon text-success"></i>
                                @error('nama_level') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mt-2">
                            <label class="form-label">Syarat Minimal <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="number" name="min_poin" class="form-control-modern input-with-text-right fw-semibold @error('min_poin') is-invalid @enderror" 
                                    value="{{ old('min_poin', $level->min_poin) }}" min="0" required>
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
                                    value="{{ old('max_poin', $level->max_poin) }}" min="0" required>
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
                                    value="{{ old('urutan', $level->urutan) }}" min="1" required>
                                <i class="bi bi-sort-numeric-down input-icon"></i>
                                @error('urutan') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top" style="border-color: #f1f5f9 !important;">
                        <a href="{{ route('admin.level.index') }}" class="btn bg-white border rounded-3 px-4 fw-bold" style="color: #64748b;">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success rounded-3 px-5 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: #10b981; border: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="bi bi-save2"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection