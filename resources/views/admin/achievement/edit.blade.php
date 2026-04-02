@extends('layouts.admin')

@section('title', 'Edit Achievement')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2"></i>Edit Achievement: {{ $achievement->nama }}
            </div>
            <div class="card-body">
                <form action="{{ route('admin.achievement.update', $achievement) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Achievement <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama', $achievement->nama) }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                            rows="3">{{ old('deskripsi', $achievement->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Icon (Bootstrap Icons class)</label>
                        <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror"
                            value="{{ old('icon', $achievement->icon) }}" placeholder="Contoh: bi bi-star-fill">
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Syarat Type <span class="text-danger">*</span></label>
                            <select name="syarat_type" class="form-select @error('syarat_type') is-invalid @enderror" required>
                                <option value="total_kg" {{ old('syarat_type', $achievement->syarat_type) == 'total_kg' ? 'selected' : '' }}>Total Kg</option>
                                <option value="streak" {{ old('syarat_type', $achievement->syarat_type) == 'streak' ? 'selected' : '' }}>Streak Hari</option>
                                <option value="transaksi_count" {{ old('syarat_type', $achievement->syarat_type) == 'transaksi_count' ? 'selected' : '' }}>Jumlah Transaksi</option>
                                <option value="first_time" {{ old('syarat_type', $achievement->syarat_type) == 'first_time' ? 'selected' : '' }}>Pertama Kali</option>
                            </select>
                            @error('syarat_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Syarat Value <span class="text-danger">*</span></label>
                            <input type="number" name="syarat_value" class="form-control @error('syarat_value') is-invalid @enderror"
                                value="{{ old('syarat_value', $achievement->syarat_value) }}" min="0" required>
                            @error('syarat_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Poin Bonus <span class="text-danger">*</span></label>
                            <input type="number" name="poin_bonus" class="form-control @error('poin_bonus') is-invalid @enderror"
                                value="{{ old('poin_bonus', $achievement->poin_bonus) }}" min="0" required>
                            @error('poin_bonus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Update
                        </button>
                        <a href="{{ route('admin.achievement.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection