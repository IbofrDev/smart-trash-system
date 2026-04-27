@extends('layouts.admin')

@section('title', 'Laporan & Peringkat Mahasiswa')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama & Umum */
    .custom-card { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03); background-color: #ffffff; overflow: hidden; }
    .card-header-custom { background-color: #f9fafb; padding: 1.25rem 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }

    /* Mini Stat Cards (Compact Gradient) */
    .mini-stat { border-radius: 14px; color: white; padding: 1.25rem 1rem; text-align: center; position: relative; overflow: hidden; transition: transform 0.3s; height: 100%; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .mini-stat:hover { transform: translateY(-5px); }
    .mini-stat::after { content: ''; position: absolute; right: -10px; top: -10px; width: 60px; height: 60px; border-radius: 50%; background: rgba(255,255,255,0.15); }
    
    .bg-grad-blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .bg-grad-green { background: linear-gradient(135deg, #10b981, #059669); }
    .bg-grad-amber { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .bg-grad-cyan { background: linear-gradient(135deg, #06b6d4, #0284c7); }
    .bg-grad-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
    .bg-grad-rose { background: linear-gradient(135deg, #f43f5e, #be123c); }

    .mini-stat h3 { font-weight: 800; font-size: 1.5rem; margin-bottom: 0.2rem; z-index: 1; position: relative; }
    .mini-stat p { margin: 0; font-size: 0.75rem; opacity: 0.9; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; z-index: 1; position: relative; }
    .mini-stat-icon { font-size: 1.5rem; margin-bottom: 0.5rem; opacity: 0.8; z-index: 1; position: relative; }

    /* Leaderboard Medals */
    .rank-medal { width: 36px; height: 36px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem; box-shadow: 0 4px 8px rgba(0,0,0,0.15); color: #fff; }
    .medal-1 { background: linear-gradient(135deg, #fef08a, #f59e0b); text-shadow: 1px 1px 2px rgba(180, 83, 9, 0.5); border: 2px solid #fef3c7; } /* Emas */
    .medal-2 { background: linear-gradient(135deg, #e2e8f0, #94a3b8); text-shadow: 1px 1px 2px rgba(71, 85, 105, 0.5); border: 2px solid #f8fafc; } /* Perak */
    .medal-3 { background: linear-gradient(135deg, #fed7aa, #b45309); text-shadow: 1px 1px 2px rgba(120, 53, 15, 0.5); border: 2px solid #ffedd5; } /* Perunggu */
    .rank-normal { background-color: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; font-weight: 700; }

    /* Styling Tabel Modern */
    .table-modern { margin-bottom: 0; width: 100%; }
    .table-modern th { background-color: #ffffff; color: #475569; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; padding: 1rem 1.2rem; border-bottom: 2px solid #e2e8f0; }
    .table-modern td { padding: 1rem 1.2rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; color: #334155; font-size: 0.9rem; }
    .table-modern tbody tr { transition: background-color 0.2s; }
    .table-modern tbody tr:hover { background-color: #f8fafc; }
    
    /* Top 3 Row Highlight */
    .table-modern tbody tr:nth-child(1) { background-color: rgba(245, 158, 11, 0.03); }
    .table-modern tbody tr:nth-child(2) { background-color: rgba(148, 163, 184, 0.03); }
    .table-modern tbody tr:nth-child(3) { background-color: rgba(180, 83, 9, 0.03); }

    /* Sort Form Styling */
    .sort-control { background-color: #f8fafc; border-radius: 12px; padding: 0.8rem 1.2rem; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; gap: 0.8rem; }
    .sort-select { border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem; font-weight: 600; color: #334155; padding: 0.4rem 2rem 0.4rem 0.8rem; background-color: #fff; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .sort-select:focus { border-color: #3b82f6; outline: none; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-dark mb-1">Leaderboard & Laporan Mahasiswa</h4>
        <p class="text-muted mb-0 small">Analisis performa dan peringkat mahasiswa dalam menjaga lingkungan.</p>
    </div>
    <a href="{{ route('admin.laporan.mahasiswa.pdf') }}" target="_blank" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: linear-gradient(135deg, #ef4444, #b91c1c); border: none;">
        <i class="bi bi-file-earmark-pdf-fill fs-5"></i> Cetak PDF Laporan
    </a>
</div>

<div class="row g-3 mb-4 animate-fade-up" style="animation-delay: 0.1s;">
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat bg-grad-blue">
            <i class="bi bi-people mini-stat-icon"></i>
            <h3>{{ number_format($summary['total_mahasiswa']) }}</h3>
            <p>Total User</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat bg-grad-green">
            <i class="bi bi-speedometer2 mini-stat-icon"></i>
            <h3>
                @if($summary['total_berat'] >= 1000)
                    {{ number_format($summary['total_berat'] / 1000, 1) }}<span style="font-size: 0.9rem; font-weight: normal;"> kg</span>
                @else
                    {{ number_format($summary['total_berat'], 0) }}<span style="font-size: 0.9rem; font-weight: normal;"> g</span>
                @endif
            </h3>
            <p>Total Berat</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat bg-grad-amber">
            <i class="bi bi-star-fill mini-stat-icon"></i>
            <h3>{{ number_format($summary['total_poin']) }}</h3>
            <p>Total Poin</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat bg-grad-cyan">
            <i class="bi bi-coin mini-stat-icon"></i>
            <h3>{{ number_format($summary['total_koin']) }}</h3>
            <p>Total Koin</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat bg-grad-purple">
            <i class="bi bi-recycle mini-stat-icon"></i>
            <h3>{{ number_format($summary['total_botol']) }}</h3>
            <p>Pcs Botol/Kaleng</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat bg-grad-rose">
            <i class="bi bi-receipt-cutoff mini-stat-icon"></i>
            <h3>{{ number_format($summary['total_transaksi']) }}</h3>
            <p>Total Transaksi</p>
        </div>
    </div>
</div>

<div class="card custom-card animate-fade-up" style="animation-delay: 0.2s;">
    
    <div class="card-header-custom">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-trophy-fill text-warning fs-4"></i>
            <h6 class="m-0 fw-bold text-dark">Klasemen Mahasiswa <span class="badge bg-secondary ms-2 rounded-pill">{{ $mahasiswas->count() }} Terdaftar</span></h6>
        </div>

        <div class="sort-control shadow-sm">
            <span class="text-muted small fw-bold"><i class="bi bi-filter-circle me-1"></i> Urutkan:</span>
            <form action="{{ route('admin.laporan.mahasiswa') }}" method="GET" class="d-flex gap-2 mb-0">
                <select name="sort" class="sort-select" onchange="this.form.submit()">
                    <option value="total_poin" {{ request('sort','total_poin') == 'total_poin' ? 'selected' : '' }}>🌟 Berdasarkan Poin</option>
                    <option value="total_koin" {{ request('sort') == 'total_koin' ? 'selected' : '' }}>🪙 Berdasarkan Koin</option>
                    <option value="total_berat" {{ request('sort') == 'total_berat' ? 'selected' : '' }}>⚖️ Berdasarkan Berat</option>
                    <option value="transaksi_count" {{ request('sort') == 'transaksi_count' ? 'selected' : '' }}>🔁 Jumlah Transaksi</option>
                </select>
                <select name="dir" class="sort-select" onchange="this.form.submit()">
                    <option value="desc" {{ request('dir','desc') == 'desc' ? 'selected' : '' }}>Tertinggi ⬇</option>
                    <option value="asc" {{ request('dir') == 'asc' ? 'selected' : '' }}>Terendah ⬆</option>
                </select>
            </form>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">Rank</th>
                        <th width="25%">Profil Pahlawan Lingkungan</th>
                        <th width="15%">Peringkat (Tier)</th>
                        <th width="15%" class="text-end">Total Poin</th>
                        <th width="15%" class="text-end">Saldo Koin</th>
                        <th width="15%" class="text-end">Kontribusi Berat</th>
                        <th width="10%" class="text-end pe-4">Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $index => $mhs)
                    <tr>
                        <td class="text-center">
                            @if($index == 0)
                                <div class="rank-medal medal-1" title="Peringkat 1">1</div>
                            @elseif($index == 1)
                                <div class="rank-medal medal-2" title="Peringkat 2">2</div>
                            @elseif($index == 2)
                                <div class="rank-medal medal-3" title="Peringkat 3">3</div>
                            @else
                                <div class="rank-medal rank-normal">{{ $index + 1 }}</div>
                            @endif
                        </td>
                        
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($mhs->name) }}&background=f1f5f9&color=475569&rounded=true&bold=true" alt="Avatar" width="38" height="38" class="rounded-circle border border-2">
                                <div>
                                    <div class="fw-bold text-dark">{{ $mhs->name }}</div>
                                    <small class="text-muted" style="font-family: monospace;">{{ $mhs->nim ?? 'NIM Kosong' }}</small>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            @if($mhs->level)
                                <span class="badge bg-indigo bg-opacity-10 px-3 py-2 rounded-pill fw-semibold border" style="color: #4338ca; border-color: #c7d2fe !important;">
                                    <i class="bi bi-shield-fill-check me-1"></i> {{ $mhs->level->nama_level }}
                                </span>
                            @else
                                <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill">Unranked</span>
                            @endif
                        </td>
                        
                        <td class="text-end">
                            <span class="text-success fw-bold fs-6">{{ number_format($mhs->total_poin) }}</span>
                        </td>
                        
                        <td class="text-end">
                            <span class="text-warning text-darken fw-bold fs-6">🪙 {{ number_format($mhs->total_koin_botol ?? 0) }}</span>
                        </td>
                        
                        <td class="text-end">
                            <div class="fw-bold text-dark">
                                @php $berat = $mhs->transaksi_sampah_sum_berat ?? 0; @endphp
                                @if($berat >= 1000)
                                    {{ number_format($berat / 1000, 2) }} kg
                                @else
                                    {{ number_format($berat, 0) }} g
                                @endif
                            </div>
                            <small class="text-muted border rounded px-1 mt-1 d-inline-block bg-light">
                                {{ number_format($mhs->transaksi_sampah_sum_jumlah_final ?? 0) }} item
                            </small>
                        </td>
                        
                        <td class="text-end pe-4">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fw-bold rounded px-2 py-1">
                                <i class="bi bi-arrow-repeat me-1"></i> {{ number_format($mhs->transaksi_sampah_count) }}x
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="border-0">
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-award fs-1 d-block mb-3 opacity-50"></i>
                                <span class="fw-medium">Belum ada data mahasiswa untuk diklasemenkan.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection