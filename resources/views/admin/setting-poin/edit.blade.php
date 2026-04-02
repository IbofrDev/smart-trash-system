@extends('layouts.admin')

@section('title', 'Edit Setting Poin')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-gear me-2"></i>Edit Setting: {{ $settingPoin->nama_setting }}
            </div>
            <div class="card-body">
                <form action="{{ route('admin.setting-poin.update', $settingPoin) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Setting</label>
                        <input type="text" class="form-control" value="{{ $settingPoin->nama_setting }}" disabled>
                        <small class="text-muted">Nama setting tidak dapat diubah</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Value <span class="text-danger">*</span></label>
                        <input type="number" name="value" class="form-control @error('value') is-invalid @enderror"
                            value="{{ old('value', $settingPoin->value) }}" min="0" required>
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                            rows="3">{{ old('deskripsi', $settingPoin->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Update
                        </button>
                        <a href="{{ route('admin.setting-poin.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection