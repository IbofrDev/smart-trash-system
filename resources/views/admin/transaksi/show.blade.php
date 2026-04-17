@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt me-2"></i>Detail Transaksi #{{ $transaksi->id }}</span>
                @if(($transaksi->status_validasi ?? 'valid') === 'valid')
                    <span class="badge bg-success">✓ Valid</span>
                @else
                    <span class="badge bg-warning text-dark">⚠ Anomali</span>
                @endif
            </div>
            <div class="card-body">

                {{-- INFO DASAR --}}
                <table class="table table-borderless">
                    <tr>
                        <th width="200">ID Transaksi</th>
                        <td>#{{ $transaksi->id }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal & Waktu</th>
                        <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y, H:i:s') }}</td>
                    </tr>
                </table>

                <hr>

                {{-- MAHASISWA --}}
                <h6 class="fw-semibold mb-3"><i class="bi bi-person me-1"></i> Mahasiswa</h6>
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Nama</th>
                        <td>
                            {{ $transaksi->mahasiswa->name ?? '-' }}
                            @if($transaksi->mahasiswa)
                                <a href="{{ route('admin.mahasiswa.show', $transaksi->mahasiswa) }}" class="ms-2 small">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>NIM</th>
                        <td>{{ $transaksi->mahasiswa->nim ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Level Saat Ini</th>
                        <td><span class="badge badge-info">{{ $transaksi->mahasiswa->level->nama_level ?? '-' }}</span></td>
                    </tr>
                </table>

                <hr>

                {{-- DETAIL JUMLAH --}}
                <h6 class="fw-semibold mb-3"><i class="bi bi-123 me-1"></i> Detail Jumlah</h6>
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Jumlah Botol Plastik</th>
                        <td>{{ $transaksi->jumlah_botol ?? 0 }} pcs</td>
                    </tr>
                    <tr>
                        <th>Jumlah Kaleng Aluminium</th>
                        <td>{{ $transaksi->jumlah_kaleng ?? 0 }} pcs</td>
                    </tr>
                    <tr>
                        <th>Jumlah Terhitung (Sensor)</th>
                        <td>{{ $transaksi->jumlah_terhitung ?? 0 }} pcs</td>
                    </tr>
                    <tr>
                        <th>Jumlah Final</th>
                        <td>
                            <span class="fs-5 fw-semibold">{{ $transaksi->jumlah_final ?? 0 }} pcs</span>
                            @if(($transaksi->jumlah_terhitung ?? 0) > ($transaksi->jumlah_final ?? 0))
                                <small class="text-warning ms-2">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    Penalty {{ ($transaksi->jumlah_terhitung ?? 0) - ($transaksi->jumlah_final ?? 0) }} pcs
                                </small>
                            @endif
                        </td>
                    </tr>
                </table>

                <hr>

                {{-- DETAIL SAMPAH --}}
                <h6 class="fw-semibold mb-3"><i class="bi bi-trash3 me-1"></i> Detail Berat & Reward</h6>
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Berat</th>
                        <td>
                            <span class="fs-5 fw-semibold">
                                @if($transaksi->berat)
                                    @if($transaksi->berat >= 1000)
                                        {{ number_format($transaksi->berat / 1000, 3) }} kg
                                        <small class="text-muted">({{ number_format($transaksi->berat, 0) }} gram)</small>
                                    @else
                                        {{ number_format($transaksi->berat, 0) }} gram
                                    @endif
                                @else
                                    -
                                @endif
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Status Validasi Berat</th>
                        <td>
                            @if(($transaksi->status_validasi ?? 'valid') === 'valid')
                                <span class="badge bg-success">✓ Valid — Berat sesuai ekspektasi</span>
                            @else
                                <span class="badge bg-warning text-dark">⚠ Anomali — Berat tidak sesuai ekspektasi</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Poin Didapat</th>
                        <td><span class="fs-5 fw-semibold text-success">+{{ number_format($transaksi->poin_didapat ?? 0) }} poin</span></td>
                    </tr>
                    <tr>
                        <th>Koin Didapat</th>
                        <td><span class="fs-5 fw-semibold text-info">🪙 {{ number_format($transaksi->koin_didapat ?? 0) }} koin</span></td>
                    </tr>
                </table>

                <hr>

                {{-- LOKASI --}}
                <h6 class="fw-semibold mb-3"><i class="bi bi-geo-alt me-1"></i> Lokasi</h6>
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Bak Sampah</th>
                        <td>{{ $transaksi->bakSampah->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $transaksi->bakSampah->lokasi->nama_lokasi ?? '-' }}</td>
                    </tr>
                </table>

            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('admin.transaksi.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection