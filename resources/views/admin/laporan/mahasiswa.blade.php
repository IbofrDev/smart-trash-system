@extends('layouts.admin')

@section('title', 'Laporan Mahasiswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Laporan performa mahasiswa dalam pengelolaan sampah</p>
    <a href="{{ route('admin.laporan.mahasiswa.pdf') }}" class="btn btn-danger" target="_blank">
        <i class="bi bi-file-pdf me-1"></i> Export PDF
    </a>
</div>

<!-- Summary -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="text-primary mb-1">{{ number_format($summary['total_mahasiswa']) }}</h3>
                <small class="text-muted">Total Mahasiswa</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="text-success mb-1">{{ number_format($summary['total_berat'], 2) }} kg</h3>
                <small class="text-muted">Total Berat Sampah</small>
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
                <h3 class="text-info mb-1">{{ number_format($summary['total_transaksi']) }}</h3>
                <small class="text-muted">Total Transaksi</small>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-people me-2"></i>Ranking Mahasiswa
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
                        <th class="text-end">Total Berat</th>
                        <th class="text-end">Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $index => $mhs)
                    <tr>
                        <td class="text-center">
                            @if($index < 3)
                                <span class="badge {{ $index == 0 ? 'bg-warning' : ($index == 1 ? 'bg-secondary' : 'bg-danger') }}">
                                    #{{ $index + 1 }}
                                </span>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $mhs->name }}</td>
                        <td>{{ $mhs->nim ?? '-' }}</td>
                        <td><span class="badge badge-info">{{ $mhs->level->nama_level ?? '-' }}</span></td>
                        <td class="text-end"><span class="text-success fw-semibold">{{ number_format($mhs->total_poin) }}</span></td>
                        <td class="text-end">{{ number_format($mhs->transaksi_sampah_sum_berat ?? 0, 2) }} kg</td>
                        <td class="text-end">{{ number_format($mhs->transaksi_sampah_count) }}x</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Tidak ada data mahasiswa</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection