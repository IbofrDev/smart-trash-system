@extends('layouts.admin')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Laporan transaksi pembuangan sampah</p>
    <a href="{{ route('admin.laporan.transaksi.pdf', ['tanggal_dari' => $tanggalDari, 'tanggal_sampai' => $tanggalSampai]) }}"
        class="btn btn-danger" target="_blank">
        <i class="bi bi-file-pdf me-1"></i> Export PDF
    </a>
</div>

<!-- Filter Tanggal -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.laporan.transaksi') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" class="form-control" value="{{ $tanggalDari }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" class="form-control" value="{{ $tanggalSampai }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-funnel me-1"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="text-primary mb-1">{{ number_format($summary['total_transaksi']) }}</h3>
                <small class="text-muted">Total Transaksi</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="text-success mb-1">
                    @if($summary['total_berat'] >= 1000)
                        {{ number_format($summary['total_berat'] / 1000, 2) }} kg
                    @else
                        {{ number_format($summary['total_berat'], 0) }} g
                    @endif
                </h3>
                <small class="text-muted">Total Berat</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 bg-warning bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="text-warning mb-1">{{ number_format($summary['total_poin']) }}</h3>
                <small class="text-muted">Total Poin</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 bg-info bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="text-info mb-1">🪙 {{ number_format($summary['total_koin']) }}</h3>
                <small class="text-muted">Total Koin</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 bg-secondary bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="text-secondary mb-1">{{ number_format($summary['total_botol']) }}</h3>
                <small class="text-muted">Total Botol/Kaleng</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 bg-danger bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="text-danger mb-1">{{ number_format($summary['mahasiswa_aktif']) }}</h3>
                <small class="text-muted">Mahasiswa Aktif</small>
            </div>
        </div>
    </div>
</div>

<!-- Anomali Alert -->
@if($summary['total_anomali'] > 0)
<div class="alert alert-warning d-flex align-items-center mb-4">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    Terdapat <strong class="mx-1">{{ $summary['total_anomali'] }}</strong> transaksi anomali pada periode ini.
</div>
@endif

<div class="row g-4 mb-4">
    <!-- Chart Per Hari -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-graph-up me-2"></i>Tren Harian
            </div>
            <div class="card-body">
                <canvas id="chartHarian" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Per Jenis Sampah -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart me-2"></i>Per Jenis Sampah
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Berat</th>
                                <th>Koin</th>
                                <th>Trx</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($perJenisSampah as $item)
                            <tr>
                                <td>{{ $item->jenisSampah->nama ?? '-' }}</td>
                                <td>{{ number_format($item->total_botol ?? 0) }}</td>
                                <td>
                                    @if(($item->total_berat ?? 0) >= 1000)
                                        {{ number_format($item->total_berat / 1000, 2) }} kg
                                    @else
                                        {{ number_format($item->total_berat ?? 0, 0) }} g
                                    @endif
                                </td>
                                <td>🪙{{ number_format($item->total_koin ?? 0) }}</td>
                                <td>{{ $item->total_transaksi }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Transaksi -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-list-ul me-2"></i>Detail Transaksi
        <span class="badge bg-secondary ms-2">{{ $transaksis->count() }} data</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Waktu</th>
                        <th>Mahasiswa</th>
                        <th>Bak Sampah</th>
                        <th>Jumlah</th>
                        <th>Berat</th>
                        <th>Poin</th>
                        <th>Koin</th>
                        <th>Validasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $index => $trx)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><small>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y H:i') }}</small></td>
                        <td>
                            <div>{{ $trx->mahasiswa->name ?? '-' }}</div>
                            <small class="text-muted">{{ $trx->mahasiswa->nim ?? '' }}</small>
                        </td>
                        <td>{{ $trx->bakSampah->nama ?? '-' }}</td>
                        <td>{{ $trx->jumlah_final ?? 0 }} pcs</td>
                        <td>
                            @if($trx->berat >= 1000)
                                {{ number_format($trx->berat / 1000, 2) }} kg
                            @else
                                {{ number_format($trx->berat ?? 0, 0) }} g
                            @endif
                        </td>
                        <td><span class="text-success">+{{ number_format($trx->poin_didapat ?? 0) }}</span></td>
                        <td><span class="text-info">🪙{{ number_format($trx->koin_didapat ?? 0) }}</span></td>
                        <td>
                            @if(($trx->status_validasi ?? 'valid') === 'valid')
                                <span class="badge bg-success">Valid</span>
                            @else
                                <span class="badge bg-warning text-dark">Anomali</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">Tidak ada transaksi pada periode ini</td>
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
const ctx = document.getElementById('chartHarian').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($perHari->pluck('tanggal')->map(fn($t) => \Carbon\Carbon::parse($t)->format('d/m'))) !!},
        datasets: [
            {
                label: 'Jumlah Transaksi',
                data: {!! json_encode($perHari->pluck('total_transaksi')) !!},
                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1,
                yAxisID: 'y'
            },
            {
                label: 'Total Koin',
                data: {!! json_encode($perHari->pluck('total_koin')) !!},
                type: 'line',
                borderColor: 'rgba(23, 162, 184, 1)',
                backgroundColor: 'rgba(23, 162, 184, 0.1)',
                borderWidth: 2,
                fill: true,
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        scales: {
            y: {
                beginAtZero: true,
                position: 'left',
                title: { display: true, text: 'Transaksi' }
            },
            y1: {
                beginAtZero: true,
                position: 'right',
                grid: { drawOnChartArea: false },
                title: { display: true, text: 'Koin' }
            }
        }
    }
});
</script>
@endpush
@endsection