@extends('layouts.admin')

@section('title', 'Edit Perangkat IoT')

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
    
    .form-control-modern { width: 100%; padding: 0.8rem 1rem 0.8rem 3.2rem; border: 1.5px solid #e5e7eb; border-radius: 12px; font-size: 0.95rem; color: #1f2937; background-color: #fcfcfc; transition: all 0.3s; }
    
    .form-control-modern:focus { outline: none; border-color: #3b82f6; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
    .form-control-modern:focus + i, .input-group-custom input:focus ~ i, .input-group-custom select:focus ~ i { color: #3b82f6; }

    /* Styling Select / Dropdown Bawaan */
    select.form-control-modern {
        appearance: none; -moz-appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 1.2rem center; background-size: 16px 12px;
    }

    /* Input Group Khusus Angka (Kapasitas) */
    .input-group-text-right {
        position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
        color: #6b7280; font-weight: 600; font-size: 0.9rem; pointer-events: none;
    }
    .input-with-text-right { padding-right: 3rem !important; }

    /* Panel Informasi Kiri (Dark Theme) */
    .info-panel { background: linear-gradient(135deg, #1f2937 0%, #111827 100%); border-radius: 16px; padding: 2.5rem 2rem; height: 100%; color: #ffffff; box-shadow: 0 10px 25px rgba(17, 24, 39, 0.2); position: relative; overflow: hidden; }
    .info-panel::after { content: '\F633'; font-family: 'bootstrap-icons'; position: absolute; right: -30px; bottom: -30px; font-size: 12rem; opacity: 0.03; transform: rotate(-15deg); }
    
    .info-panel-icon { width: 80px; height: 80px; background-color: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin-bottom: 1.5rem; border: 1px solid rgba(255,255,255,0.1); }
    .info-panel h4 { font-weight: 800; margin-bottom: 0.5rem; line-height: 1.3; }
    
    /* Box Device ID di Panel Kiri */
    .device-id-box { background-color: rgba(0,0,0,0.3); padding: 1rem; border-radius: 12px; margin-top: 2rem; border: 1px solid rgba(255,255,255,0.1); font-family: monospace; }
    .device-id-box label { display: block; font-size: 0.75rem; color: #9ca3af; text-transform: uppercase; margin-bottom: 4px; font-family: sans-serif;}
    .device-id-box .value { color: #10b981; font-size: 1.1rem; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4 animate-fade-up">
    <div class="d-flex align-items-center">
        <a href="{{ route('admin.bak-sampah.index') }}" class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-1">Perbarui Pengaturan Perangkat</h4>
            <p class="text-muted mb-0 small">Ubah konfigurasi perangkat bak sampah IoT ({{ $bakSampah->nama }}).</p>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <div class="info-panel-icon">
                <i class="bi bi-cpu-fill text-white"></i>
            </div>
            <h4>{{ $bakSampah->nama }}</h4>
            
            <div class="mb-4">
                @if($bakSampah->status === 'aktif')
                    <span class="badge bg-success bg-opacity-25 text-white border border-success px-3 py-1 rounded-pill"><i class="bi bi-circle-fill text-success small me-1"></i> Aktif (Online)</span>
                @elseif($bakSampah->status === 'maintenance')
                    <span class="badge bg-warning bg-opacity-25 text-warning border border-warning px-3 py-1 rounded-pill"><i class="bi bi-tools me-1"></i> Maintenance</span>
                @else
                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger px-3 py-1 rounded-pill"><i class="bi bi-power me-1"></i> Nonaktif</span>
                @endif
            </div>
            
            <p class="text-white-50 text-sm">Pastikan perubahan kapasitas maksimum disesuaikan dengan volume fisik bak sampah sebenarnya untuk menghindari error sensor.</p>
            
            <div class="device-id-box">
                <label><i class="bi bi-link-45deg me-1"></i>Terhubung dengan Lokasi</label>
                <div class="value text-white">{{ $bakSampah->lokasi->nama_lokasi ?? 'Belum Diatur' }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <i class="bi bi-sliders fs-3 text-primary"></i>
                <h5 class="m-0 fw-bold text-dark">Konfigurasi Sistem IoT</h5>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('admin.bak-sampah.update', $bakSampah) }}" method="POST">
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
                                    value="{{ old('kapasitas_max', $bakSampah->kapasitas_max) }}" placeholder="0.00">
                                <i class="bi bi-speedometer2"></i>
                                <span class="input-group-text-right">KG</span>
                                @error('kapasitas_max') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-5 pt-3 border-top">
                        <a href="{{ route('admin.bak-sampah.index') }}" class="btn btn-light rounded-pill px-4 fw-medium border shadow-sm">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm d-flex align-items-center gap-2" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="bi bi-cloud-upload"></i> Simpan Konfigurasi
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection