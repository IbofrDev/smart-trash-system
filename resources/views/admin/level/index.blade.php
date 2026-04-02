@extends('layouts.admin')

@section('title', 'Kelola Level')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Kelola level gamifikasi mahasiswa</p>
    <a href="{{ route('admin.level.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Level
    </a>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="80">Urutan</th>
                        <th>Nama Level</th>
                        <th>Min Poin</th>
                        <th>Max Poin</th>
                        <th>Range</th>
                        <th>Mahasiswa</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($levels as $level)
                    <tr>
                        <td>
                            <span class="badge bg-secondary">{{ $level->urutan }}</span>
                        </td>
                        <td class="fw-semibold">{{ $level->nama_level }}</td>
                        <td>{{ number_format($level->min_poin) }}</td>
                        <td>{{ number_format($level->max_poin) }}</td>
                        <td>
                            <div class="progress" style="height: 6px; width: 100px;">
                                @php
                                    $maxPoin = $levels->max('max_poin');
                                    $width = $maxPoin > 0 ? ($level->max_poin / $maxPoin) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-success" style="width: {{ $width }}%"></div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $level->mahasiswas_count }} orang</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.level.edit', $level) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Hapus" 
                                    onclick="confirmDelete('{{ route('admin.level.destroy', $level) }}', '{{ $level->nama_level }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            Tidak ada data level
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('components.delete-modal')
@endsection