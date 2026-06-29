@extends('layouts.admin')

@section('title', 'Detail Profil Mahasiswa')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Layout Standar Berkelas */
    .custom-card { border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; margin-bottom: 1.5rem; }
    .card-header-custom { background-color: #ffffff; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; font-weight: 700; color: #0f172a; display: flex; justify-content: space-between; align-items: center; }

    /* Profile Side Layout - Clean White Style */
    .profile-cover-clean { height: 80px; background-color: #f8fafc; border-bottom: 1px solid #f1f5f9; position: relative; }
    .profile-avatar-wrapper { text-align: center; margin-top: -50px; position: relative; z-index: 10; }
    .profile-avatar { width: 100px; height: 100px; border-radius: 12px; border: 4px solid #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background-color: #ffffff; object-fit: cover; }
    
    .list-group-custom .list-group-item { border-color: #f8fafc; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; background-color: transparent; }
    .list-group-custom .list-group-item span { color: #64748b; font-size: 0.85rem; font-weight: 600; }
    .list-group-custom .list-group-item strong { color: #1e293b; font-size: 0.95rem; font-weight: 700; }

    /* Stat Boxy Panel (Pengganti Floating Gradient) */
    .stat-card-boxy { border-radius: 12px; border: 1px solid #e2e8f0; background-color: #ffffff; padding: 1.5rem; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01); }
    .stat-card-boxy h3 { font-weight: 800; font-size: 1.75rem; color: #0f172a; margin-bottom: 0.2rem; }
    .stat-card-boxy p { margin: 0; font-size: 0.8rem; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
    .stat-icon { position: absolute; top: 1.5rem; right: 1.5rem; font-size: 1.5rem; color: #cbd5e1; }

    /* Achievement Item */
    .achieve-item { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; display: flex; align-items: center; gap: 1rem; }
    .achieve-icon { width: 42px; height: 42px; border-radius: 6px; background-color: #ecfdf5; color: #047857; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }

    /* Styling Tabel Clean */
    .table-clean { margin-bottom: 0; width: 100%; }
    .table-clean th { background-color: #ffffff; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 1rem 1.5rem; border-bottom: 2px solid #f1f5f9; }
    .table-clean td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; color: #334155; font-size: 0.9rem; }
    .table-clean tbody tr { transition: background-color 0.2s; }
    .table-clean tbody tr:hover { background-color: #f8fafc; }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Profil Player / Mahasiswa</h4>
        <p class="text-muted mb-0 small">Detail statistik, riwayat setoran, dan pencapaian pengguna.</p>
    </div>
    <div>
        <a href="{{ route($routePrefix . '.mahasiswa.index') }}" class="btn bg-white border rounded-3 px-4 fw-bold me-2" style="color: #64748b;">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <a href="{{ route($routePrefix . '.mahasiswa.edit', $mahasiswa) }}" class="btn btn-success rounded-3 px-4 fw-bold shadow-sm" style="background: #10b981; border: none;">
            <i class="bi bi-pencil-square me-1"></i> Edit Data
        </a>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        
        <div class="custom-card mb-4">
            <div class="profile-cover-clean"></div>
            <div class="profile-avatar-wrapper">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($mahasiswa->name) }}&background=ecfdf5&color=047857&size=256&bold=true" alt="Avatar" class="profile-avatar">
            </div>
            
            <div class="text-center px-4 pb-3 pt-3">
                <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">{{ $mahasiswa->name }}</h4>
                <p class="text-muted mb-3 small"><i class="bi bi-envelope me-1"></i>{{ $mahasiswa->email }}</p>
                
                @if($mahasiswa->level)
                    <span class="badge px-3 py-2 rounded-2 fw-bold" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;">
                        <i class="bi bi-shield-fill-check me-1 text-warning"></i> {{ $mahasiswa->level->nama_level }}
                    </span>
                @else
                    <span class="badge border px-3 py-2 rounded-2 fw-bold text-secondary bg-light">Unranked</span>
                @endif
            </div>

            <ul class="list-group list-group-flush list-group-custom border-top" style="border-color: #f1f5f9 !important;">
                <li class="list-group-item">
                    <span><i class="bi bi-person-badge me-2 text-success"></i>NIM</span>
                    <strong>{{ $mahasiswa->nim ?? 'Belum Diatur' }}</strong>
                </li>
                <li class="list-group-item">
                    <span><i class="bi bi-book me-2 text-success"></i>Program Studi</span>
                    <strong>{{ $mahasiswa->prodi ?? '-' }}</strong>
                </li>
                <li class="list-group-item">
                    <span><i class="bi bi-upc-scan me-2 text-success"></i>RFID Card</span>
                    @if($mahasiswa->rfid_uid)
                        <code class="px-2 py-1 rounded text-success fw-bold" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">{{ $mahasiswa->rfid_uid }}</code>
                    @else
                        <span class="text-muted fst-italic">Belum Tautkan</span>
                    @endif
                </li>
                <li class="list-group-item" style="background-color: #f8fafc;">
                    <span><i class="bi bi-star-fill me-2 text-success"></i>Total Poin</span>
                    <strong class="text-success fs-5">{{ number_format($mahasiswa->total_poin) }}</strong>
                </li>
                <li class="list-group-item" style="background-color: #f8fafc;">
                    <span><i class="bi bi-coin me-2 text-warning"></i>Koin Voucher</span>
                    <strong class="text-warning fs-5" style="color: #d97706 !important;">{{ number_format($mahasiswa->total_koin_botol ?? 0) }}</strong>
                </li>
                <li class="list-group-item">
                    <span><i class="bi bi-bar-chart-line-fill me-2 text-success"></i>Ranking Aktif</span>
                    <strong>
                        @if($mahasiswa->leaderboard)
                            <span class="badge px-3 py-1 rounded-2" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;">Rank #{{ $mahasiswa->leaderboard->ranking_mingguan ?? '-' }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </strong>
                </li>
                <li class="list-group-item">
                    <span><i class="bi bi-calendar-check me-2 text-success"></i>Bergabung</span>
                    <strong>{{ $mahasiswa->created_at->format('d M Y') }}</strong>
                </li>
            </ul>
        </div>

        <div class="custom-card">
            <div class="card-header-custom">
                <span><i class="bi bi-ticket-perforated-fill text-success me-2"></i>Dompet Voucher</span>
                <span class="badge bg-light text-dark border px-2 py-1 rounded-2">{{ $mahasiswa->vouchers->count() }} Item</span>
            </div>
            <div class="card-body p-0">
                @forelse($mahasiswa->vouchers->take(5) as $voucher)
                <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom" style="border-color: #f8fafc !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded p-2 text-muted" style="border-radius: 6px !important;"><i class="bi bi-qr-code"></i></div>
                        <div>
                            <code class="fw-bold fs-6 text-dark d-block mb-1">{{ $voucher->kode_voucher }}</code>
                            <div class="small text-muted" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i>Exp: {{ \Carbon\Carbon::parse($voucher->expired_at)->format('d M Y') }}</div>
                        </div>
                    </div>
                    <div>
                        @if($voucher->status === 'aktif')
                            <span class="badge px-2 py-1 rounded-2" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;">Aktif</span>
                        @elseif($voucher->status === 'terpakai')
                            <span class="badge px-2 py-1 rounded-2" style="background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;">Terpakai</span>
                        @else
                            <span class="badge px-2 py-1 rounded-2" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;">Expired</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="bi bi-wallet2 text-muted fs-2 d-block mb-2 opacity-50"></i>
                    <span class="text-muted small">Belum ada voucher yang ditukar</span>
                </div>
                @endforelse
                
                @if($mahasiswa->vouchers->count() > 5)
                <div class="text-center py-3 border-top bg-light">
                    <span class="fw-bold small" style="color: #059669;">Lihat {{ $mahasiswa->vouchers->count() - 5 }} voucher lainnya...</span>
                </div>
                @endif
            </div>
        </div>

    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="stat-card-boxy">
                    <i class="bi bi-arrow-left-right stat-icon"></i>
                    <h3>{{ $mahasiswa->transaksiSampah->count() }}</h3>
                    <p>Transaksi</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card-boxy">
                    <i class="bi bi-speedometer2 stat-icon"></i>
                    @php $totalBerat = $mahasiswa->transaksiSampah->sum('berat'); @endphp
                    <h3>
                        @if($totalBerat >= 1000)
                            {{ number_format($totalBerat / 1000, 1) }}<span style="font-size: 1rem; font-weight: normal; color: #64748b;"> kg</span>
                        @else
                            {{ number_format($totalBerat, 0) }}<span style="font-size: 1rem; font-weight: normal; color: #64748b;"> g</span>
                        @endif
                    </h3>
                    <p>Total Berat</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card-boxy">
                    <i class="bi bi-recycle stat-icon"></i>
                    <h3>{{ number_format($mahasiswa->transaksiSampah->sum('jumlah_final')) }}</h3>
                    <p>Total Botol</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card-boxy">
                    <i class="bi bi-stars stat-icon"></i>
                    <h3>{{ $mahasiswa->achievements->count() }}</h3>
                    <p>Gelar / Misi</p>
                </div>
            </div>
        </div>

        <div class="custom-card mb-4">
            <div class="card-header-custom">
                <span><i class="bi bi-trophy-fill text-success me-2"></i>Pencapaian Diraih ({{ $mahasiswa->achievements->count() }})</span>
            </div>
            <div class="card-body p-4">
                @if($mahasiswa->achievements->count() > 0)
                    <div class="row g-3">
                        @foreach($mahasiswa->achievements as $ach)
                        <div class="col-md-6">
                            <div class="achieve-item">
                                <div class="achieve-icon">
                                    <i class="{{ $ach->icon ?? 'bi bi-trophy' }}"></i>
                                </div>
                                <div>
                                    <div class="fw-bold" style="color: #0f172a;">{{ $ach->nama }}</div>
                                    <small class="text-muted d-block" style="font-size: 0.75rem;">
                                        <i class="bi bi-calendar-check me-1"></i>Unlocked: {{ \Carbon\Carbon::parse($ach->pivot->unlocked_at)->format('d M Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-award text-light mb-2" style="font-size: 2.5rem; display: block; color: #e2e8f0 !important;"></i>
                        <p class="text-muted mb-0 small">Belum ada achievement yang berhasil diselesaikan.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="custom-card">
            <div class="card-header-custom">
                <span><i class="bi bi-clock-history text-success me-2"></i>Riwayat Setoran Terbaru</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-clean">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Lokasi Drop</th>
                                <th>Volume</th>
                                <th>Rewards</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mahasiswa->transaksiSampah->sortByDesc('tanggal_transaksi')->take(10) as $trx)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark" style="color: #0f172a !important;">{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d M Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('H:i') }} WITA</small>
                                </td>
                                <td>
                                    <span class="fw-medium" style="color: #334155;"><i class="bi bi-hdd-network text-muted me-1"></i>{{ $trx->bakSampah->nama ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="badge border me-1" style="background: #f8fafc; color: #0f172a; border-radius: 4px;">{{ $trx->jumlah_final ?? 0 }} pcs</span>
                                    <span class="badge border" style="background: #f8fafc; color: #0f172a; border-radius: 4px;">
                                        @if(($trx->berat ?? 0) >= 1000)
                                            {{ number_format($trx->berat / 1000, 2) }} kg
                                        @else
                                            {{ number_format($trx->berat ?? 0, 0) }} g
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold small" style="color: #059669;"><i class="bi bi-star-fill me-1 text-warning"></i>+{{ number_format($trx->poin_didapat ?? 0) }} Pts</div>
                                    <div class="fw-bold small" style="color: #d97706;"><i class="bi bi-coin me-1 text-warning"></i>+{{ number_format($trx->koin_didapat ?? 0) }} Koin</div>
                                </td>
                                <td class="text-center">
                                    @if(($trx->status_validasi ?? 'valid') === 'valid')
                                        <span class="badge px-3 py-1 rounded-2" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;">Valid</span>
                                    @else
                                        <span class="badge px-3 py-1 rounded-2" style="background: #fff1f2; color: #dc2626; border: 1px solid #fecaca;">Anomali</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5 border-0">
                                    <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50" style="color: #e2e8f0 !important;"></i>
                                    Mahasiswa ini belum pernah melakukan setoran sampah.
                                </td>
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