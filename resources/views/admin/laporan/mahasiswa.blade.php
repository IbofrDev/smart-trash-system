@extends('layouts.admin')

@section('title', 'Laporan & Peringkat Mahasiswa')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama & Umum */
    .custom-card { border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }
    .card-header-custom { background-color: #ffffff; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }

    /* Mini Stat Cards - Clean White Boxy */
    .mini-stat { border-radius: 12px; background-color: #ffffff; border: 1px solid #e2e8f0; padding: 1.25rem 1rem; text-align: left; position: relative; overflow: hidden; transition: transform 0.2s; height: 100%; box-shadow: 0 4px 12px rgba(0,0,0,0.01); display: flex; flex-direction: column; justify-content: center; }
    .mini-stat:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.03); }
    .mini-stat h3 { font-weight: 800; font-size: 1.5rem; margin-bottom: 0.2rem; z-index: 1; color: #0f172a; position: relative; }
    .mini-stat p { margin: 0; font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; z-index: 1; position: relative; }
    .mini-stat-icon { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); font-size: 2rem; color: #f1f5f9; z-index: 0; }

    /* Leaderboard Medals - Pastel Clean */
    .rank-medal { width: 32px; height: 32px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem; }
    .medal-1 { background-color: #fef3c7; color: #d97706; border: 1px solid #fde68a; } /* Emas */
    .medal-2 { background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; } /* Perak */
    .medal-3 { background-color: #ffedd5; color: #c2410c; border: 1px solid #fed7aa; } /* Perunggu */
    .rank-normal { background-color: #ffffff; color: #64748b; border: 1px solid #e2e8f0; font-weight: 700; }

    /* Styling Tabel Clean */
    .table-modern { margin-bottom: 0; width: 100%; }
    .table-modern th { background-color: #ffffff; color: #64748b; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 1rem 1.2rem; border-bottom: 2px solid #f1f5f9; }
    .table-modern td { padding: 1rem 1.2rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; color: #334155; font-size: 0.9rem; }
    .table-modern tbody tr { transition: background-color 0.2s; }
    .table-modern tbody tr:hover { background-color: #f8fafc; }
    
    /* Top 3 Row Highlight - Sangat Tipis */
    .table-modern tbody tr:nth-child(1) { background-color: #fffbeb; }
    .table-modern tbody tr:nth-child(2) { background-color: #f8fafc; }
    .table-modern tbody tr:nth-child(3) { background-color: #fff7ed; }

    /* Sort Form Styling */
    .sort-control { background-color: #ffffff; border-radius: 8px; padding: 0.6rem 1rem; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; gap: 0.8rem; box-shadow: 0 1px 2px rgba(0,0,0,0.01); }
    .sort-select { border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.85rem; font-weight: 600; color: #334155; padding: 0.4rem 2rem 0.4rem 0.8rem; background-color: #f8fafc; cursor: pointer; transition: all 0.2s; }
    .sort-select:focus { border-color: #10b981; outline: none; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); background-color: #ffffff; }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Leaderboard & Laporan Mahasiswa</h4>
        <p class="text-muted mb-0 small">Analisis performa dan peringkat mahasiswa dalam menjaga lingkungan.</p>
    </div>
    <a href="{{ route($routePrefix . '.laporan.mahasiswa.pdf') }}" target="_blank" class="btn rounded-3 px-4 fw-bold d-flex align-items-center gap-2" style="background-color: #fff1f2; color: #dc2626; border: 1px solid #fecaca;">
        <i class="bi bi-file-earmark-pdf-fill fs-5"></i> Cetak PDF Laporan
    </a>
</div>

<div class="row g-3 mb-4 animate-fade-up" style="animation-delay: 0.1s;">
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat">
            <i class="bi bi-people mini-stat-icon"></i>
            <h3>{{ number_format($summary['total_mahasiswa']) }}</h3>
            <p>Total User</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat">
            <i class="bi bi-speedometer2 mini-stat-icon"></i>
            <h3 style="color: #059669;">
                @if($summary['total_berat'] >= 1000)
                    {{ number_format($summary['total_berat'] / 1000, 1) }}<span style="font-size: 0.9rem; font-weight: normal; color: #64748b;"> kg</span>
                @else
                    {{ number_format($summary['total_berat'], 0) }}<span style="font-size: 0.9rem; font-weight: normal; color: #64748b;"> g</span>
                @endif
            </h3>
            <p>Total Berat</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat">
            <i class="bi bi-star-fill mini-stat-icon"></i>
            <h3>{{ number_format($summary['total_poin']) }}</h3>
            <p>Total Poin</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat">
            <i class="bi bi-coin mini-stat-icon"></i>
            <h3 style="color: #d97706;">{{ number_format($summary['total_koin']) }}</h3>
            <p>Total Koin</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat">
            <i class="bi bi-recycle mini-stat-icon"></i>
            <h3>{{ number_format($summary['total_botol']) }}</h3>
            <p>Pcs Botol/Kaleng</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="mini-stat">
            <i class="bi bi-receipt-cutoff mini-stat-icon"></i>
            <h3>{{ number_format($summary['total_transaksi']) }}</h3>
            <p>Total Transaksi</p>
        </div>
    </div>
</div>

<div class="custom-card animate-fade-up" style="animation-delay: 0.2s;">
    
    <div class="card-header-custom">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-trophy-fill text-success fs-4"></i>
            <h6 class="m-0 fw-bold" style="color: #0f172a;">Klasemen Mahasiswa <span class="badge" style="background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; margin-left: 8px;">{{ $mahasiswas->count() }} Terdaftar</span></h6>
        </div>

        <div class="sort-control">
            <span class="text-muted small fw-bold"><i class="bi bi-filter-circle me-1"></i> Urutkan:</span>
            <form action="{{ route($routePrefix . '.laporan.mahasiswa') }}" method="GET" class="d-flex gap-2 mb-0">
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
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($mhs->name) }}&background=f1f5f9&color=0f172a&rounded=true&bold=true" alt="Avatar" width="38" height="38" class="border border-2" style="border-radius: 8px;">
                                <div>
                                    <div class="fw-bold" style="color: #0f172a;">{{ $mhs->name }}</div>
                                    <small class="text-muted" style="font-family: monospace;">{{ $mhs->nim ?? 'NIM Kosong' }}</small>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            @if($mhs->level)
                                <span class="badge px-3 py-1 border rounded-2" style="background-color: #ffffff; color: #0f172a; border-color: #e2e8f0;">
                                    <i class="bi bi-shield-fill-check me-1 text-success"></i> {{ $mhs->level->nama_level }}
                                </span>
                            @else
                                <span class="badge bg-light text-secondary border px-3 py-1 rounded-2">Unranked</span>
                            @endif
                        </td>
                        
                        <td class="text-end">
                            <span class="fw-bold fs-6" style="color: #059669;">{{ number_format($mhs->total_poin) }}</span>
                        </td>
                        
                        <td class="text-end">
                            <span class="fw-bold fs-6" style="color: #d97706;">🪙 {{ number_format($mhs->total_koin_botol ?? 0) }}</span>
                        </td>
                        
                        <td class="text-end">
                            <div class="fw-bold" style="color: #0f172a;">
                                @php $berat = $mhs->transaksi_sampah_sum_berat ?? 0; @endphp
                                @if($berat >= 1000)
                                    {{ number_format($berat / 1000, 2) }} kg
                                @else
                                    {{ number_format($berat, 0) }} g
                                @endif
                            </div>
                            <small class="text-muted border px-1 mt-1 d-inline-block" style="background-color: #ffffff; border-radius: 4px;">
                                {{ number_format($mhs->transaksi_sampah_sum_jumlah_final ?? 0) }} item
                            </small>
                        </td>
                        
                        <td class="text-end pe-4">
                            <div class="d-inline-flex align-items-center justify-content-center fw-bold px-2 py-1" style="background-color: #f1f5f9; color: #0f172a; border-radius: 6px; border: 1px solid #e2e8f0;">
                                <i class="bi bi-arrow-repeat me-1 text-muted"></i> {{ number_format($mhs->transaksi_sampah_count) }}x
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-award fs-1 d-block mb-3 opacity-25" style="color: #e2e8f0;"></i>
                            <span class="fw-medium text-muted">Belum ada data mahasiswa untuk diklasemenkan.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection