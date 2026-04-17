@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<style>
    /* Animasi Slide Up Fade-in */
    .animate-slide-up {
        opacity: 0;
        transform: translateY(20px);
        animation: slideUpFade 0.6s ease-out forwards;
    }
    @keyframes slideUpFade {
        to { opacity: 1; transform: translateY(0); }
    }

    /* Styling Dasar Card */
    .custom-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background-color: #ffffff;
    }
    .custom-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .card-header-custom {
        background-color: transparent;
        border-bottom: 1px solid #f3f4f6;
        padding: 1.25rem 1.5rem;
        font-weight: 700;
        color: #1f2937;
        border-radius: 16px 16px 0 0 !important;
    }

    .stats-icon-box {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }
    .stats-value-text { font-size: 1.8rem; font-weight: 800; color: #111827; }
    .stats-label-text { font-size: 0.875rem; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

    .pulse-dot {
        width: 10px; height: 10px;
        background-color: #10b981;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
        position: relative;
    }
    .pulse-dot::after {
        content: ""; width: 100%; height: 100%;
        background-color: #10b981;
        border-radius: 50%;
        position: absolute;
        animation: pulse-ring 1.5s infinite;
    }
    @keyframes pulse-ring {
        0% { transform: scale(0.33); opacity: 1; }
        80%, 100% { transform: scale(2.5); opacity: 0; }
    }

    .rank-circle {
        width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;
        border-radius: 50%; font-weight: 800;
    }
    .rank-1 { background-color: #fef08a; color: #854d0e; }
    .rank-2 { background-color: #e5e7eb; color: #374151; }
    .rank-3 { background-color: #ffedd5; color: #9a3412; }

    .table-custom th { background-color: #f9fafb; color: #6b7280; text-transform: uppercase; font-size: 0.75rem; padding: 12px 16px; }
    .table-custom td { vertical-align: middle; border-bottom: 1px solid #f3f4f6; padding: 12px 16px; }
</style>

<div class="row mb-4 animate-slide-up" style="animation-delay: 0.1s;">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center bg-white p-4 rounded-4 shadow-sm" style="border-left: 5px solid #10b981;">
            <div>
                <h4 class="fw-bold text-dark mb-1" id="dynamic-greeting">Selamat datang, Administrator! 👋</h4>
                <p class="text-muted mb-0">Sistem Smart Waste Bank siap dikelola.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <button class="btn btn-outline-dark border-2 rounded-pill px-4 fw-bold me-2" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Refresh Data
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="card custom-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stats-value-text">{{ number_format($stats['total_mahasiswa']) }}</div>
                    <div class="stats-label-text">Total Mahasiswa</div>
                </div>
                <div class="stats-icon-box" style="background:#eff6ff; color:#3b82f6;">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-xl-3 animate-slide-up" style="animation-delay: 0.3s;">
        <div class="card custom-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stats-value-text">{{ number_format($stats['total_transaksi']) }}</div>
                    <div class="stats-label-text">Total Transaksi</div>
                </div>
                <div class="stats-icon-box" style="background:#ecfdf5; color:#10b981;">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-xl-3 animate-slide-up" style="animation-delay: 0.4s;">
        <div class="card custom-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stats-value-text">
                        {{ number_format($stats['total_berat_gram'] / 1000, 1) }}
                        <small class="fs-6 fw-normal text-muted">kg</small>
                    </div>
                    <div class="stats-label-text">Total Sampah ({{ number_format($stats['total_botol']) }} pcs)</div>
                </div>
                <div class="stats-icon-box" style="background:#fffbeb; color:#f59e0b;">
                    <i class="bi bi-trash3-fill"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-xl-3 animate-slide-up" style="animation-delay: 0.5s;">
        <div class="card custom-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stats-value-text">{{ $stats['bak_sampah_aktif'] }}/{{ $stats['bak_sampah_total'] }}</div>
                    <div class="stats-label-text">Bak Sampah Aktif</div>
                </div>
                <div class="stats-icon-box" style="background:#f3e8ff; color:#8b5cf6;">
                    <i class="bi bi-hdd-stack-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8 animate-slide-up" style="animation-delay: 0.6s;">
        <div class="card custom-card h-100">
            <div class="card-header card-header-custom">
                <i class="bi bi-graph-up-arrow text-primary me-2"></i>Tren Setoran (7 Hari Terakhir)
            </div>
            <div class="card-body">
                <canvas id="transaksiChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 animate-slide-up" style="animation-delay: 0.7s;">
        <div class="card custom-card h-100">
            <div class="card-header card-header-custom">
                <i class="bi bi-trophy-fill text-warning me-2"></i>Peringkat Mahasiswa
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($topMahasiswa as $index => $mhs)
                        <div class="list-group-item d-flex align-items-center gap-3 py-3 border-light border-bottom">
                            <div class="rank-circle {{ $index < 3 ? 'rank-'.($index+1) : 'bg-light' }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark">{{ $mhs->name }}</div>
                                <small class="text-muted"><i class="bi bi-stars text-warning me-1"></i>{{ $mhs->level->nama_level ?? 'Eco Starter' }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">{{ number_format($mhs->total_poin) }}</div>
                                <small class="text-muted" style="font-size: 0.7rem;">PTS</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-center py-4 text-muted">Belum ada data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4 animate-slide-up" style="animation-delay: 0.8s;">
        <div class="card custom-card h-100">
            <div class="card-header card-header-custom">
                <i class="bi bi-pie-chart-fill text-info me-2"></i>Distribusi Jenis Sampah
            </div>
            <div class="card-body">
                <canvas id="jenisSampahChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-slide-up" style="animation-delay: 0.9s;">
        <div class="card custom-card h-100">
            <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history text-secondary me-2"></i>Transaksi Terbaru</span>
                <a href="{{ route('admin.transaksi.index') }}" class="btn btn-sm btn-light text-primary fw-bold">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Mahasiswa</th>
                                <th>Berat</th>
                                <th>Poin</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransaksi as $trx)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m H:i') }}</td>
                                <td>{{ $trx->mahasiswa->name ?? '-' }}</td>
                                <td>{{ number_format($trx->berat / 1000, 2) }} kg</td>
                                <td class="text-success fw-bold">+{{ $trx->poin_didapat }}</td>
                                <td><span class="badge bg-success bg-opacity-10 text-success">Valid</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const greetingEl = document.getElementById('dynamic-greeting');
        const hour = new Date().getHours();
        let greeting = "Pagi";
        if (hour >= 11 && hour < 15) greeting = "Siang";
        else if (hour >= 15 && hour < 18) greeting = "Sore";
        else if (hour >= 18 || hour < 4) greeting = "Malam";
        greetingEl.innerText = `Selamat ${greeting}, Administrator! 👋`;
    });

    const ctxBar = document.getElementById('transaksiChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))) !!},
            datasets: [
                {
                    label: 'Berat (kg)',
                    data: {!! json_encode($chartData->pluck('total_berat_gram')->map(fn($v) => round($v / 1000, 2))) !!},
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderRadius: 5,
                    yAxisID: 'y',
                },
                {
                    label: 'Botol',
                    data: {!! json_encode($chartData->pluck('total_botol')) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderRadius: 5,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, position: 'left' },
                y1: { beginAtZero: true, position: 'right', grid: { display: false } }
            }
        }
    });

    const ctxDoughnut = document.getElementById('jenisSampahChart');
    new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($jenisSampahStats->pluck('jenisSampah.nama')) !!},
            datasets: [{
                data: {!! json_encode($jenisSampahStats->pluck('total_berat')) !!},
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '70%',
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endpush