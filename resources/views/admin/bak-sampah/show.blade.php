@extends('layouts.admin')

@section('title', 'Detail Perangkat IoT')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Modern - Radius Dikurangi & Lebih Bersih */
    .custom-card { border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; transition: transform 0.2s; }
    .custom-card:hover { box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04); }
    .card-header-modern { background-color: #ffffff; border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem; font-weight: 700; color: #0f172a; }

    /* Device Banner Header - Minimalist Emerald */
    .device-banner {
        background-color: #ffffff;
        border: 1px solid #f1f5f9;
        border-left: 6px solid #10b981;
        border-radius: 12px;
        padding: 2rem;
        color: #334155;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    }
    .device-banner::before {
        content: '\F633'; 
        font-family: 'bootstrap-icons';
        position: absolute;
        right: -10px;
        top: -30px;
        font-size: 10rem;
        color: #f1f5f9; /* Sangat samar di latar putih */
        z-index: 0;
        transform: rotate(-15deg);
    }
    
    /* Radial Progress (Kapasitas Visual) */
    .capacity-circle {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: conic-gradient(#10b981 var(--percentage), #f1f5f9 0);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .capacity-inner {
        width: 90px;
        height: 90px;
        background-color: #ffffff;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.02);
    }

    /* Terminal API Key - Light Mode */
    .api-terminal {
        background-color: #f8fafc;
        border-radius: 8px;
        padding: 1.25rem 1.5rem;
        font-family: 'Courier New', Courier, monospace;
        color: #047857; /* Hijau Emerald Tua */
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 1px dashed #cbd5e1;
        position: relative;
    }
    .api-terminal code { font-size: 0.95rem; font-weight: 700; word-break: break-all; padding-right: 45px; line-height: 1.5; }
    
    .btn-copy { 
        background: #ffffff; border: 1px solid #e2e8f0; color: #64748b; 
        width: 36px; height: 36px; border-radius: 6px; 
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s; position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .btn-copy:hover { background: #f1f5f9; color: #0f172a; border-color: #cbd5e1; }

    /* Styling Tabel Clean */
    .table-clean { margin-bottom: 0; width: 100%; }
    .table-clean th { background-color: #ffffff; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 1rem 1.5rem; border-bottom: 2px solid #f1f5f9; }
    .table-clean td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; color: #334155; }
    .table-clean tbody tr { transition: background-color 0.2s; }
    .table-clean tbody tr:hover { background-color: #f8fafc; }
    
    .empty-state { padding: 3rem 1rem; text-align: center; color: #94a3b8; }
    .empty-state i { font-size: 3rem; color: #e2e8f0; display: block; margin-bottom: 1rem; }

    /* Stat Cards */
    .stat-card-value { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 0; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4 animate-fade-up">
    <div class="d-flex align-items-center">
        <a href="{{ route('admin.bak-sampah.index') }}" class="btn btn-light rounded-3 shadow-sm me-3 bg-white border" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Device Monitor</h4>
            <p class="text-muted mb-0 small">Pemantauan perangkat keras bak sampah IoT.</p>
        </div>
    </div>
    <a href="{{ route('admin.bak-sampah.edit', $bakSampah) }}" class="btn btn-success rounded-3 px-4 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: #10b981; border: none;">
        <i class="bi bi-pencil-square"></i> Edit Device
    </a>
</div>

<div class="row g-4">
    
    <div class="col-lg-8">
        
        <div class="device-banner mb-4 animate-fade-up" style="animation-delay: 0.1s;">
            <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 1;">
                <div>
                    <span class="badge mb-2 px-3 py-1 rounded-2 fw-semibold" style="background: #f8fafc; color: #475569; border: 1px solid #e2e8f0;">
                        <i class="bi bi-geo-alt-fill text-success me-1"></i> {{ $bakSampah->lokasi->nama_lokasi ?? 'Lokasi Belum Diatur' }}
                    </span>
                    <h3 class="fw-bold mb-1" style="color: #0f172a;">{{ $bakSampah->nama }}</h3>
                    <p class="text-muted small mb-0">Terdaftar sejak {{ $bakSampah->created_at->format('d M Y') }}</p>
                </div>
                
                <div class="text-end">
                    <div class="text-uppercase small fw-bold text-muted mb-2" style="letter-spacing: 0.5px;">Status Operasional</div>
                    @if($bakSampah->status === 'aktif')
                        <div class="d-inline-flex align-items-center px-3 py-2 rounded-2" style="background-color: #ecfdf5; border: 1px solid #a7f3d0;">
                            <span class="spinner-grow spinner-grow-sm text-success me-2" style="width: 10px; height: 10px;" role="status"></span>
                            <span class="fw-bold text-success" style="font-size: 0.85rem;">ONLINE (Aktif)</span>
                        </div>
                    @elseif($bakSampah->status === 'maintenance')
                        <div class="d-inline-flex align-items-center px-3 py-2 rounded-2" style="background-color: #fffbeb; border: 1px solid #fde68a;">
                            <i class="bi bi-tools text-warning me-2"></i>
                            <span class="fw-bold" style="color: #d97706; font-size: 0.85rem;">MAINTENANCE</span>
                        </div>
                    @else
                        <div class="d-inline-flex align-items-center px-3 py-2 rounded-2" style="background-color: #fef2f2; border: 1px solid #fecaca;">
                            <i class="bi bi-power text-danger me-2"></i>
                            <span class="fw-bold text-danger" style="font-size: 0.85rem;">OFFLINE</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4 animate-fade-up" style="animation-delay: 0.15s;">
            <div class="col-md-4">
                <div class="card custom-card p-3 text-center h-100">
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Total Setoran</div>
                    <h3 class="stat-card-value">{{ number_format($stats['total_transaksi']) }} <small class="fs-6 text-muted fw-medium">kali</small></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card custom-card p-3 text-center h-100">
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Berat Diterima</div>
                    <h3 class="stat-card-value" style="color: #059669;">{{ number_format($stats['total_berat'], 2) }} <small class="fs-6 text-muted fw-medium">kg</small></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card custom-card p-3 text-center h-100">
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Poin Keluar</div>
                    <h3 class="stat-card-value">{{ number_format($stats['total_poin']) }} <small class="fs-6 text-muted fw-medium">pts</small></h3>
                </div>
            </div>
        </div>

        <div class="card custom-card animate-fade-up" style="animation-delay: 0.2s;">
            <div class="card-header-modern d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history text-success me-2"></i>Riwayat Sinkronisasi Data (Setoran)</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-clean">
                        <thead>
                            <tr>
                                <th class="ps-4">Waktu</th>
                                <th>Mahasiswa</th>
                                <th>Jenis Sampah</th>
                                <th>Berat</th>
                                <th class="pe-4 text-end">Poin Keluar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bakSampah->transaksiSampah as $trx)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark" style="color: #0f172a !important;">{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('H:i') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d M Y') }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold" style="color: #334155;">{{ $trx->mahasiswa->name ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge rounded-2 px-2 py-1" style="background: #f8fafc; color: #475569; border: 1px solid #e2e8f0;">
                                        {{ $trx->jenisSampah->nama ?? '-' }}
                                    </span>
                                </td>
                                <td><span class="fw-bold" style="color: #0f172a;">{{ number_format($trx->berat, 2) }} <small class="text-muted">kg</small></span></td>
                                <td class="text-end pe-4">
                                    <span class="badge px-2 py-1 rounded-2" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;">+{{ $trx->poin_didapat }} Pts</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="border-0">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox text-light mb-3" style="font-size: 3rem; display: block; color: #e2e8f0 !important;"></i>
                                        <div class="fw-bold text-secondary mb-1">Belum Ada Transaksi</div>
                                        <small>Alat ini belum pernah menerima setoran sampah.</small>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 animate-fade-up d-flex flex-column" style="animation-delay: 0.3s;">
        
        <div class="card custom-card mb-4 text-center p-4 flex-shrink-0">
            <h6 class="fw-bold mb-4 text-start" style="color: #0f172a;"><i class="bi bi-hdd-fill text-success me-2"></i>Kapasitas Hardware</h6>
            
            @php
                $maxKg = $bakSampah->kapasitas_max ?? 0;
            @endphp
            
            <div class="capacity-circle" style="--percentage: 100%;">
                <div class="capacity-inner">
                    <span class="fs-4 fw-bold mb-0" style="color: #0f172a;">{{ $maxKg ? number_format($maxKg, 0) : '0' }}</span>
                    <small class="text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">KG MAX</small>
                </div>
            </div>
            <p class="text-muted small mt-4 mb-0">Batas maksimal tampungan fisik yang diizinkan sebelum alat terkunci otomatis.</p>
        </div>

        <div class="card custom-card p-4 flex-grow-1 d-flex flex-column">
            <div>
                <h6 class="fw-bold mb-2" style="color: #0f172a;"><i class="bi bi-key-fill text-success me-2"></i>Autentikasi (API Key)</h6>
                <p class="text-muted small mb-0">Token akses unik untuk mengintegrasikan NodeMCU/ESP32 dengan server.</p>
            </div>
            
            <div class="d-flex flex-column justify-content-center flex-grow-1 my-4">
                <div class="api-terminal">
                    <code id="apiText">{{ $bakSampah->api_key }}</code>
                    <input type="text" id="apiKeyInput" value="{{ $bakSampah->api_key }}" style="position: absolute; left: -9999px;">
                    <button type="button" class="btn-copy" onclick="copyApiKey()" title="Salin API Key">
                        <i class="bi bi-clipboard"></i>
                    </button>
                </div>
            </div>
            
            <div class="mt-auto border-top pt-4" style="border-color: #f1f5f9 !important;">
                <form action="{{ route('admin.bak-sampah.regenerate-api-key', $bakSampah) }}" method="POST" class="m-0 text-center" onsubmit="return confirm('PERINGATAN! Regenerate API Key akan memutuskan koneksi hardware saat ini. Lanjutkan?')">
                    @csrf
                    <button type="submit" class="btn rounded-3 px-4 py-2 fw-bold w-100 shadow-sm" style="background-color: #fff1f2; color: #dc2626; border: 1px solid #fecaca; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#fecaca'" onmouseout="this.style.backgroundColor='#fff1f2'">
                        <i class="bi bi-arrow-repeat me-1"></i> Generate Ulang Token
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
// Fungsi Copy API Key
function copyApiKey() {
    const apiInput = document.getElementById('apiKeyInput');
    apiInput.select();
    apiInput.setSelectionRange(0, 99999); 
    document.execCommand('copy');
    
    // Ubah ikon tombol sementara menjadi centang
    const btn = document.querySelector('.btn-copy i');
    btn.classList.remove('bi-clipboard');
    btn.classList.add('bi-check2');
    btn.classList.add('text-success');
    
    setTimeout(() => {
        btn.classList.remove('bi-check2');
        btn.classList.remove('text-success');
        btn.classList.add('bi-clipboard');
    }, 2000);
}
</script>
@endpush