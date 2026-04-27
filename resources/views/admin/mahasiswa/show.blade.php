@extends('layouts.admin')

@section('title', 'Detail Profil Mahasiswa')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama & Umum */
    .custom-card { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03); background-color: #ffffff; overflow: hidden; margin-bottom: 1.5rem; }
    .card-header-custom { background-color: #f9fafb; padding: 1.25rem 1.5rem; border-bottom: 1px solid #e5e7eb; font-weight: 700; color: #1f2937; display: flex; justify-content: space-between; align-items: center; }

    /* Profile Card Khusus */
    .profile-cover { height: 130px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); position: relative; }
    .profile-cover::after { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
    .profile-avatar-wrapper { text-align: center; margin-top: -65px; position: relative; z-index: 10; }
    .profile-avatar { width: 130px; height: 130px; border-radius: 50%; border: 5px solid #ffffff; box-shadow: 0 4px 15px rgba(0,0,0,0.1); background-color: #f8fafc; object-fit: cover; }
    
    .list-group-custom .list-group-item { border-color: #f3f4f6; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; background-color: transparent; }
    .list-group-custom .list-group-item span { color: #6b7280; font-size: 0.9rem; font-weight: 500; }
    .list-group-custom .list-group-item strong { color: #1f2937; font-size: 0.95rem; }

    /* Stat Cards (Floating Gradient) */
    .stat-card { border-radius: 16px; border: none; color: white; padding: 1.5rem; position: relative; overflow: hidden; transition: transform 0.3s; }
    .stat-card:hover { transform: translateY(-5px); }
    .stat-card::after { content: ''; position: absolute; right: -15px; bottom: -15px; width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,0.1); }
    
    .bg-gradient-blue { background: linear-gradient(135deg, #3b82f6, #2563eb); box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2); }
    .bg-gradient-green { background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2); }
    .bg-gradient-amber { background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 10px 20px rgba(217, 119, 6, 0.2); }
    .bg-gradient-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); box-shadow: 0 10px 20px rgba(139, 92, 246, 0.2); }

    .stat-card h3 { font-weight: 800; font-size: 2rem; margin-bottom: 0.2rem; }
    .stat-card p { margin: 0; font-size: 0.9rem; opacity: 0.9; font-weight: 500; }
    .stat-icon { position: absolute; top: 1.5rem; right: 1.5rem; font-size: 2rem; opacity: 0.5; }

    /* Achievement Item */
    .achieve-item { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem; display: flex; align-items: center; gap: 1rem; transition: all 0.2s; }
    .achieve-item:hover { background-color: #ffffff; border-color: #cbd5e1; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
    .achieve-icon { width: 45px; height: 45px; border-radius: 10px; background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }

    /* Styling Tabel Modern */
    .table-modern { margin-bottom: 0; width: 100%; }
    .table-modern th { background-color: #f9fafb; color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
    .table-modern td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f3f4f6; color: #4b5563; font-size: 0.9rem; }
    .table-modern tbody tr { transition: background-color 0.2s; }
    .table-modern tbody tr:hover { background-color: #f8fafc; }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-dark mb-1">Profil Player / Mahasiswa</h4>
        <p class="text-muted mb-0 small">Detail statistik, riwayat setoran, dan pencapaian pengguna.</p>
    </div>
    <div>
        <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-light rounded-pill px-4 fw-medium border shadow-sm me-2">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
            <i class="bi bi-pencil-square me-1"></i> Edit Data
        </a>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        
        <div class="custom-card mb-4">
            <div class="profile-cover">
                </div>
            <div class="profile-avatar-wrapper">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($mahasiswa->name) }}&background=ffffff&color=1d4ed8&size=256&bold=true" alt="Avatar" class="profile-avatar">
            </div>
            
            <div class="text-center px-4 pb-3 pt-2">
                <h4 class="fw-bold text-dark mb-1">{{ $mahasiswa->name }}</h4>
                <p class="text-muted mb-3 small"><i class="bi bi-envelope me-1"></i>{{ $mahasiswa->email }}</p>
                
                @if($mahasiswa->level)
                    <span class="badge bg-indigo bg-opacity-10 text-primary border border-primary border-opacity-25 px-4 py-2 rounded-pill fw-bold fs-6">
                        <i class="bi bi-shield-fill-check me-1 text-warning"></i> {{ $mahasiswa->level->nama_level }}
                    </span>
                @else
                    <span class="badge bg-light text-secondary border px-4 py-2 rounded-pill fw-bold fs-6">Unranked</span>
                @endif
            </div>

            <ul class="list-group list-group-flush list-group-custom">
                <li class="list-group-item">
                    <span><i class="bi bi-person-badge me-2"></i>NIM</span>
                    <strong>{{ $mahasiswa->nim ?? 'Belum Diatur' }}</strong>
                </li>
                <li class="list-group-item">
                    <span><i class="bi bi-book me-2"></i>Program Studi</span>
                    <strong>{{ $mahasiswa->prodi ?? '-' }}</strong>
                </li>
                <li class="list-group-item">
                    <span><i class="bi bi-upc-scan me-2"></i>RFID Card</span>
                    @if($mahasiswa->rfid_uid)
                        <code class="bg-light px-2 py-1 rounded text-primary fw-bold">{{ $mahasiswa->rfid_uid }}</code>
                    @else
                        <span class="text-muted fst-italic">Belum Tautkan</span>
                    @endif
                </li>
                <li class="list-group-item bg-success bg-opacity-10 border-success border-opacity-25">
                    <span class="text-success"><i class="bi bi-star-fill me-2"></i>Total Poin</span>
                    <strong class="text-success fs-5">{{ number_format($mahasiswa->total_poin) }}</strong>
                </li>
                <li class="list-group-item bg-warning bg-opacity-10 border-warning border-opacity-25">
                    <span class="text-warning text-darken"><i class="bi bi-coin me-2"></i>Koin Voucher</span>
                    <strong class="text-warning text-darken fs-5">{{ number_format($mahasiswa->total_koin_botol ?? 0) }}</strong>
                </li>
                <li class="list-group-item">
                    <span><i class="bi bi-bar-chart-line-fill me-2"></i>Ranking Aktif</span>
                    <strong>
                        @if($mahasiswa->leaderboard)
                            <span class="badge bg-danger rounded-pill px-3">Rank #{{ $mahasiswa->leaderboard->ranking_mingguan ?? '-' }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </strong>
                </li>
                <li class="list-group-item">
                    <span><i class="bi bi-calendar-check me-2"></i>Bergabung</span>
                    <strong>{{ $mahasiswa->created_at->format('d M Y') }}</strong>
                </li>
            </ul>
        </div>

        <div class="custom-card">
            <div class="card-header-custom">
                <span><i class="bi bi-ticket-perforated-fill text-warning me-2"></i>Dompet Voucher</span>
                <span class="badge bg-dark rounded-pill">{{ $mahasiswa->vouchers->count() }} Item</span>
            </div>
            <div class="card-body p-0">
                @forelse($mahasiswa->vouchers->take(5) as $voucher)
                <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded p-2 text-secondary"><i class="bi bi-qr-code"></i></div>
                        <div>
                            <code class="fw-bold fs-6 text-dark d-block mb-1">{{ $voucher->kode_voucher }}</code>
                            <div class="small text-muted"><i class="bi bi-clock me-1"></i>Exp: {{ \Carbon\Carbon::parse($voucher->expired_at)->format('d M Y') }}</div>
                        </div>
                    </div>
                    <div>
                        @if($voucher->status === 'aktif')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1 rounded-pill">Aktif</span>
                        @elseif($voucher->status === 'terpakai')
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-1 rounded-pill">Terpakai</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1 rounded-pill">Expired</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="bi bi-wallet2 text-muted fs-1 d-block mb-2 opacity-50"></i>
                    <span class="text-muted small">Belum ada voucher yang ditukar</span>
                </div>
                @endforelse
                
                @if($mahasiswa->vouchers->count() > 5)
                <div class="text-center py-3 bg-light">
                    <span class="text-primary fw-medium small">Lihat {{ $mahasiswa->vouchers->count() - 5 }} voucher lainnya...</span>
                </div>
                @endif
            </div>
        </div>

    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="stat-card bg-gradient-blue">
                    <i class="bi bi-arrow-left-right stat-icon"></i>
                    <h3>{{ $mahasiswa->transaksiSampah->count() }}</h3>
                    <p>Transaksi</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card bg-gradient-green">
                    <i class="bi bi-speedometer2 stat-icon"></i>
                    @php $totalBerat = $mahasiswa->transaksiSampah->sum('berat'); @endphp
                    <h3>
                        @if($totalBerat >= 1000)
                            {{ number_format($totalBerat / 1000, 1) }}<span style="font-size: 1rem; font-weight: normal;"> kg</span>
                        @else
                            {{ number_format($totalBerat, 0) }}<span style="font-size: 1rem; font-weight: normal;"> g</span>
                        @endif
                    </h3>
                    <p>Total Berat</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card bg-gradient-amber">
                    <i class="bi bi-recycle stat-icon"></i>
                    <h3>{{ number_format($mahasiswa->transaksiSampah->sum('jumlah_final')) }}</h3>
                    <p>Total Botol</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card bg-gradient-purple">
                    <i class="bi bi-stars stat-icon"></i>
                    <h3>{{ $mahasiswa->achievements->count() }}</h3>
                    <p>Gelar / Misi</p>
                </div>
            </div>
        </div>

        <div class="custom-card mb-4">
            <div class="card-header-custom">
                <span><i class="bi bi-trophy-fill text-primary me-2"></i>Pencapaian Diraih ({{ $mahasiswa->achievements->count() }})</span>
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
                                    <div class="fw-bold text-dark">{{ $ach->nama }}</div>
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
                        <i class="bi bi-award text-muted fs-1 d-block mb-2 opacity-25"></i>
                        <p class="text-muted mb-0">Belum ada achievement yang berhasil diselesaikan.</p>
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
                    <table class="table table-modern">
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
                                    <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d M Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('H:i') }} WITA</small>
                                </td>
                                <td>
                                    <span class="text-dark fw-medium"><i class="bi bi-hdd-network text-secondary me-1"></i>{{ $trx->bakSampah->nama ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border me-1">{{ $trx->jumlah_final ?? 0 }} pcs</span>
                                    <span class="badge bg-light text-dark border">
                                        @if(($trx->berat ?? 0) >= 1000)
                                            {{ number_format($trx->berat / 1000, 2) }} kg
                                        @else
                                            {{ number_format($trx->berat ?? 0, 0) }} g
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="text-success fw-bold small"><i class="bi bi-star-fill me-1 text-warning"></i>+{{ number_format($trx->poin_didapat ?? 0) }} Poin</div>
                                    <div class="text-warning text-darken fw-bold small"><i class="bi bi-coin me-1"></i>+{{ number_format($trx->koin_didapat ?? 0) }} Koin</div>
                                </td>
                                <td class="text-center">
                                    @if(($trx->status_validasi ?? 'valid') === 'valid')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1">Valid</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-1">Anomali</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
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