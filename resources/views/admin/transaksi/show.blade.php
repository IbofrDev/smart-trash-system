@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt me-2"></i>Detail Transaksi #{{ $transaksi->id }}</span>
                <span class="badge badge-success">Berhasil</span>
            </div>
            <div class="card-body">
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
                        <th>Level</th>
                        <td><span class="badge badge-info">{{ $transaksi->mahasiswa->level->nama_level ?? '-' }}</span></td>
                    </tr>
                </table>

                <hr>
                <h6 class="fw-semibold mb-3"><i class="bi bi-trash3 me-1"></i> Detail Sampah</h6>
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Jenis Sampah</th>
                        <td><span class="badge badge-info">{{ $transaksi->jenisSampah->nama ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <th>Berat</th>
                        <td><span class="fs-5 fw-semibold">{{ number_format($transaksi->berat, 2) }} kg</span></td>
                    </tr>
                    <tr>
                        <th>Poin Didapat</th>
                        <td><span class="fs-5 fw-semibold text-success">+{{ number_format($transaksi->poin_didapat) }} poin</span></td>
                    </tr>
                </table>

                <hr>
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