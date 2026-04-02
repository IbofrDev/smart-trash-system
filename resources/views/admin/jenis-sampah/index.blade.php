@extends('layouts.admin')

@section('title', 'Kelola Jenis Sampah')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Kelola kategori jenis sampah dan poin per kg</p>
    <a href="{{ route('admin.jenis-sampah.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Jenis Sampah
    </a>
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
                        <th>Deskripsi</th>
                        <th>Poin/kg</th>
                        <th>Satuan</th>
                        <th>Status</th>
                        <th>Transaksi</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenisSampahs as $index => $jenis)
                    <tr>
                        <td>{{ $jenisSampahs->firstItem() + $index }}</td>
                        <td class="fw-semibold">{{ $jenis->nama }}</td>
                        <td>{{ Str::limit($jenis->deskripsi, 40) ?? '-' }}</td>
                        <td><span class="badge bg-success">{{ number_format($jenis->poin_per_kg) }} poin</span></td>
                        <td>{{ $jenis->satuan }}</td>
                        <td>
                            @if($jenis->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>{{ number_format($jenis->transaksi_sampah_count) }}x</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.jenis-sampah.edit', $jenis) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Hapus" 
                                    onclick="confirmDelete('{{ route('admin.jenis-sampah.destroy', $jenis) }}', '{{ $jenis->nama }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            Tidak ada data jenis sampah
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($jenisSampahs->hasPages())
    <div class="card-footer">
        {{ $jenisSampahs->links() }}
    </div>
    @endif
</div>

@include('components.delete-modal')
@endsection