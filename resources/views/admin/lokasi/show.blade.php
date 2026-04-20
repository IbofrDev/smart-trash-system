@extends('layouts.admin')

@section('title', 'Detail Lokasi')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Modern */
    .custom-card { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03); background-color: #ffffff; overflow: hidden; }
    .card-header-modern { background-color: #ffffff; border-bottom: 1px solid #f3f4f6; padding: 1.25rem 1.5rem; font-weight: 700; color: #1f2937; }

    /* Info Grid Layout */
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; padding: 1.5rem; }
    .info-item { display: flex; align-items: flex-start; gap: 1rem; }
    .info-icon { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
    
    .icon-teal { background: linear-gradient(135deg, #ccfbf1, #99f6e4); color: #0d9488; }
    .icon-blue { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #2563eb; }
    .icon-orange { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; }
    
    .info-label { font-size: 0.8rem; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.2rem; }
    .info-value { font-size: 1rem; color: #1f2937; font-weight: 600; line-height: 1.4; }

    /* Map Placeholder (Visual Element) */
    .map-placeholder {
        background: linear-gradient(rgba(243, 244, 246, 0.8), rgba(243, 244, 246, 0.9)), 
                    url('data:image/svg+xml,%3Csvg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="%23d1d5db" fill-opacity="0.4" fill-rule="evenodd"%3E%3Ccircle cx="3" cy="3" r="3"/%3E%3Cg/%3E%3C/svg%3E');
        height: 200px;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed #d1d5db;
        position: relative;
    }
    .map-pin-pulse {
        width: 20px; height: 20px; background-color: #14b8a6; border-radius: 50%; position: relative;
    }
    .map-pin-pulse::after {
        content: ""; position: absolute; width: 100%; height: 100%; background-color: #14b8a6;
        border-radius: 50%; animation: pulse-ring 1.5s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite;
    }
    @keyframes pulse-ring { 0% { transform: scale(0.8); opacity: 1; } 100% { transform: scale(3); opacity: 0; } }

    /* Styling Tabel Modern */
    .table-modern { margin-bottom: 0; width: 100%; }
    .table-modern th { background-color: #f9fafb; color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
    .table-modern td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f3f4f6; color: #4b5563; }
    .table-modern tbody tr { transition: background-color 0.2s; }
    .table-modern tbody tr:hover { background-color: #f9fafb; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4 animate-fade-up">
    <div class="d-flex align-items-center">
        <a href="{{ route('admin.lokasi.index') }}" class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-1">Detail Area: {{ $lokasi->nama_lokasi }}</h4>
            <p class="text-muted mb-0 small">Melihat informasi spesifik dan daftar alat yang terpasang di lokasi ini.</p>
        </div>
    </div>
    <a href="{{ route('admin.lokasi.edit', $lokasi) }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm d-flex align-items-center gap-2">
        <i class="bi bi-pencil-square"></i> Edit Lokasi
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        
        <div class="card custom-card mb-4 animate-fade-up" style="animation-delay: 0.1s;">
            <div class="card-header-modern d-flex align-items-center">
                <i class="bi bi-info-circle-fill text-primary me-2"></i> Informasi Umum
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-icon icon-teal"><i class="bi bi-building"></i></div>
                    <div>
                        <div class="info-label">Nama Lokasi</div>
                        <div class="info-value">{{ $lokasi->nama_lokasi }}</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon icon-blue"><i class="bi bi-map"></i></div>
                    <div>
                        <div class="info-label">Alamat Lengkap</div>
                        <div class="info-value">{{ $lokasi->alamat ?? 'Tidak ada deskripsi alamat.' }}</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon icon-orange"><i class="bi bi-calendar3"></i></div>
                    <div>
                        <div class="info-label">Terdaftar Sejak</div>
                        <div class="info-value">{{ $lokasi->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card custom-card animate-fade-up" style="animation-delay: 0.2s;">
            <div class="card-header-modern d-flex justify-content-between align-items-center">
                <span><i class="bi bi-hdd-stack-fill text-success me-2"></i> Alat IoT (Bak Sampah) di Lokasi Ini</span>
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">{{ $lokasi->bakSampahs->count() }} Unit Terpasang</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th class="ps-4">Nama / ID Alat</th>
                                <th>Kapasitas Max</th>
                                <th>Status Operasional</th>
                                <th class="text-end pe-4">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lokasi->bakSampahs as $bak)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $bak->nama }}</div>
                                    <small class="text-muted"><i class="bi bi-cpu me-1"></i> Sensor Terhubung</small>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $bak->kapasitas_max ? number_format($bak->kapasitas_max, 1) . ' kg' : 'Belum diatur' }}</span>
                                </td>
                                <td>
                                    @if($bak->status === 'aktif')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> Aktif</span>
                                    @elseif($bak->status === 'maintenance')
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-1 rounded-pill"><i class="bi bi-tools me-1"></i> Maintenance</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1 rounded-pill"><i class="bi bi-x-circle-fill me-1"></i> Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.bak-sampah.show', $bak) }}" class="btn btn-sm btn-light text-primary rounded-circle" title="Lihat Sensor" style="width: 32px; height: 32px; padding: 0; line-height: 32px;">
                                        <i class="bi bi-arrow-right-short fs-5"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 border-0">
                                    <i class="bi bi-hdd-network text-light mb-3" style="font-size: 3rem; display: block;"></i>
                                    <div class="fw-bold text-secondary mb-1">Belum Ada Perangkat</div>
                                    <small class="text-muted">Belum ada bak sampah IoT yang ditempatkan di lokasi ini.</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.3s;">
        <div class="card custom-card h-100 p-4">
            <h6 class="fw-bold text-dark mb-4"><i class="bi bi-geo-alt-fill text-danger me-2"></i> Titik Geografis (GPS)</h6>
            
            <div class="map-placeholder mb-4">
                @if($lokasi->koordinat)
                    <div class="map-pin-pulse"></div>
                    <div class="mt-3 fw-bold text-dark bg-white px-3 py-1 rounded-pill shadow-sm" style="font-size: 0.8rem;">
                        <i class="bi bi-crosshair text-primary me-1"></i> Signal Ditemukan
                    </div>
                @else
                    <i class="bi bi-slash-circle text-muted fs-1 mb-2"></i>
                    <small class="text-muted fw-medium">Koordinat Belum Diatur</small>
                @endif
            </div>

            <div class="bg-light rounded-3 p-3 text-center border">
                <div class="text-muted small text-uppercase fw-bold mb-1">Latitude, Longitude</div>
                @if($lokasi->koordinat)
                    <code class="fs-6 text-dark fw-bold">{{ $lokasi->koordinat }}</code>
                @else
                    <span class="text-muted fst-italic small">-</span>
                @endif
            </div>

        </div>
    </div>
</div>

@endsection