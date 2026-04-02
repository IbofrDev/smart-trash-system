@extends('layouts.admin')

@section('title', 'Log Aktivitas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Riwayat aktivitas pengguna sistem</p>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.log-aktivitas.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari aktivitas..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="user_type" class="form-select">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="pengelola" {{ request('user_type') == 'pengelola' ? 'selected' : '' }}>Pengelola</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}" placeholder="Dari">
            </div>
            <div class="col-md-2">
                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}" placeholder="Sampai">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-search"></i>
                </button>
            </div>
            @if(request()->hasAny(['search', 'user_type', 'tanggal_dari', 'tanggal_sampai']))
            <div class="col-md-2">
                <a href="{{ route('admin.log-aktivitas.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
            @endif
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
                        <th width="150">Waktu</th>
                        <th width="100">Role</th>
                        <th>Aktivitas</th>
                        <th width="120">Tabel</th>
                        <th width="120">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                    <tr>
                        <td>{{ $logs->firstItem() + $index }}</td>
                       <td><small>{{ $log->created_at ? \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') : '-' }}</small></td>
                        <td>
                            @if($log->user_type === 'admin')
                                <span class="badge bg-primary">Admin</span>
                            @else
                                <span class="badge bg-secondary">Pengelola</span>
                            @endif
                        </td>
                        <td>{{ $log->aktivitas }}</td>
                        <td>
                            @if($log->tabel_target)
                                <code>{{ $log->tabel_target }}</code>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $log->ip_address }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bi bi-journal display-6 d-block mb-2"></i>
                            Tidak ada log aktivitas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
    <div class="card-footer">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection