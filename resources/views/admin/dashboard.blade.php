@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stats-value">{{ number_format($stats['total_mahasiswa']) }}</div>
                    <div class="stats-label">Total Mahasiswa</div>
                </div>
                <div class="stats-icon" style="background:#dbeafe;color:#2563eb;">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-xl-3">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stats-value">{{ number_format($stats['total_transaksi']) }}</div>
                    <div class="stats-label">Total Transaksi</div>
                </div>
                <div class="stats-icon" style="background:#d1fae5;color:#059669;">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-xl-3">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stats-value">{{ number_format($stats['total_berat'], 1) }} <small class="fw-normal text-muted">kg</small></div>
                    <div class="stats-label">Total Sampah</div>
                </div>
                <div class="stats-icon" style="background:#fef3c7;color:#d97706;">
                    <i class="bi bi-trash3-fill"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-xl-3">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stats-value">{{ $stats['bak_sampah_aktif'] }}/{{ $stats['bak_sampah_total'] }}</div>
                    <div class="stats-label">Bak Sampah Aktif</div>
                </div>
                <div class="stats-icon" style="background:#ede9fe;color:#7c3aed;">
                    <i class="bi bi-hdd-stack-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today Stats -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-calendar-day me-2"></i>Statistik Hari Ini</span>
                <span class="text-muted">{{ now()->format('d M Y') }}</span>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h3 class="text-primary mb-1">{{ $statsToday['transaksi'] }}</h3>
                        <small class="text-muted">Transaksi</small>
                    </div>
                    <div class="col-md-4">
                        <h3 class="text-success mb-1">{{ number_format($statsToday['berat'], 2) }} kg</h3>
                        <small class="text-muted">Berat Sampah</small>
                    </div>
                    <div class="col-md-4">
                        <h3 class="text-warning mb-1">{{ number_format($statsToday['poin']) }}</h3>
                        <small class="text-muted">Poin Didistribusikan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Chart -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-graph-up me-2"></i>Transaksi 7 Hari Terakhir
            </div>
            <div class="card-body">
                <canvas id="transaksiChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Top Mahasiswa -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-trophy me-2"></i>Top 5 Mahasiswa
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($topMahasiswa as $index => $mhs)
                    <div class="list-group-item d-flex align-items-center gap-3">
                        <div class="fw-bold text-muted" style="width:24px;">#{{ $index + 1 }}</div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $mhs->name }}</div>
                            <small class="text-muted">{{ $mhs->level->nama_level ?? 'Eco Starter' }}</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-primary">{{ number_format($mhs->total_poin) }}</div>
                            <small class="text-muted">poin</small>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted">
                        Belum ada data
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-0">
    <!-- Distribusi Jenis Sampah -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart me-2"></i>Distribusi Jenis Sampah
            </div>
            <div class="card-body">
                @forelse($jenisSampahStats as $js)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>{{ $js->jenisSampah->nama ?? 'Unknown' }}</span>
                        <span class="fw-semibold">{{ number_format($js->total_berat, 1) }} kg</span>
                    </div>
                    @php
                        $maxBerat = $jenisSampahStats->max('total_berat');
                        $percentage = $maxBerat > 0 ? ($js->total_berat / $maxBerat) * 100 : 0;
                    @endphp
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Transaksi Terbaru -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Transaksi Terbaru</span>
                <a href="{{ route('admin.transaksi.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Mahasiswa</th>
                                <th>Jenis</th>
                                <th>Berat</th>
                                <th>Poin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransaksi as $trx)
                            <tr>
                                <td>
                                    <small>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m H:i') }}</small>
                                </td>
                                <td>{{ $trx->mahasiswa->name ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $trx->jenisSampah->nama ?? '-' }}</span>
                                </td>
                                <td>{{ number_format($trx->berat, 2) }} kg</td>
                                <td><span class="fw-semibold text-success">+{{ $trx->poin_didapat }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada transaksi</td>
                            </tr>
                            @endforelse
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
const ctx = document.getElementById('transaksiChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartData->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))) !!},
        datasets: [{
            label: 'Berat (kg)',
            data: {!! json_encode($chartData->pluck('total_berat')) !!},
            backgroundColor: 'rgba(16, 185, 129, 0.8)',
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f3f4f6' }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});
</script>
@endpush