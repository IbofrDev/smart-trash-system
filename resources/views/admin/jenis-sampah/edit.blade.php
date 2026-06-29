@extends('layouts.admin')

@section('title', 'Edit Jenis Sampah')

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
    .input-group-custom i { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.1rem; z-index: 10; transition: color 0.3s; }
    .input-group-custom i.textarea-icon { top: 1.2rem; transform: none; }
    
    .form-control-modern { width: 100%; padding: 0.8rem 1rem 0.8rem 3.2rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; color: #0f172a; background-color: #f8fafc; transition: all 0.3s; }
    textarea.form-control-modern { padding-top: 1rem; min-height: 100px; }
    
    .form-control-modern:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
    .form-control-modern:focus + i, .input-group-custom input:focus ~ i, .input-group-custom textarea:focus ~ i, .input-group-custom select:focus ~ i { color: #10b981; }

    /* Styling Select / Dropdown Bawaan */
    select.form-control-modern {
        appearance: none; -moz-appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748b' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 1.2rem center; background-size: 16px 12px;
    }

    /* Panel Informasi Kiri (Minimalist Emerald) */
    .info-panel { 
        background-color: #ffffff; 
        border: 1px solid #f1f5f9; 
        border-left: 6px solid #10b981; 
        border-radius: 12px; 
        padding: 2.5rem 2rem; 
        height: 100%; 
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); 
    }
    .info-panel-icon { 
        width: 64px; height: 64px; 
        background-color: #ecfdf5; color: #047857; 
        border-radius: 10px; display: flex; align-items: center; justify-content: center; 
        font-size: 2rem; margin-bottom: 1.5rem; 
    }
    .info-panel h4 { font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; line-height: 1.3; }
    .info-panel p { color: #64748b; font-size: 0.95rem; line-height: 1.6; }
    
    /* Box Warning di Panel Kiri (Kuning Pastel Dashed) */
    .warning-box { 
        background-color: #fffbeb; 
        padding: 1.25rem; 
        border-radius: 8px; 
        margin-top: 2rem; 
        border: 1px dashed #fde68a; 
    }
    .warning-box strong { color: #d97706; display: block; margin-bottom: 0.5rem; font-size: 0.95rem; font-weight: 700; }
    .warning-box p { margin: 0; font-size: 0.85rem; color: #64748b; line-height: 1.5; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4 animate-fade-up">
    <div class="d-flex align-items-center">
        <a href="{{ route($routePrefix . '.jenis-sampah.index') }}" class="btn btn-light rounded-3 shadow-sm me-3 bg-white border" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Perbarui Kategori Sampah</h4>
            <p class="text-muted mb-0 small">Ubah informasi nama, deskripsi, atau nilai tukar poin.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <div class="info-panel-icon">
                <i class="bi bi-tags-fill"></i>
            </div>
            <h4 class="mb-2">{{ $jenisSampah->nama }}</h4>
            <div class="mb-3">
                <span class="badge" style="background: #f8fafc; color: #0f172a; border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 6px; display: inline-flex; align-items: center; font-weight: 600;">
                    <i class="bi bi-star-fill text-warning me-2"></i> Saat ini: {{ number_format($jenisSampah->poin_per_kg) }} Poin / {{ $jenisSampah->satuan }}
                </span>
            </div>
            
            <p>Pastikan nilai konversi poin diatur secara seimbang agar sistem gamifikasi bank sampah tetap sehat.</p>
            
            <div class="warning-box">
                <strong><i class="bi bi-info-circle-fill me-2"></i>Catatan Konversi</strong>
                <p>Perubahan nilai "Poin per Kg" hanya akan berlaku untuk transaksi baru. Transaksi masa lalu tidak akan terpengaruh oleh perubahan harga ini.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <h5 class="m-0 fw-bold" style="color: #0f172a;"><i class="bi bi-pencil-square text-success me-2"></i>Formulir Detail Kategori</h5>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="{{ route($routePrefix . '.jenis-sampah.update', $jenisSampah) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Nama Kategori Sampah <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="text" name="nama" class="form-control-modern @error('nama') is-invalid @enderror" 
                                    value="{{ old('nama', $jenisSampah->nama) }}" required autofocus>
                                <i class="bi bi-tag"></i>
                                @error('nama') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Deskripsi Singkat</label>
                            <div class="input-group-custom">
                                <textarea name="deskripsi" class="form-control-modern @error('deskripsi') is-invalid @enderror" 
                                    rows="2" placeholder="Jelaskan karakteristik sampah ini...">{{ old('deskripsi', $jenisSampah->deskripsi) }}</textarea>
                                <i class="bi bi-card-text textarea-icon"></i>
                                @error('deskripsi') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-5 mt-2">
                            <label class="form-label">Nilai Konversi (Poin) <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="number" name="poin_per_kg" class="form-control-modern fw-bold text-success @error('poin_per_kg') is-invalid @enderror" 
                                    value="{{ old('poin_per_kg', $jenisSampah->poin_per_kg) }}" min="0" required>
                                <i class="bi bi-star-fill text-warning"></i>
                                @error('poin_per_kg') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3 mt-2">
                            <label class="form-label">Satuan <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="text" name="satuan" class="form-control-modern @error('satuan') is-invalid @enderror" 
                                    value="{{ old('satuan', $jenisSampah->satuan) }}" required>
                                <i class="bi bi-box"></i>
                                @error('satuan') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mt-2">
                            <label class="form-label">Status Kategori <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <select name="is_active" class="form-control-modern @error('is_active') is-invalid @enderror" required>
                                    <option value="1" {{ old('is_active', $jenisSampah->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active', $jenisSampah->is_active) == 0 ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                <i class="bi bi-toggle-on"></i>
                                @error('is_active') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top" style="border-color: #f1f5f9 !important;">
                        <a href="{{ route($routePrefix . '.jenis-sampah.index') }}" class="btn bg-white border rounded-3 px-4 fw-bold" style="color: #64748b;">
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