@extends('layouts.admin')

@section('title', 'Kelola Bak Sampah')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Kelola unit bak sampah pintar</p>
    <a href="{{ route('admin.bak-sampah.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Bak Sampah
    </a>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.bak-sampah.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari nama..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="lokasi_id" class="form-select">
                    <option value="">Semua Lokasi</option>
                    @foreach($lokasis as $lokasi)
                        <option value="{{ $lokasi->id }}" {{ request('lokasi_id') == $lokasi->id ? 'selected' : '' }}>
                            {{ $lokasi->nama_lokasi }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
            </div>
            @if(request()->hasAny(['search', 'lokasi_id', 'status']))
            <div class="col-md-2">
                <a href="{{ route('admin.bak-sampah.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
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
                        <th>Nama</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Kapasitas</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bakSampahs as $index => $bak)
                    <tr>
                        <td>{{ $bakSampahs->firstItem() + $index }}</td>
                        <td class="fw-semibold">{{ $bak->nama }}</td>
                        <td>{{ $bak->lokasi->nama_lokasi ?? '-' }}</td>
                        <td>
                            @if($bak->status === 'aktif')
                                <span class="badge badge-success">Aktif</span>
                            @elseif($bak->status === 'maintenance')
                                <span class="badge badge-warning">Maintenance</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>{{ $bak->kapasitas_max ? number_format($bak->kapasitas_max, 2) . ' kg' : '-' }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.bak-sampah.show', $bak) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.bak-sampah.edit', $bak) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Hapus" 
                                    onclick="confirmDelete('{{ route('admin.bak-sampah.destroy', $bak) }}', '{{ $bak->nama }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox display-6 d-block mb-2"></i>
                            Tidak ada data bak sampah
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($bakSampahs->hasPages())
    <div class="card-footer">
        {{ $bakSampahs->links() }}
    </div>
    @endif
</div>

@include('components.delete-modal')
@endsection