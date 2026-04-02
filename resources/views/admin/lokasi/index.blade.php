@extends('layouts.admin')

@section('title', 'Kelola Lokasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Kelola lokasi penempatan bak sampah</p>
    <a href="{{ route('admin.lokasi.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Lokasi
    </a>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.lokasi.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama lokasi..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
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
                        <th>Nama Lokasi</th>
                        <th>Alamat</th>
                        <th>Koordinat</th>
                        <th>Bak Sampah</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lokasis as $index => $lokasi)
                    <tr>
                        <td>{{ $lokasis->firstItem() + $index }}</td>
                        <td class="fw-semibold">{{ $lokasi->nama_lokasi }}</td>
                        <td>{{ Str::limit($lokasi->alamat, 50) ?? '-' }}</td>
                        <td><code>{{ $lokasi->koordinat ?? '-' }}</code></td>
                        <td>
                            <span class="badge bg-info">{{ $lokasi->bak_sampahs_count }} unit</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.lokasi.show', $lokasi) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.lokasi.edit', $lokasi) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Hapus" 
                                    onclick="confirmDelete('{{ route('admin.lokasi.destroy', $lokasi) }}', '{{ $lokasi->nama_lokasi }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox display-6 d-block mb-2"></i>
                            Tidak ada data lokasi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($lokasis->hasPages())
    <div class="card-footer">
        {{ $lokasis->links() }}
    </div>
    @endif
</div>

@include('components.delete-modal')
@endsection