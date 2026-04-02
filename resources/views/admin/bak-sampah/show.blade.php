@extends('layouts.admin')

@section('title', 'Detail Bak Sampah')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Detail Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-trash3 me-2"></i>Detail Bak Sampah</span>
                <a href="{{ route('admin.bak-sampah.edit', $bakSampah) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Nama</th>
                        <td>{{ $bakSampah->nama }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $bakSampah->lokasi->nama_lokasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($bakSampah->status === 'aktif')
                                <span class="badge badge-success">Aktif</span>
                            @elseif($bakSampah->status === 'maintenance')
                                <span class="badge badge-warning">Maintenance</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Kapasitas Max</th>
                        <td>{{ $bakSampah->kapasitas_max ? number_format($bakSampah->kapasitas_max, 2) . ' kg' : '-' }}</td>
                    </tr>
                    <tr>
                        <th>API Key</th>
                        <td>
                            <div class="input-group">
                                <input type="text" class="form-control font-monospace" value="{{ $bakSampah->api_key }}" readonly id="apiKey">
                                <button class="btn btn-outline-secondary" type="button" onclick="copyApiKey()">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>{{ $bakSampah->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                </table>
                
                <form action="{{ route('admin.bak-sampah.regenerate-api-key', $bakSampah) }}" method="POST" class="mt-3"
                    onsubmit="return confirm('Yakin ingin regenerate API Key? Hardware yang menggunakan API Key lama akan terputus.')">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="bi bi-arrow-repeat me-1"></i> Regenerate API Key
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Statistik -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-graph-up me-2"></i>Statistik
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h3 class="text-primary mb-1">{{ number_format($stats['total_transaksi']) }}</h3>
                        <small class="text-muted">Total Transaksi</small>
                    </div>
                    <div class="col-md-4">
                        <h3 class="text-success mb-1">{{ number_format($stats['total_berat'], 2) }} kg</h3>
                        <small class="text-muted">Total Berat</small>
                    </div>
                    <div class="col-md-4">
                        <h3 class="text-warning mb-1">{{ number_format($stats['total_poin']) }}</h3>
                        <small class="text-muted">Total Poin</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Transaksi Terbaru -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>Transaksi Terbaru
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Mahasiswa</th>
                                <th>Jenis</th>
                                <th>Berat</th>
                                <th>Poin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bakSampah->transaksiSampah as $trx)
                            <tr>
                                <td><small>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y H:i') }}</small></td>
                                <td>{{ $trx->mahasiswa->name ?? '-' }}</td>
                                <td><span class="badge badge-info">{{ $trx->jenisSampah->nama ?? '-' }}</span></td>
                                <td>{{ number_format($trx->berat, 2) }} kg</td>
                                <td><span class="text-success">+{{ $trx->poin_didapat }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Belum ada transaksi</td>
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
                <a href="{{ route('admin.bak-sampah.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyApiKey() {
    const apiKey = document.getElementById('apiKey');
    apiKey.select();
    document.execCommand('copy');
    alert('API Key berhasil disalin!');
}
</script>
@endpush
@endsection