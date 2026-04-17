@extends('layouts.admin')

@section('title', 'Data Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Riwayat transaksi pembuangan sampah</p>
</div>

<!-- Summary Cards -->
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
                <h3 class="text-success mb-1">{{ number_format($summary['total_berat'], 0) }} g</h3>
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
                <h3 class="text-info mb-1">{{ number_format($summary['total_koin'] ?? 0) }}</h3>
                <small class="text-muted">Total Koin</small>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.transaksi.index') }}" method="GET" class="row g-3">
            <div class="col-md-2">
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Jenis Sampah</label>
                <select name="jenis_sampah_id" class="form-select">
                    <option value="">Semua</option>
                    @foreach($jenisSampahs as $jenis)
                        <option value="{{ $jenis->id }}" {{ request('jenis_sampah_id') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Bak Sampah</label>
                <select name="bak_sampah_id" class="form-select">
                    <option value="">Semua</option>
                    @foreach($bakSampahs as $bak)
                        <option value="{{ $bak->id }}" {{ request('bak_sampah_id') == $bak->id ? 'selected' : '' }}>
                            {{ $bak->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Status Validasi</label>
                <select name="status_validasi" class="form-select">
                    <option value="">Semua</option>
                    <option value="valid" {{ request('status_validasi') == 'valid' ? 'selected' : '' }}>Valid</option>
                    <option value="anomali" {{ request('status_validasi') == 'anomali' ? 'selected' : '' }}>Anomali</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-secondary">
                    <i class="bi bi-search"></i>
                </button>
                @if(request()->hasAny(['tanggal_dari', 'tanggal_sampai', 'jenis_sampah_id', 'bak_sampah_id', 'status_validasi', 'search']))
                    <a href="{{ route('admin.transaksi.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Waktu</th>
                        <th>Mahasiswa</th>
                        <th>Bak Sampah</th>
                        <th>Jumlah</th>
                        <th>Berat</th>
                        <th>Poin</th>
                        <th>Koin</th>
                        <th>Validasi</th>
                        <th width="60">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $index => $trx)
                    <tr>
                        <td>{{ $transaksis->firstItem() + $index }}</td>
                        <td><small>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y H:i') }}</small></td>
                        <td>
                            <div class="fw-semibold">{{ $trx->mahasiswa->name ?? '-' }}</div>
                            <small class="text-muted">{{ $trx->mahasiswa->nim ?? '' }}</small>
                        </td>
                        <td>{{ $trx->bakSampah->nama ?? '-' }}</td>
                        <td>
                            <div class="fw-semibold">{{ $trx->jumlah_final ?? $trx->jumlah_botol ?? 0 }} pcs</div>
                            @if(($trx->jumlah_botol ?? 0) > 0 || ($trx->jumlah_kaleng ?? 0) > 0)
                            <small class="text-muted">
                                🍶{{ $trx->jumlah_botol ?? 0 }} 🥫{{ $trx->jumlah_kaleng ?? 0 }}
                            </small>
                            @endif
                        </td>
                        <td>
                            @if($trx->berat)
                                @if($trx->berat >= 1000)
                                    {{ number_format($trx->berat / 1000, 2) }} kg
                                @else
                                    {{ number_format($trx->berat, 0) }} g
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td><span class="text-success fw-semibold">+{{ number_format($trx->poin_didapat ?? 0) }}</span></td>
                        <td><span class="text-info fw-semibold">🪙{{ number_format($trx->koin_didapat ?? 0) }}</span></td>
                        <td>
                            @if(($trx->status_validasi ?? 'valid') === 'valid')
                                <span class="badge bg-success">✓ Valid</span>
                            @else
                                <span class="badge bg-warning text-dark">⚠ Anomali</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.transaksi.show', $trx) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox display-6 d-block mb-2"></i>
                            Tidak ada data transaksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($transaksis->hasPages())
    <div class="card-footer">
        {{ $transaksis->links() }}
    </div>
    @endif
</div>
@endsection