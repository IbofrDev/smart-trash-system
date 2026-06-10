@extends('layouts.admin')

@section('title', 'Data Transaksi')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama & Umum */
    .custom-card { border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }
    
    /* Stat Cards - Minimalist Boxy */
    .stat-card-boxy { border-radius: 12px; border: 1px solid #e2e8f0; background-color: #ffffff; padding: 1.5rem; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01); height: 100%; display: flex; flex-direction: column; justify-content: center; transition: transform 0.2s; }
    .stat-card-boxy:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0, 0, 0, 0.03); }
    .stat-card-boxy h3 { font-weight: 800; font-size: 2rem; color: #0f172a; margin-bottom: 0.2rem; z-index: 1; }
    .stat-card-boxy p { margin: 0; font-size: 0.85rem; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; z-index: 1; }
    .stat-icon { position: absolute; top: 50%; right: 1.5rem; transform: translateY(-50%); font-size: 2.5rem; color: #f1f5f9; z-index: 0; }

    /* Filter Area */
    .filter-wrapper { background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; position: relative; z-index: 50; box-shadow: 0 4px 12px rgba(0,0,0,0.01); }
    .filter-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; display: block; }
    
    /* Input Date Normal */
    .filter-input { border-radius: 8px; border: 1px solid #e2e8f0; padding: 0.6rem 1rem; font-size: 0.9rem; color: #0f172a; background-color: #f8fafc; transition: all 0.2s; }
    .filter-input:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }

    /* --- Custom Dropdown Filter Modern --- */
    .modern-dropdown-btn { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.6rem 2.5rem 0.6rem 1rem; color: #0f172a; font-size: 0.9rem; font-weight: 500; width: 100%; text-align: left; transition: all 0.2s; position: relative; }
    .modern-dropdown-btn:hover, .modern-dropdown-btn:focus, .modern-dropdown-btn.show { background-color: #ffffff; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); outline: none; }
    .modern-dropdown-btn::after { display: none; }
    .modern-dropdown-btn::before { content: '\F282'; font-family: 'bootstrap-icons'; font-size: 0.85rem; color: #94a3b8; position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); transition: transform 0.3s; }
    .modern-dropdown-btn.show::before { transform: translateY(-50%) rotate(180deg); color: #10b981; }
    
    .custom-dropdown-menu { border: 1px solid #f1f5f9; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-radius: 10px; padding: 0.5rem; margin-top: 0.5rem !important; width: 100%; max-height: 250px; overflow-y: auto; z-index: 1050; }
    .custom-dropdown-item { border-radius: 6px; padding: 0.5rem 1rem; font-size: 0.9rem; color: #475569; font-weight: 500; transition: all 0.2s; }
    .custom-dropdown-item:hover, .custom-dropdown-item.active-filter { background-color: #ecfdf5; color: #047857; }

    /* Styling Tabel Clean */
    .table-clean { margin-bottom: 0; width: 100%; }
    .table-clean th { background-color: #ffffff; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 1rem 1.5rem; border-bottom: 2px solid #f1f5f9; }
    .table-clean td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; color: #334155; font-size: 0.9rem; }
    .table-clean tbody tr { transition: background-color 0.2s; }
    .table-clean tbody tr:hover { background-color: #f8fafc; }
    
    /* Tombol Aksi */
    .action-btns { display: flex; gap: 0.4rem; justify-content: flex-end; }
    .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid #e2e8f0; background-color: #f8fafc; color: #64748b; transition: all 0.2s; text-decoration: none; cursor: pointer; }
    .btn-icon:hover { transform: translateY(-2px); }
    .btn-detail:hover { background-color: #0f172a; color: #ffffff; border-color: #0f172a; }

    /* Empty State */
    .empty-state { padding: 4rem 1rem; text-align: center; color: #94a3b8; }
    .empty-state i { font-size: 4rem; color: #e2e8f0; display: block; margin-bottom: 1rem; }
    .pagination-wrapper { padding: 1.25rem 1.5rem; background-color: #ffffff; border-top: 1px solid #f1f5f9; }

    /* Avatar Kotak Halus */
    .avatar-square-soft { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; border: 1px solid #e2e8f0; }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Riwayat Transaksi</h4>
        <p class="text-muted mb-0 small">Pantau aktivitas setoran sampah, validasi mesin, dan distribusi reward.</p>
    </div>
</div>

<div class="row g-4 mb-4 animate-fade-up" style="animation-delay: 0.1s;">
    <div class="col-md-3 col-6">
        <div class="stat-card-boxy">
            <i class="bi bi-receipt-cutoff stat-icon"></i>
            <h3>{{ number_format($summary['total_transaksi']) }}</h3>
            <p>Total Transaksi</p>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card-boxy">
            <i class="bi bi-speedometer2 stat-icon"></i>
            <h3 style="color: #059669;">
                @if(($summary['total_berat'] ?? 0) >= 1000)
                    {{ number_format($summary['total_berat'] / 1000, 1) }}<span style="font-size: 1rem; font-weight: 600; color: #64748b;"> kg</span>
                @else
                    {{ number_format($summary['total_berat'], 0) }}<span style="font-size: 1rem; font-weight: 600; color: #64748b;"> g</span>
                @endif
            </h3>
            <p>Volume Berat</p>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card-boxy">
            <i class="bi bi-star-fill stat-icon"></i>
            <h3>{{ number_format($summary['total_poin']) }}</h3>
            <p>Poin Dibagikan</p>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card-boxy">
            <i class="bi bi-coin stat-icon"></i>
            <h3>{{ number_format($summary['total_koin'] ?? 0) }}</h3>
            <p>Koin Voucher</p>
        </div>
    </div>
</div>

<div class="filter-wrapper animate-fade-up" style="animation-delay: 0.2s;">
    <form action="{{ route('admin.transaksi.index') }}" method="GET" class="row g-3 align-items-end">
        
        <div class="col-lg-2 col-md-4">
            <label class="filter-label"><i class="bi bi-calendar-event me-1 text-success"></i> Mulai Tgl</label>
            <input type="date" name="tanggal_dari" class="form-control filter-input w-100" value="{{ request('tanggal_dari') }}">
        </div>
        
        <div class="col-lg-2 col-md-4">
            <label class="filter-label"><i class="bi bi-calendar-check me-1 text-success"></i> Sampai Tgl</label>
            <input type="date" name="tanggal_sampai" class="form-control filter-input w-100" value="{{ request('tanggal_sampai') }}">
        </div>
        
        <div class="col-lg-2 col-md-4">
            <label class="filter-label"><i class="bi bi-tags me-1 text-success"></i> Kategori</label>
            <input type="hidden" name="jenis_sampah_id" id="valJenisSampah" value="{{ request('jenis_sampah_id') }}">
            <div class="dropdown">
                <button class="btn modern-dropdown-btn dropdown-toggle text-truncate" type="button" data-bs-toggle="dropdown">
                    <span id="lblJenisSampah">
                        @php
                            $jenisAktif = $jenisSampahs->firstWhere('id', request('jenis_sampah_id'));
                            echo $jenisAktif ? $jenisAktif->nama : 'Semua Kategori';
                        @endphp
                    </span>
                </button>
                <ul class="dropdown-menu custom-dropdown-menu">
                    <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('jenis_sampah_id') == '' ? 'active-filter' : '' }}" href="#" data-val="" data-target="valJenisSampah" data-label="lblJenisSampah">Semua Kategori</a></li>
                    @foreach($jenisSampahs as $jenis)
                        <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('jenis_sampah_id') == $jenis->id ? 'active-filter' : '' }}" href="#" data-val="{{ $jenis->id }}" data-target="valJenisSampah" data-label="lblJenisSampah">{{ $jenis->nama }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4">
            <label class="filter-label"><i class="bi bi-hdd-network me-1 text-success"></i> Lokasi Mesin</label>
            <input type="hidden" name="bak_sampah_id" id="valBakSampah" value="{{ request('bak_sampah_id') }}">
            <div class="dropdown">
                <button class="btn modern-dropdown-btn dropdown-toggle text-truncate" type="button" data-bs-toggle="dropdown">
                    <span id="lblBakSampah">
                        @php
                            $bakAktif = $bakSampahs->firstWhere('id', request('bak_sampah_id'));
                            echo $bakAktif ? $bakAktif->nama : 'Semua Mesin';
                        @endphp
                    </span>
                </button>
                <ul class="dropdown-menu custom-dropdown-menu">
                    <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('bak_sampah_id') == '' ? 'active-filter' : '' }}" href="#" data-val="" data-target="valBakSampah" data-label="lblBakSampah">Semua Mesin</a></li>
                    @foreach($bakSampahs as $bak)
                        <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('bak_sampah_id') == $bak->id ? 'active-filter' : '' }}" href="#" data-val="{{ $bak->id }}" data-target="valBakSampah" data-label="lblBakSampah">{{ $bak->nama }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4">
            <label class="filter-label"><i class="bi bi-shield-check me-1 text-success"></i> Validasi</label>
            <input type="hidden" name="status_validasi" id="valStatus" value="{{ request('status_validasi') }}">
            <div class="dropdown">
                <button class="btn modern-dropdown-btn dropdown-toggle text-truncate" type="button" data-bs-toggle="dropdown">
                    <span id="lblStatus">
                        @php
                            if(request('status_validasi') == 'valid') echo 'Valid';
                            elseif(request('status_validasi') == 'anomali') echo 'Anomali (Warning)';
                            else echo 'Semua Status';
                        @endphp
                    </span>
                </button>
                <ul class="dropdown-menu custom-dropdown-menu">
                    <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('status_validasi') == '' ? 'active-filter' : '' }}" href="#" data-val="" data-target="valStatus" data-label="lblStatus">Semua Status</a></li>
                    <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('status_validasi') == 'valid' ? 'active-filter' : '' }}" href="#" data-val="valid" data-target="valStatus" data-label="lblStatus"><i class="bi bi-check-circle text-success me-1"></i> Valid</a></li>
                    <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('status_validasi') == 'anomali' ? 'active-filter' : '' }}" href="#" data-val="anomali" data-target="valStatus" data-label="lblStatus"><i class="bi bi-exclamation-triangle text-danger me-1"></i> Anomali (Warning)</a></li>
                </ul>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 d-flex gap-2">
            <button type="submit" class="btn w-100 rounded-3 shadow-sm d-flex justify-content-center align-items-center fw-bold" style="height: 42px; background-color: #0f172a; color: #ffffff;">
                <i class="bi bi-funnel-fill me-2"></i> Filter
            </button>
            @if(request()->hasAny(['tanggal_dari', 'tanggal_sampai', 'jenis_sampah_id', 'bak_sampah_id', 'status_validasi']))
                <a href="{{ route('admin.transaksi.index') }}" class="btn btn-light border text-danger w-100 rounded-3 shadow-sm d-flex justify-content-center align-items-center fw-bold" style="height: 42px;" title="Reset Filter">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<div class="custom-card animate-fade-up" style="animation-delay: 0.3s;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-clean">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="25%">Waktu & Pelaku Transaksi</th>
                        <th width="20%">Lokasi Mesin</th>
                        <th width="20%">Setoran Masuk</th>
                        <th width="15%">Distribusi Reward</th>
                        <th width="10%" class="text-center">Validitas</th>
                        <th width="5%" class="text-center">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $index => $trx)
                    <tr>
                        <td class="text-center text-muted fw-semibold">{{ $transaksis->firstItem() + $index }}</td>
                        
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($trx->mahasiswa->name ?? 'Unknown') }}&background=ecfdf5&color=047857&bold=true" alt="Avatar" class="avatar-square-soft flex-shrink-0">
                                <div>
                                    <div class="fw-bold" style="color: #0f172a;">{{ $trx->mahasiswa->name ?? 'Mahasiswa Dihapus' }}</div>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <span class="badge" style="background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; font-family: monospace; border-radius: 4px;"><i class="bi bi-upc-scan me-1"></i>{{ $trx->mahasiswa->nim ?? '-' }}</span>
                                        <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/y H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            <span class="fw-medium d-block" style="color: #334155;"><i class="bi bi-hdd-network text-muted me-1"></i>{{ $trx->bakSampah->nama ?? 'Bak Dihapus' }}</span>
                            @if($trx->bakSampah && isset($trx->bakSampah->lokasi))
                                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($trx->bakSampah->lokasi->nama_lokasi ?? '-', 25) }}</small>
                            @endif
                        </td>
                        
                        <td>
                            <div class="fw-bold text-dark d-flex align-items-center gap-2 mb-1">
                                <span class="badge px-2 py-1" style="background: #f8fafc; color: #0f172a; border: 1px solid #e2e8f0; border-radius: 6px;"><i class="bi bi-box-seam me-1 text-muted"></i>{{ $trx->jumlah_final ?? $trx->jumlah_botol ?? 0 }} pcs</span>
                                <span class="badge px-2 py-1" style="background: #f8fafc; color: #0f172a; border: 1px solid #e2e8f0; border-radius: 6px;"><i class="bi bi-speedometer2 me-1 text-muted"></i>
                                    @if(($trx->berat ?? 0) >= 1000)
                                        {{ number_format($trx->berat / 1000, 2) }} kg
                                    @else
                                        {{ number_format($trx->berat ?? 0, 0) }} g
                                    @endif
                                </span>
                            </div>
                            @if(($trx->jumlah_botol ?? 0) > 0 || ($trx->jumlah_kaleng ?? 0) > 0)
                                <small class="text-muted d-flex gap-2">
                                    <span><i class="bi bi-cup-straw text-success opacity-75"></i> {{ $trx->jumlah_botol ?? 0 }}</span>
                                    <span><i class="bi bi-plugin text-warning opacity-75"></i> {{ $trx->jumlah_kaleng ?? 0 }}</span>
                                </small>
                            @endif
                        </td>
                        
                        <td>
                            <div class="fw-bold mb-1" style="font-size: 0.95rem; color: #059669;"><i class="bi bi-star-fill text-warning me-1"></i>+{{ number_format($trx->poin_didapat ?? 0) }} Pts</div>
                            <div class="fw-bold" style="font-size: 0.85rem; color: #d97706;"><i class="bi bi-coin text-warning me-1"></i>+{{ number_format($trx->koin_didapat ?? 0) }} Koin</div>
                        </td>
                        
                        <td class="text-center">
                            @if(($trx->status_validasi ?? 'valid') === 'valid')
                                <span class="badge px-3 py-1 rounded-2" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;">Valid</span>
                            @else
                                <span class="badge px-3 py-1 rounded-2" style="background: #fff1f2; color: #dc2626; border: 1px solid #fecaca;" title="Peringatan Anomali Sensor">Anomali</span>
                            @endif
                        </td>
                        
                        <td class="text-center">
                            <a href="{{ route('admin.transaksi.show', $trx) }}" class="btn-icon btn-detail mx-auto" title="Lihat Detail Transaksi">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    
                    @empty
                    <tr>
                        <td colspan="7" class="border-0">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <div class="fw-bold text-secondary mb-1">Riwayat Kosong</div>
                                <small>Belum ada data transaksi yang sesuai dengan filter.</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($transaksis->hasPages())
    <div class="pagination-wrapper">
        {{ $transaksis->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Logic untuk Custom Dropdown Filter ---
        const filterItems = document.querySelectorAll('.filter-item');
        
        filterItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                const value = this.getAttribute('data-val');
                const labelText = this.innerHTML; // Mengambil HTML agar ikon ikut terbawa
                const plainText = this.innerText.trim();
                const targetInputId = this.getAttribute('data-target');
                const targetLabelId = this.getAttribute('data-label');
                
                document.getElementById(targetInputId).value = value;
                document.getElementById(targetLabelId).innerText = plainText;
                
                const parentUl = this.closest('.custom-dropdown-menu');
                parentUl.querySelectorAll('.filter-item').forEach(opt => opt.classList.remove('active-filter'));
                this.classList.add('active-filter');
            });
        });
    });
</script>
@endpush