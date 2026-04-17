@extends('layouts.admin')

@section('title', 'Data Voucher')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">🎟️ Data Voucher</h1>
            <p class="text-muted mb-0">Kelola voucher makan mahasiswa</p>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Voucher</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-ticket-alt fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['aktif'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Terpakai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['terpakai'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-shopping-bag fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Expired</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['expired'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-clock fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.voucher.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Cari Mahasiswa</label>
                    <input type="text" name="search" class="form-control"
                        placeholder="Nama atau NIM..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="terpakai" {{ request('status') == 'terpakai' ? 'selected' : '' }}>Terpakai</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.voucher.index') }}" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Daftar Voucher
                <span class="badge bg-secondary ms-2">{{ $vouchers->total() }} total</span>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kode Voucher</th>
                            <th>Mahasiswa</th>
                            <th>Status</th>
                            <th>Koin Digunakan</th>
                            <th>Dibuat</th>
                            <th>Expired</th>
                            <th>Digunakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vouchers as $index => $voucher)
                        <tr>
                            <td>{{ $vouchers->firstItem() + $index }}</td>
                            <td>
                                <code class="fw-bold">{{ $voucher->kode_voucher }}</code>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $voucher->mahasiswa->name ?? '-' }}</div>
                                <small class="text-muted">{{ $voucher->mahasiswa->nim ?? '-' }}</small>
                            </td>
                            <td>
                                @if($voucher->status === 'aktif' && $voucher->expired_at > now())
                                    <span class="badge bg-success">✅ Aktif</span>
                                @elseif($voucher->status === 'terpakai')
                                    <span class="badge bg-info">✓ Terpakai</span>
                                @else
                                    <span class="badge bg-secondary">⏱️ Expired</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">
                                    🪙 {{ $voucher->koin_digunakan }} koin
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($voucher->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($voucher->expired_at)->format('d/m/Y H:i') }}
                                @if($voucher->status === 'aktif' && $voucher->expired_at > now())
                                    <br>
                                    <small class="text-success">
                                        {{ \Carbon\Carbon::parse($voucher->expired_at)->diffForHumans() }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($voucher->used_at)
                                    {{ \Carbon\Carbon::parse($voucher->used_at)->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Tidak ada data voucher
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $vouchers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection