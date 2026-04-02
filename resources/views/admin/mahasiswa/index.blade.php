@extends('layouts.admin')

@section('title', 'Kelola Mahasiswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Data mahasiswa pengguna sistem</p>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.mahasiswa.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama / email / NIM / RFID..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="level_id" class="form-select">
                    <option value="">Semua Level</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                            {{ $level->nama_level }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
            </div>
            @if(request()->hasAny(['search', 'level_id']))
            <div class="col-md-2">
                <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
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
                        <th>Email</th>
                        <th>NIM</th>
                        <th>RFID</th>
                        <th>Level</th>
                        <th>Total Poin</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $index => $mhs)
                    <tr>
                        <td>{{ $mahasiswas->firstItem() + $index }}</td>
                        <td class="fw-semibold">{{ $mhs->name }}</td>
                        <td>{{ $mhs->email }}</td>
                        <td>{{ $mhs->nim ?? '-' }}</td>
                        <td>
                            @if($mhs->rfid_uid)
                                <code>{{ $mhs->rfid_uid }}</code>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><span class="badge badge-info">{{ $mhs->level->nama_level ?? '-' }}</span></td>
                        <td><span class="text-success fw-semibold">{{ number_format($mhs->total_poin) }}</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.mahasiswa.show', $mhs) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.mahasiswa.edit', $mhs) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Hapus"
                                    onclick="confirmDelete('{{ route('admin.mahasiswa.destroy', $mhs) }}', '{{ $mhs->name }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="bi bi-people display-6 d-block mb-2"></i>
                            Tidak ada data mahasiswa
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($mahasiswas->hasPages())
    <div class="card-footer">
        {{ $mahasiswas->links() }}
    </div>
    @endif
</div>

@include('components.delete-modal')
@endsection