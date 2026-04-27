@extends('layouts.admin')

@section('title', 'Laporan Analitik Transaksi')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama & Umum */
    .custom-card { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03); background-color: #ffffff; overflow: hidden; }
    .card-header-custom { background-color: #ffffff; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }

    /* Filter Box */
    .filter-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem 1.5rem; display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap; }
    .filter-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem; display: block; }
    .filter-input { border-radius: 8px; border: 1px solid #cbd5e1; padding: 0.5rem 1rem; font-size: 0.9rem; color: #1e293b; background-color: #ffffff; transition: all 0.2s; min-width: 150px; }
    .filter-input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }

    /* Mini Stat Cards (Compact Gradient) */
    .mini-stat { border-radius: 14px; color: white; padding: 1.25rem 1rem; text-align: center; position: relative; overflow: hidden; transition: transform 0.3s; height: 100%; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .mini-stat:hover { transform: translateY(-5px); }
    .mini-stat::after { content: ''; position: absolute; right: -10px; top: -10px; width: 60px; height: 60px; border-radius: 50%; background: rgba(255,255,255,0.15); }
    
    .bg-grad-blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .bg-grad-green { background: linear-gradient(135deg, #10b981, #059669); }
    .bg-grad-amber { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .bg-grad-cyan { background: linear-gradient(135deg, #06b6d4, #0284c7); }
    .bg-grad-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
    .bg-grad-indigo { background: linear-gradient(135deg, #6366f1, #4338ca); }

    .mini-stat h3 { font-weight: 800; font-size: 1.5rem; margin-bottom: 0.2rem; z-index: 1; position: relative; }
    .mini-stat p { margin: 0; font-size: 0.75rem; opacity: 0.9; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; z-index: 1; position: relative; }
    .mini-stat-icon { font-size: 1.5rem; margin-bottom: 0.5rem; opacity: 0.8; z-index: 1; position: relative; }

    /* Anomaly Alert Custom */
    .alert-anomaly { background: linear-gradient(to right, #fffbeb, #fef3c7); border: 1px solid #fcd34d; border-left: 5px solid #f59e0b; color: #b45309; border-radius: 12px; font-weight: 500; box-shadow: 0 4px 6px rgba(245, 158, 11, 0.05); }

    /* Styling Tabel Modern */
    .table-modern { margin-bottom: 0; width: 100%; }
    .table-modern th { background-color: #f8fafc; color: #475569; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; padding: 1rem 1.2rem; border-bottom: 1px solid #e2e8f0; }
    .table-modern td { padding: 1rem 1.2rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; color: #334155; font-size: 0.9rem; }
    .table-modern tbody tr { transition: background-color 0.2s; }
    .table-modern tbody tr:hover { background-color: #f8fafc; }
</style>

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end mb-4 animate-fade-up">
    <div class="mb-3 mb-lg-0">
        <h4 class="fw-bold text-dark mb-1">Laporan Analitik Sistem</h4>
        <p class="text-muted mb-0 small">Ringkasan aktivitas setoran sampah berdasarkan rentang waktu.</p>
    </div>
    
    <div class="d-flex gap-3 align-items-end flex-wrap">
        <form action="{{ route('admin.laporan.transaksi') }}" method="GET" class="filter-box m-0 p-2 shadow-sm">
            <div>
                <label class="filter-label"><i class="bi bi-calendar-event me-1"></i> Dari Tanggal</label>
                <input type="date" name="tanggal_dari" class="filter-input" value="{{ $tanggalDari }}">
            </div>
            <div>
                <label class="filter-label"><i class="bi bi-calendar-check me-1"></i> Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" class="filter-input" value="{{ $tanggalSampai }}">
            </div>
            <button type="submit" class="btn btn-dark fw-bold rounded-3 px-4" style="height: 38px;">
                <i class="bi bi-funnel-fill me-1"></i> Tampilkan
            </button>
        </form>

        <a href="{{ route('admin.laporan.transaksi.pdf', ['tanggal_dari' => $tanggalDari, 'tanggal_sampai' => $tanggalSampai]) }}" class="btn btn-danger rounded-3 fw-bold shadow-sm d-flex align-items-center" target="_blank" style="height: 38px; background: linear-gradient(135deg, #ef4444, #b91c1c); border: none;">
            <i class="bi bi-file-earmark-pdf-fill me-2"></i> Cetak PDF
        </a>
    </div>
</div>

@if($summary['total_anomali'] > 0)
<div class="alert alert-anomaly p-3 mb-4 d-flex align-items-center animate-fade-up" style="animation-delay: 0.05s;">
    <div class="bg-warning bg-opacity-25 text-warning rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px;">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    </div>
    <div>
        <h6 class="fw-bold mb-0">Peringatan Sensor!</h6>
        <span style="font-size: 0.9rem;">Sistem mendeteksi adanya <strong class="fs-6">{{ $summary['total_anomali'] }} transaksi anomali</strong> pada periode waktu ini. Silakan periksa detail transaksi.</span>
    </div>
</div>
@endif

<div class="row g-3 mb-4 animate-fade-up" style="animation-delay: 0.1s;">
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat bg-grad-blue">
            <i class="bi bi-receipt-cutoff mini-stat-icon"></i>
            <h3>{{ number_format($summary['total_transaksi']) }}</h3>
            <p>Transaksi Valid</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat bg-grad-green">
            <i class="bi bi-speedometer2 mini-stat-icon"></i>
            <h3>
                @if($summary['total_berat'] >= 1000)
                    {{ number_format($summary['total_berat'] / 1000, 1) }}<span style="font-size: 0.9rem; font-weight: normal;"> kg</span>
                @else
                    {{ number_format($summary['total_berat'], 0) }}<span style="font-size: 0.9rem; font-weight: normal;"> g</span>
                @endif
            </h3>
            <p>Volume Berat</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat bg-grad-amber">
            <i class="bi bi-star-fill mini-stat-icon"></i>
            <h3>{{ number_format($summary['total_poin']) }}</h3>
            <p>Poin Diberikan</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat bg-grad-cyan">
            <i class="bi bi-coin mini-stat-icon"></i>
            <h3>{{ number_format($summary['total_koin']) }}</h3>
            <p>Koin Dicetak</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat bg-grad-purple">
            <i class="bi bi-recycle mini-stat-icon"></i>
            <h3>{{ number_format($summary['total_botol']) }}</h3>
            <p>Pcs Botol/Kaleng</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat bg-grad-indigo">
            <i class="bi bi-people mini-stat-icon"></i>
            <h3>{{ number_format($summary['mahasiswa_aktif']) }}</h3>
            <p>Mahasiswa Aktif</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4 animate-fade-up" style="animation-delay: 0.2s;">
    
    <div class="col-lg-7 col-xl-8">
        <div class="custom-card h-100">
            <div class="card-header-custom">
                <div class="fw-bold text-dark"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Tren Aktivitas Harian</div>
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
            <div class="card-header-custom">
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
                                    <span class="badge bg-slate-100 text-dark border">{{ number_format($item->total_botol ?? 0) }} pcs</span><br>
                                    <small class="text-muted" style="font-size: 0.7rem;">({{ $item->total_transaksi }} Trx)</small>
                                </td>
                                <td class="text-end fw-semibold text-success">
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

<div class="custom-card animate-fade-up" style="animation-delay: 0.3s;">
    <div class="card-header-custom">
        <div class="fw-bold text-dark"><i class="bi bi-list-columns-reverse text-info me-2"></i>Log Detail Transaksi</div>
        <span class="badge bg-dark bg-opacity-10 text-dark rounded-pill px-3">{{ $transaksis->count() }} Data Terekam</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern">
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
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($trx->mahasiswa->name ?? 'User') }}&background=f1f5f9&color=475569&rounded=true" alt="Avatar" width="32" height="32" class="rounded-circle border">
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
                            <span class="badge bg-light text-dark border me-1">{{ $trx->jumlah_final ?? 0 }} pcs</span>
                            <span class="badge bg-light text-dark border">
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
    // Styling Chart.js agar terlihat Premium
    Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
    Chart.defaults.color = '#64748b';
    
    const ctx = document.getElementById('chartHarian').getContext('2d');
    
    // Membuat Gradient untuk Bar Chart
    const gradientBar = ctx.createLinearGradient(0, 0, 0, 400);
    gradientBar.addColorStop(0, 'rgba(59, 130, 246, 0.8)'); // Biru
    gradientBar.addColorStop(1, 'rgba(59, 130, 246, 0.2)');
    
    // Membuat Gradient untuk Line Chart
    const gradientLine = ctx.createLinearGradient(0, 0, 0, 400);
    gradientLine.addColorStop(0, 'rgba(16, 185, 129, 0.3)'); // Hijau
    gradientLine.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($perHari->pluck('tanggal')->map(fn($t) => \Carbon\Carbon::parse($t)->format('d M'))) !!},
            datasets: [
                {
                    label: 'Volume Transaksi',
                    data: {!! json_encode($perHari->pluck('total_transaksi')) !!},
                    backgroundColor: gradientBar,
                    borderColor: '#3b82f6',
                    borderWidth: 1,
                    borderRadius: 6, // Ujung bar membulat
                    yAxisID: 'y',
                    order: 2
                },
                {
                    label: 'Koin Didistribusikan',
                    data: {!! json_encode($perHari->pluck('total_koin')) !!},
                    type: 'line',
                    borderColor: '#10b981',
                    backgroundColor: gradientLine,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4, // Membuat garis melengkung halus (spline)
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
                x: {
                    grid: { display: false, drawBorder: false }
                },
                y: {
                    beginAtZero: true,
                    position: 'left',
                    grid: { color: '#f1f5f9', drawBorder: false },
                    title: { display: false }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    title: { display: false }
                }
            }
        }
    });
});
</script>
@endpush
@endsection