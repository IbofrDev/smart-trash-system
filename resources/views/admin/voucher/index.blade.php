@extends('layouts.admin')

@section('title', 'Data Voucher')

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
    
    /* Input Form Modern */
    .filter-input { border-radius: 8px; border: 1px solid #e2e8f0; padding: 0.6rem 1rem; font-size: 0.9rem; color: #0f172a; background-color: #f8fafc; transition: all 0.2s; }
    .filter-input:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }
    
    .search-box { position: relative; width: 100%; }
    .search-box i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    .search-box input { width: 100%; padding-left: 2.5rem; }

    /* Custom Dropdown Filter */
    .modern-dropdown-btn { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.6rem 2.5rem 0.6rem 1rem; color: #0f172a; font-size: 0.9rem; font-weight: 500; width: 100%; text-align: left; transition: all 0.2s; position: relative; }
    .modern-dropdown-btn:hover, .modern-dropdown-btn:focus, .modern-dropdown-btn.show { background-color: #ffffff; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); outline: none; }
    .modern-dropdown-btn::after { display: none; }
    .modern-dropdown-btn::before { content: '\F282'; font-family: 'bootstrap-icons'; font-size: 0.85rem; color: #94a3b8; position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); transition: transform 0.3s; }
    .modern-dropdown-btn.show::before { transform: translateY(-50%) rotate(180deg); color: #10b981; }
    
    .custom-dropdown-menu { border: 1px solid #f1f5f9; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-radius: 10px; padding: 0.5rem; margin-top: 0.5rem !important; width: 100%; max-height: 250px; overflow-y: auto; z-index: 1050; }
    .custom-dropdown-item { border-radius: 6px; padding: 0.5rem 1rem; font-size: 0.9rem; color: #475569; font-weight: 500; transition: all 0.2s; }
    .custom-dropdown-item:hover, .custom-dropdown-item.active-filter { background-color: #ecfdf5; color: #047857; }

    /* Custom Ticket Style - Minimalist */
    .ticket-code {
        background-color: #f8fafc; border: 1px dashed #cbd5e1; padding: 0.4rem 0.8rem;
        border-radius: 6px; font-family: 'Courier New', Courier, monospace; font-weight: 700;
        color: #0f172a; display: inline-block; letter-spacing: 1px;
    }

    /* Avatar Kotak Halus */
    .avatar-square-soft { width: 35px; height: 35px; border-radius: 8px; object-fit: cover; border: 1px solid #e2e8f0; }

    /* Styling Tabel Clean */
    .table-clean { margin-bottom: 0; width: 100%; }
    .table-clean th { background-color: #ffffff; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 1rem 1.5rem; border-bottom: 2px solid #f1f5f9; }
    .table-clean td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; color: #334155; font-size: 0.9rem; }
    .table-clean tbody tr { transition: background-color 0.2s; }
    .table-clean tbody tr:hover { background-color: #f8fafc; }

    /* Empty State */
    .empty-state { padding: 4rem 1rem; text-align: center; color: #94a3b8; }
    .empty-state i { font-size: 4rem; color: #e2e8f0; display: block; margin-bottom: 1rem; }
    .pagination-wrapper { padding: 1.25rem 1.5rem; background-color: #ffffff; border-top: 1px solid #f1f5f9; }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Manajemen Voucher</h4>
        <p class="text-muted mb-0 small">Kelola penukaran koin mahasiswa menjadi voucher makan.</p>
    </div>
</div>

<div class="row g-4 mb-4 animate-fade-up" style="animation-delay: 0.1s;">
    <div class="col-md-3 col-6">
        <div class="stat-card-boxy">
            <i class="bi bi-ticket-detailed stat-icon"></i>
            <h3>{{ number_format($stats['total']) }}</h3>
            <p>Total Voucher</p>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card-boxy">
            <i class="bi bi-check-circle stat-icon"></i>
            <h3 style="color: #059669;">{{ number_format($stats['aktif']) }}</h3>
            <p>Voucher Aktif</p>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card-boxy">
            <i class="bi bi-bag-check stat-icon"></i>
            <h3>{{ number_format($stats['terpakai']) }}</h3>
            <p>Telah Terpakai</p>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card-boxy">
            <i class="bi bi-clock-history stat-icon"></i>
            <h3 style="color: #dc2626;">{{ number_format($stats['expired']) }}</h3>
            <p>Kadaluarsa</p>
        </div>
    </div>
</div>

<div class="filter-wrapper animate-fade-up" style="animation-delay: 0.2s;">
    <form action="{{ route('admin.voucher.index') }}" method="GET" class="row g-3 align-items-end">
        
        <div class="col-lg-3 col-md-6">
            <label class="filter-label"><i class="bi bi-person text-success me-1"></i> Cari Mahasiswa</label>
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" name="search" class="form-control filter-input" placeholder="Nama atau NIM..." value="{{ request('search') }}">
            </div>
        </div>
        
        <div class="col-lg-2 col-md-6">
            <label class="filter-label"><i class="bi bi-ticket-perforated text-success me-1"></i> Status Voucher</label>
            <input type="hidden" name="status" id="valStatus" value="{{ request('status') }}">
            <div class="dropdown">
                <button class="btn modern-dropdown-btn dropdown-toggle text-truncate" type="button" data-bs-toggle="dropdown">
                    <span id="lblStatus">
                        @php
                            if(request('status') == 'aktif') echo 'Aktif (Berlaku)';
                            elseif(request('status') == 'terpakai') echo 'Telah Terpakai';
                            elseif(request('status') == 'expired') echo 'Expired / Hangus';
                            else echo 'Semua Status';
                        @endphp
                    </span>
                </button>
                <ul class="dropdown-menu custom-dropdown-menu">
                    <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('status') == '' ? 'active-filter' : '' }}" href="#" data-val="" data-target="valStatus" data-label="lblStatus">Semua Status</a></li>
                    <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('status') == 'aktif' ? 'active-filter' : '' }}" href="#" data-val="aktif" data-target="valStatus" data-label="lblStatus"><i class="bi bi-check-circle text-success me-2"></i>Aktif (Berlaku)</a></li>
                    <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('status') == 'terpakai' ? 'active-filter' : '' }}" href="#" data-val="terpakai" data-target="valStatus" data-label="lblStatus"><i class="bi bi-bag-check text-secondary me-2"></i>Telah Terpakai</a></li>
                    <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('status') == 'expired' ? 'active-filter' : '' }}" href="#" data-val="expired" data-target="valStatus" data-label="lblStatus"><i class="bi bi-clock-history text-danger me-2"></i>Expired / Hangus</a></li>
                </ul>
            </div>
        </div>

        <div class="col-lg-2 col-md-4">
            <label class="filter-label"><i class="bi bi-calendar-event text-success me-1"></i> Dari Tanggal</label>
            <input type="date" name="dari" class="form-control filter-input w-100" value="{{ request('dari') }}">
        </div>
        
        <div class="col-lg-2 col-md-4">
            <label class="filter-label"><i class="bi bi-calendar-check text-success me-1"></i> Sampai Tanggal</label>
            <input type="date" name="sampai" class="form-control filter-input w-100" value="{{ request('sampai') }}">
        </div>
        
        <div class="col-lg-3 col-md-4 d-flex gap-2">
            <button type="submit" class="btn flex-grow-1 rounded-3 shadow-sm d-flex justify-content-center align-items-center fw-bold" style="height: 42px; background-color: #0f172a; color: #ffffff;">
                <i class="bi bi-funnel-fill me-2"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'status', 'dari', 'sampai']) && (request('search') != '' || request('status') != '' || request('dari') != '' || request('sampai') != ''))
                <a href="{{ route('admin.voucher.index') }}" class="btn btn-light border text-danger rounded-3 shadow-sm d-flex justify-content-center align-items-center fw-bold px-3" style="height: 42px;" title="Reset Filter">
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
                        <th width="20%">Kode Voucher</th>
                        <th width="25%">Pemilik (Mahasiswa)</th>
                        <th width="15%">Harga Koin</th>
                        <th width="15%">Status</th>
                        <th width="20%">Timeline / Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $index => $voucher)
                    <tr>
                        <td class="text-center text-muted fw-semibold">{{ $vouchers->firstItem() + $index }}</td>
                        
                        <td>
                            <div class="ticket-code">
                                <i class="bi bi-ticket-perforated me-1 text-success"></i>{{ $voucher->kode_voucher }}
                            </div>
                        </td>
                        
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($voucher->mahasiswa->name ?? 'User') }}&background=ecfdf5&color=047857&bold=true" alt="Avatar" class="avatar-square-soft">
                                <div>
                                    <div class="fw-bold" style="color: #0f172a; font-size: 0.95rem;">{{ $voucher->mahasiswa->name ?? 'Mahasiswa Dihapus' }}</div>
                                    <small class="text-muted"><i class="bi bi-upc-scan me-1"></i>{{ $voucher->mahasiswa->nim ?? '-' }}</small>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="badge px-3 py-2 rounded-2 shadow-sm" style="background: #f8fafc; color: #0f172a; border: 1px solid #e2e8f0; font-weight: 700;">
                                <i class="bi bi-coin text-warning me-1"></i> {{ number_format($voucher->koin_digunakan) }}
                            </span>
                        </td>
                        
                        <td>
                            @if($voucher->status === 'aktif' && $voucher->expired_at > now())
                                <span class="badge px-3 py-1 rounded-2" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;"><i class="bi bi-check-circle me-1"></i> Aktif</span>
                            @elseif($voucher->status === 'terpakai')
                                <span class="badge px-3 py-1 rounded-2" style="background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;"><i class="bi bi-bag-check me-1"></i> Terpakai</span>
                            @else
                                <span class="badge px-3 py-1 rounded-2" style="background: #fff1f2; color: #dc2626; border: 1px solid #fecaca;"><i class="bi bi-clock-history me-1"></i> Expired</span>
                            @endif
                        </td>
                        
                        <td>
                            <div class="d-flex flex-column gap-1" style="font-size: 0.85rem;">
                                <div>
                                    <span class="text-muted d-inline-block" style="width: 60px;">Dibuat</span>
                                    <span class="fw-medium text-dark">: {{ \Carbon\Carbon::parse($voucher->created_at)->format('d/m/Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-muted d-inline-block" style="width: 60px;">Expired</span>
                                    <span class="fw-medium {{ $voucher->expired_at < now() && $voucher->status !== 'terpakai' ? 'text-danger' : 'text-dark' }}">
                                        : {{ \Carbon\Carbon::parse($voucher->expired_at)->format('d/m/Y') }}
                                    </span>
                                </div>
                                @if($voucher->used_at)
                                <div>
                                    <span class="text-muted d-inline-block" style="width: 60px;">Dipakai</span>
                                    <span class="fw-bold text-success">: {{ \Carbon\Carbon::parse($voucher->used_at)->format('d/m/Y H:i') }}</span>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    
                    @empty
                    <tr>
                        <td colspan="6" class="border-0">
                            <div class="empty-state">
                                <i class="bi bi-ticket-detailed"></i>
                                <div class="fw-bold text-secondary mb-1">Voucher Kosong</div>
                                <small>Belum ada riwayat penukaran voucher makan.</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($vouchers->hasPages())
    <div class="pagination-wrapper">
        {{ $vouchers->links() }}
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
                const plainText = this.innerText.trim();
                const targetInputId = this.getAttribute('data-target');
                const targetLabelId = this.getAttribute('data-label');
                
                // Update nilai di hidden input
                document.getElementById(targetInputId).value = value;
                
                // Update teks di tombol
                document.getElementById(targetLabelId).innerText = plainText;
                
                // Hilangkan class aktif dari opsi lain
                const parentUl = this.closest('.custom-dropdown-menu');
                parentUl.querySelectorAll('.filter-item').forEach(opt => opt.classList.remove('active-filter'));
                
                // Tambahkan class aktif ke item yang diklik
                this.classList.add('active-filter');
            });
        });
    });
</script>
@endpush