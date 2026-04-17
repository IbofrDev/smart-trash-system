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
                    <span>Total Koin</span>
                    <strong class="text-info">🪙 {{ number_format($mahasiswa->total_koin_botol ?? 0) }}</strong>
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

        <!-- Voucher Card -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-ticket-perforated me-2"></i>Voucher
                <span class="badge bg-secondary ms-1">{{ $mahasiswa->vouchers->count() }}</span>
            </div>
            <div class="card-body p-0">
                @forelse($mahasiswa->vouchers->take(5) as $voucher)
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                    <div>
                        <code class="small">{{ $voucher->kode_voucher }}</code>
                        <div class="small text-muted">Exp: {{ \Carbon\Carbon::parse($voucher->expired_at)->format('d M Y') }}</div>
                    </div>
                    @if($voucher->status === 'aktif')
                        <span class="badge bg-success">Aktif</span>
                    @elseif($voucher->status === 'terpakai')
                        <span class="badge bg-secondary">Terpakai</span>
                    @else
                        <span class="badge bg-danger">Expired</span>
                    @endif
                </div>
                @empty
                <div class="text-center text-muted py-3 small">Belum ada voucher</div>
                @endforelse
                @if($mahasiswa->vouchers->count() > 5)
                <div class="text-center py-2">
                    <small class="text-muted">+{{ $mahasiswa->vouchers->count() - 5 }} voucher lainnya</small>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Stats Row -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 bg-primary bg-opacity-10 text-center">
                    <div class="card-body py-3">
                        <h4 class="text-primary mb-1">{{ $mahasiswa->transaksiSampah->count() }}</h4>
                        <small class="text-muted">Transaksi</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-success bg-opacity-10 text-center">
                    <div class="card-body py-3">
                        @php $totalBerat = $mahasiswa->transaksiSampah->sum('berat'); @endphp
                        <h4 class="text-success mb-1">
                            @if($totalBerat >= 1000)
                                {{ number_format($totalBerat / 1000, 2) }} kg
                            @else
                                {{ number_format($totalBerat, 0) }} g
                            @endif
                        </h4>
                        <small class="text-muted">Total Berat</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-warning bg-opacity-10 text-center">
                    <div class="card-body py-3">
                        <h4 class="text-warning mb-1">{{ number_format($mahasiswa->transaksiSampah->sum('jumlah_final')) }}</h4>
                        <small class="text-muted">Total Botol</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-danger bg-opacity-10 text-center">
                    <div class="card-body py-3">
                        <h4 class="text-danger mb-1">{{ $mahasiswa->achievements->count() }}</h4>
                        <small class="text-muted">Achievement</small>
                    </div>
                </div>
            </div>
        </div>

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
                                <th>Bak Sampah</th>
                                <th>Jumlah</th>
                                <th>Berat</th>
                                <th>Poin</th>
                                <th>Koin</th>
                                <th>Validasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mahasiswa->transaksiSampah->sortByDesc('tanggal_transaksi')->take(10) as $trx)
                            <tr>
                                <td><small>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y H:i') }}</small></td>
                                <td>{{ $trx->bakSampah->nama ?? '-' }}</td>
                                <td>{{ $trx->jumlah_final ?? 0 }} pcs</td>
                                <td>
                                    @if(($trx->berat ?? 0) >= 1000)
                                        {{ number_format($trx->berat / 1000, 2) }} kg
                                    @else
                                        {{ number_format($trx->berat ?? 0, 0) }} g
                                    @endif
                                </td>
                                <td><span class="text-success">+{{ number_format($trx->poin_didapat ?? 0) }}</span></td>
                                <td><span class="text-info">🪙{{ number_format($trx->koin_didapat ?? 0) }}</span></td>
                                <td>
                                    @if(($trx->status_validasi ?? 'valid') === 'valid')
                                        <span class="badge bg-success">Valid</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Anomali</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">Belum ada transaksi</td>
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