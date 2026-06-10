@extends('layouts.admin')

@section('title', 'Detail Lokasi')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Modern */
    .custom-card { border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }
    .card-header-modern { background-color: #ffffff; border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem; font-weight: 700; color: #0f172a; }

    /* Info Grid Layout (Soft Icon Boxes) */
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; padding: 1.5rem; }
    .info-item { display: flex; align-items: flex-start; gap: 1rem; }
    
    .icon-box-soft { width: 45px; height: 45px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; background-color: #f1f5f9; color: #64748b; }
    .icon-emerald { background-color: #ecfdf5; color: #047857; }
    
    .info-label { font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.3rem; display: block; }
    .info-value { font-size: 0.95rem; color: #1e293b; font-weight: 700; line-height: 1.4; }

    /* Map Placeholder (Minimalist) */
    .map-placeholder {
        background-color: #f8fafc;
        height: 200px;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 1px dashed #cbd5e1;
        position: relative;
    }
    .map-pin-pulse { width: 16px; height: 16px; background-color: #10b981; border-radius: 50%; position: relative; }
    .map-pin-pulse::after { content: ""; position: absolute; width: 100%; height: 100%; background-color: #10b981; border-radius: 50%; animation: pulse-ring 1.5s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite; }
    @keyframes pulse-ring { 0% { transform: scale(0.8); opacity: 1; } 100% { transform: scale(3); opacity: 0; } }

    /* Styling Tabel Clean */
    .table-clean { margin-bottom: 0; width: 100%; }
    .table-clean th { background-color: #ffffff; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 1rem 1.5rem; border-bottom: 2px solid #f1f5f9; }
    .table-clean td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; color: #334155; }
    .table-clean tbody tr { transition: background-color 0.2s; }
    .table-clean tbody tr:hover { background-color: #f8fafc; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4 animate-fade-up">
    <div class="d-flex align-items-center">
        <a href="{{ route('admin.lokasi.index') }}" class="btn btn-light rounded-3 shadow-sm me-3 bg-white border" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Detail Area: {{ $lokasi->nama_lokasi }}</h4>
            <p class="text-muted mb-0 small">Melihat informasi spesifik dan daftar alat yang terpasang di lokasi ini.</p>
        </div>
    </div>
    <a href="{{ route('admin.lokasi.edit', $lokasi) }}" class="btn btn-success rounded-3 px-4 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: #10b981; border: none;">
        <i class="bi bi-pencil-square"></i> Edit Lokasi
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        
        <div class="card custom-card mb-4 animate-fade-up" style="animation-delay: 0.1s;">
            <div class="card-header-modern d-flex align-items-center">
                <i class="bi bi-info-circle-fill text-success me-2"></i> Informasi Umum
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="icon-box-soft icon-emerald"><i class="bi bi-building"></i></div>
                    <div>
                        <span class="info-label">Nama Lokasi</span>
                        <div class="info-value">{{ $lokasi->nama_lokasi }}</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon-box-soft"><i class="bi bi-map"></i></div>
                    <div>
                        <span class="info-label">Alamat Lengkap</span>
                        <div class="info-value">{{ $lokasi->alamat ?? 'Tidak ada deskripsi alamat.' }}</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon-box-soft"><i class="bi bi-calendar3"></i></div>
                    <div>
                        <span class="info-label">Terdaftar Sejak</span>
                        <div class="info-value">{{ $lokasi->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card custom-card animate-fade-up" style="animation-delay: 0.2s;">
            <div class="card-header-modern d-flex justify-content-between align-items-center border-bottom">
                <span><i class="bi bi-hdd-stack-fill text-success me-2"></i> Alat IoT (Bak Sampah) di Lokasi Ini</span>
                <span class="badge" style="background: #f1f5f9; color: #0f172a; border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 6px;">{{ $lokasi->bakSampahs->count() }} Unit Terpasang</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-clean">
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
                                    <span class="fw-semibold" style="color: #0f172a;">{{ $bak->kapasitas_max ? number_format($bak->kapasitas_max, 1) . ' kg' : 'Belum diatur' }}</span>
                                </td>
                                <td>
                                    @if($bak->status === 'aktif')
                                        <span class="badge" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; padding: 6px 12px; border-radius: 6px;"><i class="bi bi-check-circle-fill me-1"></i> Aktif</span>
                                    @elseif($bak->status === 'maintenance')
                                        <span class="badge" style="background: #fffbeb; color: #d97706; border: 1px solid #fde68a; padding: 6px 12px; border-radius: 6px;"><i class="bi bi-tools me-1"></i> Maintenance</span>
                                    @else
                                        <span class="badge" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 12px; border-radius: 6px;"><i class="bi bi-x-circle-fill me-1"></i> Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.bak-sampah.show', $bak) }}" class="btn btn-sm bg-white border" title="Lihat Sensor" style="border-radius: 6px; color: #0f172a;">
                                        <i class="bi bi-arrow-right-short fs-5"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 border-0">
                                    <i class="bi bi-hdd-network text-light mb-3" style="font-size: 3rem; display: block; color: #e2e8f0 !important;"></i>
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
            <h6 class="fw-bold mb-4" style="color: #0f172a;"><i class="bi bi-geo-alt-fill text-success me-2"></i> Titik Geografis (GPS)</h6>
            
            <div class="map-placeholder mb-4">
                @if($lokasi->koordinat)
                    <div class="map-pin-pulse"></div>
                    <div class="mt-3 fw-bold bg-white px-3 py-1 shadow-sm" style="font-size: 0.8rem; color: #059669; border-radius: 6px; border: 1px solid #a7f3d0;">
                        <i class="bi bi-crosshair me-1"></i> Signal Ditemukan
                    </div>
                @else
                    <i class="bi bi-slash-circle text-muted fs-1 mb-2"></i>
                    <small class="text-muted fw-medium">Koordinat Belum Diatur</small>
                @endif
            </div>

            <div class="bg-light rounded-3 p-3 text-center border" style="border-radius: 8px !important; background-color: #f8fafc !important;">
                <div class="text-muted small text-uppercase fw-bold mb-1" style="letter-spacing: 0.5px;">Latitude, Longitude</div>
                @if($lokasi->koordinat)
                    <code class="fs-6 text-dark fw-bold" style="font-family: 'Courier New', monospace;">{{ $lokasi->koordinat }}</code>
                @else
                    <span class="text-muted fst-italic small">-</span>
                @endif
            </div>

        </div>
    </div>
</div>

@endsection