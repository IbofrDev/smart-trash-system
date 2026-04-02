@extends('layouts.admin')

@section('title', 'Edit Level')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2"></i>Edit Level: {{ $level->nama_level }}
            </div>
            <div class="card-body">
                <form action="{{ route('admin.level.update', $level) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Level <span class="text-danger">*</span></label>
                        <input type="text" name="nama_level" class="form-control @error('nama_level') is-invalid @enderror" 
                            value="{{ old('nama_level', $level->nama_level) }}" required>
                        @error('nama_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Min Poin <span class="text-danger">*</span></label>
                            <input type="number" name="min_poin" class="form-control @error('min_poin') is-invalid @enderror" 
                                value="{{ old('min_poin', $level->min_poin) }}" min="0" required>
                            @error('min_poin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Max Poin <span class="text-danger">*</span></label>
                            <input type="number" name="max_poin" class="form-control @error('max_poin') is-invalid @enderror" 
                                value="{{ old('max_poin', $level->max_poin) }}" min="0" required>
                            @error('max_poin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Urutan <span class="text-danger">*</span></label>
                            <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror" 
                                value="{{ old('urutan', $level->urutan) }}" min="1" required>
                            @error('urutan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Update
                        </button>
                        <a href="{{ route('admin.level.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection