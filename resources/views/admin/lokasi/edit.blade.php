@extends('layouts.admin')

@section('title', 'Edit Lokasi')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2"></i>Edit Lokasi: {{ $lokasi->nama_lokasi }}
            </div>
            <div class="card-body">
                <form action="{{ route('admin.lokasi.update', $lokasi) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Lokasi <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lokasi" class="form-control @error('nama_lokasi') is-invalid @enderror" 
                            value="{{ old('nama_lokasi', $lokasi->nama_lokasi) }}" required>
                        @error('nama_lokasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat', $lokasi->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Koordinat</label>
                        <input type="text" name="koordinat" class="form-control @error('koordinat') is-invalid @enderror" 
                            value="{{ old('koordinat', $lokasi->koordinat) }}">
                        @error('koordinat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Update
                        </button>
                        <a href="{{ route('admin.lokasi.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection