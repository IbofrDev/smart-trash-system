@extends('layouts.admin')

@section('title', 'Detail Perangkat IoT')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Modern */
    .custom-card { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03); background-color: #ffffff; overflow: hidden; transition: transform 0.2s; }
    .custom-card:hover { box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05); }
    .card-header-modern { background-color: #ffffff; border-bottom: 1px solid #f3f4f6; padding: 1.25rem 1.5rem; font-weight: 700; color: #1f2937; }

    /* Device Banner Header */
    .device-banner {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .device-banner::before {
        content: '\F633'; 
        font-family: 'bootstrap-icons';
        position: absolute;
        right: -20px;
        top: -30px;
        font-size: 10rem;
        opacity: 0.05;
        transform: rotate(-15deg);
    }
    
    /* Radial Progress (Kapasitas Visual) */
    .capacity-circle {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: conic-gradient(#10b981 var(--percentage), #e5e7eb 0);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    
    .capacity-inner {
        width: 90px;
        height: 90px;
        background-color: white;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.05);
    }

    /* Terminal API Key */
    .api-terminal {
        background-color: #111827;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        font-family: 'Courier New', Courier, monospace;
        color: #10b981;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 1px solid #374151;
        position: relative;
    }
    .api-terminal code { font-size: 0.9rem; word-break: break-all; padding-right: 45px; line-height: 1.5; }
    
    .btn-copy { 
        background: rgba(255,255,255,0.1); border: none; color: white; 
        width: 40px; height: 40px; border-radius: 8px; 
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s; position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
    }
    .btn-copy:hover { background: rgba(16, 185, 129, 0.5); }

    /* Tabel Modern */
    .table-modern { margin-bottom: 0; width: 100%; }
    .table-modern th { background-color: #f9fafb; color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
    .table-modern td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f3f4f6; color: #4b5563; }
    
    .empty-state { padding: 3rem 1rem; text-align: center; color: #9ca3af; }
    .empty-state i { font-size: 3rem; color: #e5e7eb; display: block; margin-bottom: 1rem; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4 animate-fade-up">
    <div class="d-flex align-items-center">
        <a href="{{ route('admin.bak-sampah.index') }}" class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-1">Device Monitor</h4>
            <p class="text-muted mb-0 small">Pemantauan perangkat keras bak sampah IoT.</p>
        </div>
    </div>
    <a href="{{ route('admin.bak-sampah.edit', $bakSampah) }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm d-flex align-items-center gap-2">
        <i class="bi bi-pencil-square"></i> Edit Device
    </a>
</div>

<div class="row g-4">
    
    <div class="col-lg-8">
        
        <div class="device-banner mb-4 animate-fade-up" style="animation-delay: 0.1s;">
            <div class="d-flex justify-content-between align-items-start position-relative z-1">
                <div>
                    <span class="badge bg-light text-dark mb-2 px-3 py-1 rounded-pill fw-semibold">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $bakSampah->lokasi->nama_lokasi ?? 'Lokasi Belum Diatur' }}
                    </span>
                    <h3 class="fw-bold mb-1">{{ $bakSampah->nama }}</h3>
                    <p class="text-white-50 small mb-0">Terdaftar sejak {{ $bakSampah->created_at->format('d M Y') }}</p>
                </div>
                
                <div class="text-end">
                    <div class="text-uppercase small fw-bold text-white-50 mb-1">Status Operasional</div>
                    @if($bakSampah->status === 'aktif')
                        <div class="d-inline-flex align-items-center px-3 py-2 rounded-3" style="background-color: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.3);">
                            <span class="spinner-grow spinner-grow-sm text-success me-2" role="status"></span>
                            <span class="fw-bold text-success">ONLINE (Aktif)</span>
                        </div>
                    @elseif($bakSampah->status === 'maintenance')
                        <div class="d-inline-flex align-items-center px-3 py-2 rounded-3 bg-warning bg-opacity-25 border border-warning border-opacity-50">
                            <i class="bi bi-tools text-warning me-2"></i>
                            <span class="fw-bold text-warning">MAINTENANCE</span>
                        </div>
                    @else
                        <div class="d-inline-flex align-items-center px-3 py-2 rounded-3 bg-danger bg-opacity-25 border border-danger border-opacity-50">
                            <i class="bi bi-power text-danger me-2"></i>
                            <span class="fw-bold text-danger">OFFLINE</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4 animate-fade-up" style="animation-delay: 0.15s;">
            <div class="col-md-4">
                <div class="card custom-card p-3 text-center h-100">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Total Setoran</div>
                    <h3 class="text-primary fw-bold mb-0">{{ number_format($stats['total_transaksi']) }} <small class="fs-6 text-muted">kali</small></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card custom-card p-3 text-center h-100">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Berat Diterima</div>
                    <h3 class="text-success fw-bold mb-0">{{ number_format($stats['total_berat'], 2) }} <small class="fs-6 text-muted">kg</small></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card custom-card p-3 text-center h-100">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Poin Keluar</div>
                    <h3 class="text-warning fw-bold mb-0">{{ number_format($stats['total_poin']) }} <small class="fs-6 text-muted">pts</small></h3>
                </div>
            </div>
        </div>

        <div class="card custom-card animate-fade-up" style="animation-delay: 0.2s;">
            <div class="card-header-modern d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history text-primary me-2"></i>Riwayat Sinkronisasi Data (Setoran)</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern">
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
                                    <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('H:i') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d M Y') }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $trx->mahasiswa->name ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1 rounded-1">
                                        {{ $trx->jenisSampah->nama ?? '-' }}
                                    </span>
                                </td>
                                <td><span class="fw-bold">{{ number_format($trx->berat, 2) }} <small>kg</small></span></td>
                                <td class="text-end pe-4">
                                    <span class="badge bg-success px-2 py-1 rounded-pill">+{{ $trx->poin_didapat }} Pts</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="border-0">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
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
            <h6 class="fw-bold text-dark mb-4 text-start"><i class="bi bi-hdd-fill text-secondary me-2"></i>Kapasitas Hardware</h6>
            
            @php
                $maxKg = $bakSampah->kapasitas_max ?? 0;
            @endphp
            
            <div class="capacity-circle" style="--percentage: 100%;">
                <div class="capacity-inner">
                    <span class="fs-4 fw-bold text-dark mb-0">{{ $maxKg ? number_format($maxKg, 0) : '0' }}</span>
                    <small class="text-muted fw-bold" style="font-size: 0.6rem;">KG MAX</small>
                </div>
            </div>
            <p class="text-muted small mt-3 mb-0">Batas maksimal tampungan fisik yang diizinkan sebelum alat terkunci otomatis.</p>
        </div>

        <div class="card custom-card p-4 flex-grow-1 d-flex flex-column">
            <div>
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-key-fill text-warning me-2"></i>Autentikasi (API Key)</h6>
                <p class="text-muted small mb-0">Token akses unik untuk mengintegrasikan NodeMCU/ESP32 dengan server.</p>
            </div>
            
            <div class="d-flex flex-column justify-content-center flex-grow-1 my-4">
                <div class="api-terminal shadow-sm">
                    <code id="apiText">{{ $bakSampah->api_key }}</code>
                    <input type="text" id="apiKeyInput" value="{{ $bakSampah->api_key }}" style="position: absolute; left: -9999px;">
                    <button type="button" class="btn-copy" onclick="copyApiKey()" title="Salin API Key">
                        <i class="bi bi-clipboard fs-5"></i>
                    </button>
                </div>
            </div>
            
            <div class="mt-auto border-top pt-4">
                <form action="{{ route('admin.bak-sampah.regenerate-api-key', $bakSampah) }}" method="POST" class="m-0 text-center" onsubmit="return confirm('PERINGATAN! Regenerate API Key akan memutuskan koneksi hardware saat ini. Lanjutkan?')">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger rounded-pill px-4 py-2 fw-semibold w-100 shadow-sm">
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
    
    setTimeout(() => {
        btn.classList.remove('bi-check2');
        btn.classList.add('bi-clipboard');
    }, 2000);
}
</script>
@endpush