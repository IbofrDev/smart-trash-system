@extends('layouts.admin')

@section('title', 'Tambah Achievement')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-trophy me-2"></i>Form Tambah Achievement
            </div>
            <div class="card-body">
                <form action="{{ route('admin.achievement.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Achievement <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama') }}" placeholder="Contoh: First Timer" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                            rows="3" placeholder="Deskripsi achievement...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Icon (Bootstrap Icons class)</label>
                        <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror"
                            value="{{ old('icon') }}" placeholder="Contoh: bi bi-star-fill">
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Referensi: <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a></small>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Syarat Type <span class="text-danger">*</span></label>
                            <select name="syarat_type" class="form-select @error('syarat_type') is-invalid @enderror" required>
                                <option value="">Pilih Syarat</option>
                                <option value="total_kg" {{ old('syarat_type') == 'total_kg' ? 'selected' : '' }}>Total Kg</option>
                                <option value="streak" {{ old('syarat_type') == 'streak' ? 'selected' : '' }}>Streak Hari</option>
                                <option value="transaksi_count" {{ old('syarat_type') == 'transaksi_count' ? 'selected' : '' }}>Jumlah Transaksi</option>
                                <option value="first_time" {{ old('syarat_type') == 'first_time' ? 'selected' : '' }}>Pertama Kali</option>
                            </select>
                            @error('syarat_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Syarat Value <span class="text-danger">*</span></label>
                            <input type="number" name="syarat_value" class="form-control @error('syarat_value') is-invalid @enderror"
                                value="{{ old('syarat_value', 0) }}" min="0" required>
                            @error('syarat_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Poin Bonus <span class="text-danger">*</span></label>
                            <input type="number" name="poin_bonus" class="form-control @error('poin_bonus') is-invalid @enderror"
                                value="{{ old('poin_bonus', 0) }}" min="0" required>
                            @error('poin_bonus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan
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