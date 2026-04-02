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

<!-- Summary -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="text-primary mb-1">{{ number_format($summary['total_transaksi']) }}</h3>
                <small class="text-muted">Total Transaksi</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="text-success mb-1">{{ number_format($summary['total_berat'], 2) }} kg</h3>
                <small class="text-muted">Total Berat</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-warning bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="text-warning mb-1">{{ number_format($summary['total_poin']) }}</h3>
                <small class="text-muted">Total Poin</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-info bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="text-info mb-1">{{ number_format($summary['mahasiswa_aktif']) }}</h3>
                <small class="text-muted">Mahasiswa Aktif</small>
            </div>
        </div>
    </div>
</div>

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
                                <th>Berat</th>
                                <th>Trx</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($perJenisSampah as $item)
                            <tr>
                                <td>{{ $item->jenisSampah->nama ?? '-' }}</td>
                                <td>{{ number_format($item->total_berat, 2) }} kg</td>
                                <td>{{ $item->total_transaksi }}</td>
                            </tr>
                            @endforeach
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
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Waktu</th>
                        <th>Mahasiswa</th>
                        <th>Jenis</th>
                        <th>Bak Sampah</th>
                        <th>Berat</th>
                        <th>Poin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $index => $trx)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><small>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y H:i') }}</small></td>
                        <td>{{ $trx->mahasiswa->name ?? '-' }}</td>
                        <td><span class="badge badge-info">{{ $trx->jenisSampah->nama ?? '-' }}</span></td>
                        <td>{{ $trx->bakSampah->nama ?? '-' }}</td>
                        <td>{{ number_format($trx->berat, 2) }} kg</td>
                        <td><span class="text-success">+{{ number_format($trx->poin_didapat) }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Tidak ada transaksi pada periode ini</td>
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
        datasets: [{
            label: 'Berat (kg)',
            data: {!! json_encode($perHari->pluck('total_berat')) !!},
            backgroundColor: 'rgba(40, 167, 69, 0.7)',
            borderColor: 'rgba(40, 167, 69, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script>
@endpush
@endsection