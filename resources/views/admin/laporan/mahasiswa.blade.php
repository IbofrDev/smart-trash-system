@extends('layouts.admin')

@section('title', 'Laporan Mahasiswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Laporan performa mahasiswa dalam pengelolaan sampah</p>
    <a href="{{ route('admin.laporan.mahasiswa.pdf') }}" class="btn btn-danger" target="_blank">
        <i class="bi bi-file-pdf me-1"></i> Export PDF
    </a>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="text-primary mb-1">{{ number_format($summary['total_mahasiswa']) }}</h3>
                <small class="text-muted">Total Mahasiswa</small>
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
                <h3 class="text-danger mb-1">{{ number_format($summary['total_transaksi']) }}</h3>
                <small class="text-muted">Total Transaksi</small>
            </div>
        </div>
    </div>
</div>

<!-- Sort Control -->
<div class="card mb-3">
    <div class="card-body py-2">
        <form action="{{ route('admin.laporan.mahasiswa') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-auto">
                <label class="form-label small mb-0">Urutkan:</label>
            </div>
            <div class="col-auto">
                <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="total_poin" {{ request('sort','total_poin') == 'total_poin' ? 'selected' : '' }}>Total Poin</option>
                    <option value="total_koin" {{ request('sort') == 'total_koin' ? 'selected' : '' }}>Total Koin</option>
                    <option value="total_berat" {{ request('sort') == 'total_berat' ? 'selected' : '' }}>Total Berat</option>
                    <option value="transaksi_count" {{ request('sort') == 'transaksi_count' ? 'selected' : '' }}>Jumlah Transaksi</option>
                </select>
            </div>
            <div class="col-auto">
                <select name="dir" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="desc" {{ request('dir','desc') == 'desc' ? 'selected' : '' }}>Tertinggi</option>
                    <option value="asc" {{ request('dir') == 'asc' ? 'selected' : '' }}>Terendah</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-people me-2"></i>Ranking Mahasiswa
        <span class="badge bg-secondary ms-2">{{ $mahasiswas->count() }} mahasiswa</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="60" class="text-center">Rank</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Level</th>
                        <th class="text-end">Total Poin</th>
                        <th class="text-end">Total Koin</th>
                        <th class="text-end">Botol/Kaleng</th>
                        <th class="text-end">Total Berat</th>
                        <th class="text-end">Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $index => $mhs)
                    <tr>
                        <td class="text-center">
                            @if($index == 0)
                                <span class="badge bg-warning text-dark">🥇 1</span>
                            @elseif($index == 1)
                                <span class="badge bg-secondary">🥈 2</span>
                            @elseif($index == 2)
                                <span class="badge bg-danger">🥉 3</span>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $mhs->name }}</td>
                        <td>{{ $mhs->nim ?? '-' }}</td>
                        <td><span class="badge badge-info">{{ $mhs->level->nama_level ?? '-' }}</span></td>
                        <td class="text-end">
                            <span class="text-success fw-semibold">{{ number_format($mhs->total_poin) }}</span>
                        </td>
                        <td class="text-end">
                            <span class="text-info">🪙 {{ number_format($mhs->total_koin_botol ?? 0) }}</span>
                        </td>
                        <td class="text-end">
                            {{ number_format($mhs->transaksi_sampah_sum_jumlah_final ?? 0) }} pcs
                        </td>
                        <td class="text-end">
                            @php $berat = $mhs->transaksi_sampah_sum_berat ?? 0; @endphp
                            @if($berat >= 1000)
                                {{ number_format($berat / 1000, 2) }} kg
                            @else
                                {{ number_format($berat, 0) }} g
                            @endif
                        </td>
                        <td class="text-end">{{ number_format($mhs->transaksi_sampah_count) }}x</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">Tidak ada data mahasiswa</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection