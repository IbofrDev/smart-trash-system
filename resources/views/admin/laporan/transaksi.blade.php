@extends('layouts.admin')

@section('title', 'Laporan Analitik Transaksi')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama & Umum */
    .custom-card { border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }
    .card-header-custom { background-color: #ffffff; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }

    /* Filter Box Toolbar - Lapang & Berdiri Sendiri */
    .filter-box { background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem 1.5rem; display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap; box-shadow: 0 4px 12px rgba(0,0,0,0.01); }
    .filter-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem; display: block; }
    .filter-input { border-radius: 8px; border: 1px solid #e2e8f0; padding: 0.5rem 1rem; font-size: 0.9rem; color: #0f172a; background-color: #f8fafc; transition: all 0.2s; min-width: 150px; }
    .filter-input:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }

    /* Mini Stat Cards */
    .mini-stat { border-radius: 12px; background-color: #ffffff; border: 1px solid #e2e8f0; padding: 1.25rem 1rem; text-align: left; position: relative; overflow: hidden; transition: transform 0.2s; height: 100%; box-shadow: 0 4px 12px rgba(0,0,0,0.01); display: flex; flex-direction: column; justify-content: center; }
    .mini-stat:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.03); }
    .mini-stat h3 { font-weight: 800; font-size: 1.5rem; margin-bottom: 0.2rem; z-index: 1; color: #0f172a; position: relative; }
    .mini-stat p { margin: 0; font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; z-index: 1; position: relative; }
    .mini-stat-icon { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); font-size: 2rem; color: #f1f5f9; z-index: 0; }

    /* Anomaly Alert Custom */
    .alert-anomaly { background-color: #fff1f2; border: 1px dashed #fecaca; border-left: 4px solid #ef4444; color: #991b1b; border-radius: 8px; font-weight: 500; }

    /* Styling Tabel Clean */
    .table-modern { margin-bottom: 0; width: 100%; }
    .table-modern th { background-color: #ffffff; color: #64748b; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 1rem 1.2rem; border-bottom: 2px solid #f1f5f9; }
    .table-modern td { padding: 1rem 1.2rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; color: #334155; font-size: 0.9rem; }
    .table-modern tbody tr { transition: background-color 0.2s; }
    .table-modern tbody tr:hover { background-color: #f8fafc; }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Laporan Analitik Sistem</h4>
        <p class="text-muted mb-0 small">Ringkasan aktivitas setoran sampah berdasarkan rentang waktu.</p>
    </div>
    
    <div>
        <a href="{{ route('admin.laporan.transaksi.pdf', ['tanggal_dari' => $tanggalDari, 'tanggal_sampai' => $tanggalSampai]) }}" class="btn rounded-3 fw-bold d-flex align-items-center gap-2" target="_blank" style="height: 40px; background-color: #fff1f2; color: #dc2626; border: 1px solid #fecaca; padding: 0 1.25rem; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#fecaca'" onmouseout="this.style.backgroundColor='#fff1f2'">
            <i class="bi bi-file-earmark-pdf-fill"></i> Cetak PDF Laporan
        </a>
    </div>
</div>

<div class="mb-4 animate-fade-up" style="animation-delay: 0.05s;">
    <form action="{{ route('admin.laporan.transaksi') }}" method="GET" class="filter-box m-0 p-3">
        <div>
            <label class="filter-label"><i class="bi bi-calendar-event me-1 text-success"></i> Dari Tanggal</label>
            <input type="date" name="tanggal_dari" class="filter-input" value="{{ $tanggalDari }}">
        </div>
        <div>
            <label class="filter-label"><i class="bi bi-calendar-check me-1 text-success"></i> Sampai Tanggal</label>
            <input type="date" name="tanggal_sampai" class="filter-input" value="{{ $tanggalSampai }}">
        </div>
        <button type="submit" class="btn fw-bold rounded-3 px-4" style="height: 38px; background-color: #0f172a; color: #ffffff;">
            <i class="bi bi-funnel-fill me-1"></i> Tampilkan
        </button>
    </form>
</div>

{{-- ALERT ANOMALI --}}
@if($summary['total_anomali'] > 0)
<div class="alert alert-anomaly p-3 mb-4 d-flex align-items-center animate-fade-up" style="animation-delay: 0.1s;">
    <div class="bg-white text-danger border border-danger border-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px;">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    </div>
    <div>
        <h6 class="fw-bold mb-0 text-danger">Peringatan Sensor!</h6>
        <span style="font-size: 0.9rem; color: #7f1d1d;">Sistem mendeteksi adanya <strong class="fs-6">{{ $summary['total_anomali'] }} transaksi anomali</strong> pada periode waktu ini. Silakan periksa detail transaksi.</span>
    </div>
</div>
@endif

{{-- MINI STATS GRID --}}
<div class="row g-3 mb-4 animate-fade-up" style="animation-delay: 0.15s;">
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat">
            <i class="bi bi-receipt-cutoff mini-stat-icon"></i>
            <h3>{{ number_format($summary['total_transaksi']) }}</h3>
            <p>Transaksi Valid</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat">
            <i class="bi bi-speedometer2 mini-stat-icon"></i>
            <h3 style="color: #059669;">
                @if($summary['total_berat'] >= 1000)
                    {{ number_format($summary['total_berat'] / 1000, 1) }}<span style="font-size: 0.9rem; font-weight: normal; color: #64748b;"> kg</span>
                @else
                    {{ number_format($summary['total_berat'], 0) }}<span style="font-size: 0.9rem; font-weight: normal; color: #64748b;"> g</span>
                @endif
            </h3>
            <p>Volume Berat</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat">
            <i class="bi bi-star-fill mini-stat-icon"></i>
            <h3>{{ number_format($summary['total_poin']) }}</h3>
            <p>Poin Diberikan</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat">
            <i class="bi bi-coin mini-stat-icon"></i>
            <h3 style="color: #d97706;">{{ number_format($summary['total_koin']) }}</h3>
            <p>Koin Dicetak</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat">
            <i class="bi bi-recycle mini-stat-icon"></i>
            <h3>{{ number_format($summary['total_botol']) }}</h3>
            <p>Pcs Botol/Kaleng</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat">
            <i class="bi bi-people mini-stat-icon"></i>
            <h3>{{ number_format($summary['mahasiswa_aktif']) }}</h3>
            <p>Mahasiswa Aktif</p>
        </div>
    </div>
</div>

{{-- CHARTS & GRAPH SECTIONS --}}
<div class="row g-4 mb-4 animate-fade-up" style="animation-delay: 0.2s;">
    <div class="col-lg-7 col-xl-8">
        <div class="custom-card h-100">
            <div class="card-header-custom border-bottom">
                <div class="fw-bold text-dark"><i class="bi bi-graph-up-arrow text-success me-2"></i>Tren Aktivitas Harian</div>
            </div>
            <div class="card-body p-4">
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="chartHarian"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5 col-xl-4">
        <div class="custom-card h-100">
            <div class="card-header-custom border-bottom">
                <div class="fw-bold text-dark"><i class="bi bi-pie-chart-fill text-success me-2"></i>Distribusi Kategori</div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th class="text-center">Setoran</th>
                                <th class="text-end">Volume</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($perJenisSampah as $item)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->jenisSampah->nama ?? 'Dihapus' }}</div>
                                    <small class="text-muted"><i class="bi bi-coin text-warning me-1"></i>{{ number_format($item->total_koin ?? 0) }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge border" style="background: #f8fafc; color: #475569; border-radius: 4px;">{{ number_format($item->total_botol ?? 0) }} pcs</span><br>
                                    <small class="text-muted" style="font-size: 0.7rem;">({{ $item->total_transaksi }} Trx)</small>
                                </td>
                                <td class="text-end fw-bold text-success">
                                    @if(($item->total_berat ?? 0) >= 1000)
                                        {{ number_format($item->total_berat / 1000, 2) }} kg
                                    @else
                                        {{ number_format($item->total_berat ?? 0, 0) }} g
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4 small">Belum ada distribusi data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- LOG DETAIL TABLES --}}
<div class="custom-card animate-fade-up" style="animation-delay: 0.25s;">
    <div class="card-header-custom border-bottom">
        <div class="fw-bold text-dark"><i class="bi bi-list-columns-reverse text-success me-2"></i>Log Detail Transaksi</div>
        <span class="badge bg-light text-dark border px-3 py-1 rounded-2">{{ $transaksis->count() }} Data Terekam</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-clean">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="20%">Waktu Terekam</th>
                        <th width="25%">Identitas Mahasiswa</th>
                        <th width="20%">Lokasi / Mesin</th>
                        <th width="15%">Data Sensor (Pcs/Gr)</th>
                        <th width="15%">Reward (Pts/Koin)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $index => $trx)
                    <tr>
                        <td class="text-center text-muted fw-semibold">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d M Y') }}</div>
                            <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('H:i:s') }} WITA</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($trx->mahasiswa->name ?? 'User') }}&background=ecfdf5&color=047857&bold=true" alt="Avatar" width="32" height="32" style="border-radius: 6px;" class="border">
                                <div>
                                    <div class="fw-semibold text-dark" style="font-size: 0.9rem;">{{ Str::limit($trx->mahasiswa->name ?? '-', 20) }}</div>
                                    <small class="text-muted" style="font-family: monospace;">{{ $trx->mahasiswa->nim ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="fw-medium text-dark"><i class="bi bi-hdd-network text-secondary me-1"></i>{{ $trx->bakSampah->nama ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="badge border me-1" style="background: #f8fafc; color: #0f172a; border-radius: 4px;">{{ $trx->jumlah_final ?? 0 }} pcs</span>
                            <span class="badge border" style="background: #f8fafc; color: #0f172a; border-radius: 4px;">
                                @if($trx->berat >= 1000)
                                    {{ number_format($trx->berat / 1000, 2) }} kg
                                @else
                                    {{ number_format($trx->berat ?? 0, 0) }} g
                                @endif
                            </span>
                            @if(($trx->status_validasi ?? 'valid') !== 'valid')
                                <i class="bi bi-exclamation-triangle-fill text-danger ms-1" title="Anomali Sensor"></i>
                            @endif
                        </td>
                        <td>
                            <div class="text-success fw-bold" style="font-size: 0.85rem;"><i class="bi bi-star-fill text-warning me-1"></i>+{{ number_format($trx->poin_didapat ?? 0) }}</div>
                            <div class="text-info fw-bold" style="font-size: 0.85rem;"><i class="bi bi-coin text-info me-1"></i>+{{ number_format($trx->koin_didapat ?? 0) }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                            Tidak ada transaksi pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
    Chart.defaults.color = '#64748b';
    
    const ctx = document.getElementById('chartHarian').getContext('2d');
    const gradientBar = ctx.createLinearGradient(0, 0, 0, 400);
    gradientBar.addColorStop(0, 'rgba(16, 185, 129, 0.8)');
    gradientBar.addColorStop(1, 'rgba(16, 185, 129, 0.2)');
    
    const gradientLine = ctx.createLinearGradient(0, 0, 0, 400);
    gradientLine.addColorStop(0, 'rgba(15, 23, 42, 0.3)');
    gradientLine.addColorStop(1, 'rgba(15, 23, 42, 0.0)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($perHari->pluck('tanggal')->map(fn($t) => \Carbon\Carbon::parse($t)->format('d M'))) !!},
            datasets: [
                {
                    label: 'Volume Transaksi',
                    data: {!! json_encode($perHari->pluck('total_transaksi')) !!},
                    backgroundColor: gradientBar,
                    borderColor: '#10b981',
                    borderWidth: 1,
                    borderRadius: 4,
                    yAxisID: 'y',
                    order: 2
                },
                {
                    label: 'Koin Didistribusikan',
                    data: {!! json_encode($perHari->pluck('total_koin')) !!},
                    type: 'line',
                    borderColor: '#0f172a',
                    backgroundColor: gradientLine,
                    borderWidth: 2,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#0f172a',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1',
                    order: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: { usePointStyle: true, boxWidth: 8, padding: 20 }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: { size: 13 },
                    bodyFont: { size: 13 },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            scales: {
                x: { grid: { display: false, drawBorder: false } },
                y: { beginAtZero: true, position: 'left', grid: { color: '#f1f5f9', drawBorder: false } },
                y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false } }
            }
        }
    });
});
</script>
@endpush
@endsection