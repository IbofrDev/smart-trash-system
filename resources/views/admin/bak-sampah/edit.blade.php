@extends('layouts.admin')

@section('title', 'Edit Bak Sampah')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2"></i>Edit Bak Sampah: {{ $bakSampah->nama }}
            </div>
            <div class="card-body">
                <form action="{{ route('admin.bak-sampah.update', $bakSampah) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Bak Sampah <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                            value="{{ old('nama', $bakSampah->nama) }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                        <select name="lokasi_id" class="form-select @error('lokasi_id') is-invalid @enderror" required>
                            <option value="">Pilih Lokasi</option>
                            @foreach($lokasis as $lokasi)
                                <option value="{{ $lokasi->id }}" {{ old('lokasi_id', $bakSampah->lokasi_id) == $lokasi->id ? 'selected' : '' }}>
                                    {{ $lokasi->nama_lokasi }}
                                </option>
                            @endforeach
                        </select>
                        @error('lokasi_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="aktif" {{ old('status', $bakSampah->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $bakSampah->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                <option value="maintenance" {{ old('status', $bakSampah->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kapasitas Maksimum (kg)</label>
                            <input type="number" step="0.01" name="kapasitas_max" class="form-control @error('kapasitas_max') is-invalid @enderror" 
                                value="{{ old('kapasitas_max', $bakSampah->kapasitas_max) }}">
                            @error('kapasitas_max')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Update
                        </button>
                        <a href="{{ route('admin.bak-sampah.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection