@extends('layouts.admin')

@section('title', 'Kelola Achievement')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Kelola achievement gamifikasi mahasiswa</p>
    <a href="{{ route('admin.achievement.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Achievement
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Icon</th>
                        <th>Nama</th>
                        <th>Syarat</th>
                        <th>Poin Bonus</th>
                        <th>Diraih</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($achievements as $index => $achievement)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($achievement->icon)
                                <i class="{{ $achievement->icon }} fs-5"></i>
                            @else
                                <i class="bi bi-trophy fs-5 text-warning"></i>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $achievement->nama }}</div>
                            <small class="text-muted">{{ Str::limit($achievement->deskripsi, 50) }}</small>
                        </td>
                        <td>
                            @switch($achievement->syarat_type)
                                @case('total_kg')
                                    <span class="badge badge-info">Total {{ $achievement->syarat_value }} kg</span>
                                    @break
                                @case('streak')
                                    <span class="badge badge-warning">Streak {{ $achievement->syarat_value }} hari</span>
                                    @break
                                @case('transaksi_count')
                                    <span class="badge badge-success">{{ $achievement->syarat_value }} transaksi</span>
                                    @break
                                @case('first_time')
                                    <span class="badge bg-secondary">Pertama Kali</span>
                                    @break
                            @endswitch
                        </td>
                        <td><span class="text-success fw-semibold">+{{ number_format($achievement->poin_bonus) }}</span></td>
                        <td><span class="badge bg-info">{{ $achievement->mahasiswas_count }} orang</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.achievement.edit', $achievement) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Hapus"
                                    onclick="confirmDelete('{{ route('admin.achievement.destroy', $achievement) }}', '{{ $achievement->nama }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-trophy display-6 d-block mb-2"></i>
                            Tidak ada data achievement
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