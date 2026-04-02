@extends('layouts.admin')

@section('title', 'Detail Mahasiswa')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <!-- Profile Card -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-person-fill text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="mb-1">{{ $mahasiswa->name }}</h5>
                <p class="text-muted mb-2">{{ $mahasiswa->email }}</p>
                <span class="badge badge-info">{{ $mahasiswa->level->nama_level ?? '-' }}</span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <span>NIM</span>
                    <strong>{{ $mahasiswa->nim ?? '-' }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Prodi</span>
                    <strong>{{ $mahasiswa->prodi ?? '-' }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>RFID</span>
                    <strong><code>{{ $mahasiswa->rfid_uid ?? '-' }}</code></strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total Poin</span>
                    <strong class="text-success">{{ number_format($mahasiswa->total_poin) }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Ranking</span>
                    <strong>
                        @if($mahasiswa->leaderboard)
                            #{{ $mahasiswa->leaderboard->ranking_mingguan ?? '-' }}
                        @else
                            -
                        @endif
                    </strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Bergabung</span>
                    <strong>{{ $mahasiswa->created_at->format('d M Y') }}</strong>
                </li>
            </ul>
            <div class="card-body">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}" class="btn btn-primary btn-sm flex-fill">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary btn-sm flex-fill">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Achievements -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-trophy me-2"></i>Achievement ({{ $mahasiswa->achievements->count() }})
            </div>
            <div class="card-body">
                @if($mahasiswa->achievements->count() > 0)
                    <div class="row g-2">
                        @foreach($mahasiswa->achievements as $ach)
                        <div class="col-md-6">
                            <div class="border rounded p-2 d-flex align-items-center">
                                <i class="{{ $ach->icon ?? 'bi bi-trophy' }} text-warning me-2 fs-5"></i>
                                <div>
                                    <div class="fw-semibold small">{{ $ach->nama }}</div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($ach->pivot->unlocked_at)->format('d M Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center mb-0">Belum ada achievement</p>
                @endif
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
                                <th>Jenis</th>
                                <th>Bak Sampah</th>
                                <th>Berat</th>
                                <th>Poin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mahasiswa->transaksiSampah as $trx)
                            <tr>
                                <td><small>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y H:i') }}</small></td>
                                <td><span class="badge badge-info">{{ $trx->jenisSampah->nama ?? '-' }}</span></td>
                                <td>{{ $trx->bakSampah->nama ?? '-' }}</td>
                                <td>{{ number_format($trx->berat, 2) }} kg</td>
                                <td><span class="text-success">+{{ number_format($trx->poin_didapat) }}</span></td>
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
</div>
@endsection