@extends('layouts.admin')

@section('title', 'Edit Jenis Sampah')

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
    
    .form-control-modern:focus { outline: none; border-color: #f59e0b; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1); }
    .form-control-modern:focus + i, .input-group-custom input:focus ~ i, .input-group-custom textarea:focus ~ i, .input-group-custom select:focus ~ i { color: #f59e0b; }

    /* Styling Select / Dropdown Bawaan */
    select.form-control-modern {
        appearance: none; -moz-appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 1.2rem center; background-size: 16px 12px;
    }

    /* Panel Informasi Kiri (Tema Amber/Gold) */
    .info-panel { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 16px; padding: 2.5rem 2rem; height: 100%; color: #ffffff; box-shadow: 0 10px 25px rgba(217, 119, 6, 0.2); position: relative; overflow: hidden; }
    .info-panel::after { content: '\F5D3'; font-family: 'bootstrap-icons'; position: absolute; right: -20px; bottom: -20px; font-size: 12rem; opacity: 0.1; transform: rotate(-15deg); }
    
    .info-panel-icon { width: 80px; height: 80px; background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin-bottom: 1.5rem; }
    .info-panel h4 { font-weight: 800; margin-bottom: 0.5rem; line-height: 1.3; }
    
    /* Box Warning di Panel Kiri */
    .warning-box { background-color: rgba(0,0,0,0.15); padding: 1.25rem; border-radius: 12px; margin-top: 2rem; border: 1px solid rgba(255,255,255,0.2); border-left: 4px solid #fef08a; }
    .warning-box strong { color: #fef08a; display: block; margin-bottom: 0.5rem; font-size: 0.95rem; }
    .warning-box p { margin: 0; font-size: 0.85rem; color: #fef3c7; line-height: 1.5; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4 animate-fade-up">
    <div class="d-flex align-items-center">
        <a href="{{ route('admin.jenis-sampah.index') }}" class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-1">Perbarui Kategori Sampah</h4>
            <p class="text-muted mb-0 small">Ubah informasi nama, deskripsi, atau nilai tukar poin.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <div class="info-panel-icon">
                <i class="bi bi-tags-fill text-white"></i>
            </div>
            <h4 class="mb-2">{{ $jenisSampah->nama }}</h4>
            <span class="badge bg-white text-warning rounded-pill px-3 py-1 mb-3 fw-bold">
                <i class="bi bi-star-fill me-1"></i> Saat ini: {{ number_format($jenisSampah->poin_per_kg) }} Poin / {{ $jenisSampah->satuan }}
            </span>
            
            <p class="text-white-50 text-sm">Pastikan nilai konversi poin diatur secara seimbang agar sistem gamifikasi bank sampah tetap sehat.</p>
            
            <div class="warning-box">
                <strong><i class="bi bi-info-circle-fill me-2"></i>Catatan Konversi</strong>
                <p>Perubahan nilai "Poin per Kg" hanya akan berlaku untuk transaksi baru. Transaksi masa lalu tidak akan terpengaruh oleh perubahan harga ini.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <i class="bi bi-pencil-square fs-3" style="color: #f59e0b;"></i>
                <h5 class="m-0 fw-bold text-dark">Formulir Detail Kategori</h5>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('admin.jenis-sampah.update', $jenisSampah) }}" method="POST">
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

                    <div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top">
                        <a href="{{ route('admin.jenis-sampah.index') }}" class="btn btn-light rounded-pill px-4 fw-medium border shadow-sm">
                            Batal
                        </a>
                        <button type="submit" class="btn rounded-pill px-5 fw-bold shadow-sm d-flex align-items-center gap-2" style="background-color: #f59e0b; color: white; border: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="bi bi-save2"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection