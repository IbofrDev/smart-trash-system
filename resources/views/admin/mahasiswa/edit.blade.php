@extends('layouts.admin')

@section('title', 'Edit Mahasiswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2"></i>Edit Mahasiswa: {{ $mahasiswa->name }}
            </div>
            <div class="card-body">
                <form action="{{ route('admin.mahasiswa.update', $mahasiswa) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ $mahasiswa->email }}" disabled>
                        <small class="text-muted">Email tidak dapat diubah (terkait Google Sign-In)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $mahasiswa->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIM</label>
                            <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror"
                                value="{{ old('nim', $mahasiswa->nim) }}" placeholder="Contoh: P3115001">
                            @error('nim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Program Studi</label>
                            <input type="text" name="prodi" class="form-control @error('prodi') is-invalid @enderror"
                                value="{{ old('prodi', $mahasiswa->prodi) }}" placeholder="Contoh: Teknik Informatika">
                            @error('prodi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">RFID UID</label>
                        <input type="text" name="rfid_uid" class="form-control @error('rfid_uid') is-invalid @enderror"
                            value="{{ old('rfid_uid', $mahasiswa->rfid_uid) }}" placeholder="Scan kartu RFID">
                        @error('rfid_uid')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Poin <span class="text-danger">*</span></label>
                            <input type="number" name="total_poin" class="form-control @error('total_poin') is-invalid @enderror"
                                value="{{ old('total_poin', $mahasiswa->total_poin) }}" min="0" required>
                            @error('total_poin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Level <span class="text-danger">*</span></label>
                            <select name="level_id" class="form-select @error('level_id') is-invalid @enderror" required>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}" {{ old('level_id', $mahasiswa->level_id) == $level->id ? 'selected' : '' }}>
                                        {{ $level->nama_level }} ({{ number_format($level->min_poin) }} - {{ number_format($level->max_poin) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('level_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Update
                        </button>
                        <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection