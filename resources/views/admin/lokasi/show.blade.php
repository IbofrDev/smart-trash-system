@extends('layouts.admin')

@section('title', 'Detail Lokasi')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-geo-alt me-2"></i>Detail Lokasi</span>
                <a href="{{ route('admin.lokasi.edit', $lokasi) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Nama Lokasi</th>
                        <td>{{ $lokasi->nama_lokasi }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $lokasi->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Koordinat</th>
                        <td><code>{{ $lokasi->koordinat ?? '-' }}</code></td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>{{ $lokasi->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Bak Sampah di Lokasi Ini -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-trash3 me-2"></i>Bak Sampah di Lokasi Ini
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Kapasitas Max</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lokasi->bakSampahs as $bak)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.bak-sampah.show', $bak) }}">{{ $bak->nama }}</a>
                                </td>
                                <td>
                                    @if($bak->status === 'aktif')
                                        <span class="badge badge-success">Aktif</span>
                                    @elseif($bak->status === 'maintenance')
                                        <span class="badge badge-warning">Maintenance</span>
                                    @else
                                        <span class="badge badge-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>{{ $bak->kapasitas_max ? number_format($bak->kapasitas_max, 2) . ' kg' : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">Belum ada bak sampah</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <a href="{{ route('admin.lokasi.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection