@extends('layouts.admin')

@section('title', 'Edit Perangkat IoT')

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
    
    .form-control-modern { width: 100%; padding: 0.8rem 1rem 0.8rem 3.2rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; color: #0f172a; background-color: #f8fafc; transition: all 0.3s; }
    
    .form-control-modern:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
    .form-control-modern:focus + i, .input-group-custom input:focus ~ i, .input-group-custom select:focus ~ i { color: #10b981; }

    /* Styling Select / Dropdown Bawaan */
    select.form-control-modern {
        appearance: none; -moz-appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748b' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 1.2rem center; background-size: 16px 12px;
    }

    /* Input Group Khusus Angka (Kapasitas) */
    .input-group-text-right {
        position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
        color: #64748b; font-weight: 700; font-size: 0.85rem; pointer-events: none;
    }
    .input-with-text-right { padding-right: 3rem !important; }

    /* Panel Informasi Kiri (Minimalist Emerald) */
    .info-panel { background-color: #ffffff; border: 1px solid #f1f5f9; border-left: 6px solid #10b981; border-radius: 12px; padding: 2.5rem 2rem; height: 100%; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); }
    .info-panel-icon { width: 64px; height: 64px; background-color: #ecfdf5; color: #047857; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 1.5rem; }
    .info-panel h4 { font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; line-height: 1.3; }
    .info-panel p { color: #64748b; font-size: 0.95rem; line-height: 1.6; }
    
    /* Box Device ID di Panel Kiri - Light Theme */
    .device-id-box { background-color: #f8fafc; padding: 1.25rem; border-radius: 8px; margin-top: 2rem; border: 1px dashed #cbd5e1; }
    .device-id-box label { display: block; font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 700; margin-bottom: 6px; }
    .device-id-box .value { color: #0f172a; font-size: 1rem; font-weight: 700; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4 animate-fade-up">
    <div class="d-flex align-items-center">
        <a href="{{ route($routePrefix . '.bak-sampah.index') }}" class="btn btn-light rounded-3 shadow-sm me-3 bg-white border" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Perbarui Pengaturan Perangkat</h4>
            <p class="text-muted mb-0 small">Ubah konfigurasi perangkat bak sampah IoT ({{ $bakSampah->nama }}).</p>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <div class="info-panel-icon">
                <i class="bi bi-cpu-fill"></i>
            </div>
            <h4>{{ $bakSampah->nama }}</h4>
            
            <div class="mb-4 mt-2">
                @if($bakSampah->status === 'aktif')
                    <span class="badge" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; padding: 6px 12px; border-radius: 6px; display: inline-flex; align-items: center;">
                        <i class="bi bi-circle-fill small me-2" style="font-size: 8px;"></i> Aktif (Online)
                    </span>
                @elseif($bakSampah->status === 'maintenance')
                    <span class="badge" style="background: #fffbeb; color: #d97706; border: 1px solid #fde68a; padding: 6px 12px; border-radius: 6px; display: inline-flex; align-items: center;">
                        <i class="bi bi-tools me-2"></i> Maintenance
                    </span>
                @else
                    <span class="badge" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 12px; border-radius: 6px; display: inline-flex; align-items: center;">
                        <i class="bi bi-power me-2"></i> Nonaktif
                    </span>
                @endif
            </div>
            
            <p>Pastikan perubahan kapasitas maksimum disesuaikan dengan volume fisik bak sampah sebenarnya untuk menghindari error sensor.</p>
            
            <div class="device-id-box">
                <label><i class="bi bi-geo-alt-fill me-1 text-success"></i>Terhubung dengan Lokasi</label>
                <div class="value">{{ $bakSampah->lokasi->nama_lokasi ?? 'Belum Diatur' }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <h5 class="m-0 fw-bold" style="color: #0f172a;"><i class="bi bi-sliders text-success me-2"></i>Konfigurasi Sistem IoT</h5>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="{{ route($routePrefix . '.bak-sampah.update', $bakSampah) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Nama Perangkat / ID <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="text" name="nama" class="form-control-modern @error('nama') is-invalid @enderror" 
                                    value="{{ old('nama', $bakSampah->nama) }}" required>
                                <i class="bi bi-hdd-network"></i>
                                @error('nama') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Lokasi Penempatan FIsik <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <select name="lokasi_id" class="form-control-modern @error('lokasi_id') is-invalid @enderror" required>
                                    <option value="" disabled>Pilih titik lokasi perangkat...</option>
                                    @foreach($lokasis as $lokasi)
                                        <option value="{{ $lokasi->id }}" {{ old('lokasi_id', $bakSampah->lokasi_id) == $lokasi->id ? 'selected' : '' }}>
                                            {{ $lokasi->nama_lokasi }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-geo-alt"></i>
                                @error('lokasi_id') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <label class="form-label">Status Operasional <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <select name="status" class="form-control-modern @error('status') is-invalid @enderror" required>
                                    <option value="aktif" {{ old('status', $bakSampah->status) == 'aktif' ? 'selected' : '' }}>Aktif (Siap Menerima Setoran)</option>
                                    <option value="maintenance" {{ old('status', $bakSampah->status) == 'maintenance' ? 'selected' : '' }}>Dalam Perawatan (Maintenance)</option>
                                    <option value="nonaktif" {{ old('status', $bakSampah->status) == 'nonaktif' ? 'selected' : '' }}>Dimatikan Sementara (Nonaktif)</option>
                                </select>
                                <i class="bi bi-activity"></i>
                                @error('status') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                                            <div class="col-md-6 mt-2">
                            <label class="form-label">Kapasitas Maksimal Fisik</label>
                            <div class="input-group-custom">
                                <input type="number" step="0.01" name="kapasitas_max" class="form-control-modern input-with-text-right @error('kapasitas_max') is-invalid @enderror"
                                    value="{{ old('kapasitas_max', $bakSampah->kapasitas_max) }}" placeholder="Contoh: 50.00">
                                <i class="bi bi-speedometer2"></i>
                                <span class="input-group-text-right">KG</span>
                                @error('kapasitas_max')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <label class="form-label">Kapasitas Maksimal Botol <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="number" min="1" name="kapasitas_max_botol" class="form-control-modern input-with-text-right @error('kapasitas_max_botol') is-invalid @enderror"
                                    value="{{ old('kapasitas_max_botol', $bakSampah->kapasitas_max_botol ?? 100) }}" placeholder="Contoh: 100" required>
                                <i class="bi bi-archive"></i>
                                <span class="input-group-text-right">PCS</span>
                                @error('kapasitas_max_botol')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top" style="border-color: #f1f5f9 !important;">
                        <a href="{{ route($routePrefix . '.bak-sampah.index') }}" class="btn bg-white border rounded-3 px-4 fw-bold" style="color: #64748b;">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success rounded-3 px-5 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: #10b981; border: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="bi bi-cloud-upload"></i> Simpan Konfigurasi
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection 