@extends('layouts.admin')

@section('title', 'Audit Log Aktivitas')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama - Radius Dikurangi & Bersih */
    .custom-card { border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }
    
    /* Filter Area - Lapang & Berdiri Sendiri */
    .filter-wrapper { background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; position: relative; z-index: 50; box-shadow: 0 4px 12px rgba(0,0,0,0.01); }
    .filter-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; display: block; }
    
    /* Input Form Modern */
    .filter-input { border-radius: 8px; border: 1px solid #e2e8f0; padding: 0.6rem 1rem; font-size: 0.9rem; color: #0f172a; background-color: #f8fafc; transition: all 0.2s; width: 100%; }
    .filter-input:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }
    
    .search-box { position: relative; width: 100%; }
    .search-box i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    .search-box input { padding-left: 2.5rem; }

    /* Custom Dropdown Filter */
    .modern-dropdown-btn { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.6rem 2.5rem 0.6rem 1rem; color: #0f172a; font-size: 0.9rem; font-weight: 500; width: 100%; text-align: left; transition: all 0.2s; position: relative; }
    .modern-dropdown-btn:hover, .modern-dropdown-btn:focus, .modern-dropdown-btn.show { background-color: #ffffff; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); outline: none; }
    .modern-dropdown-btn::after { display: none; }
    .modern-dropdown-btn::before { content: '\F282'; font-family: 'bootstrap-icons'; font-size: 0.85rem; color: #94a3b8; position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); transition: transform 0.3s; }
    .modern-dropdown-btn.show::before { transform: translateY(-50%) rotate(180deg); color: #10b981; }
    
    .custom-dropdown-menu { border: 1px solid #f1f5f9; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-radius: 10px; padding: 0.5rem; margin-top: 0.5rem !important; width: 100%; max-height: 250px; overflow-y: auto; z-index: 1050; }
    .custom-dropdown-item { border-radius: 6px; padding: 0.5rem 1rem; font-size: 0.9rem; color: #475569; font-weight: 500; transition: all 0.2s; }
    .custom-dropdown-item:hover, .custom-dropdown-item.active-filter { background-color: #ecfdf5; color: #047857; }

    /* Tech Code Style - Clean Minimalist */
    .tech-code { background-color: #f8fafc; color: #0f172a; padding: 4px 8px; border-radius: 6px; font-family: 'Courier New', Courier, monospace; font-weight: 700; font-size: 0.85rem; border: 1px solid #e2e8f0; display: inline-block; letter-spacing: 0.5px; }

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
        <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">System Audit Log</h4>
        <p class="text-muted mb-0 small">Riwayat aktivitas dan rekam jejak akses pengguna dalam sistem.</p>
    </div>
</div>

<div class="filter-wrapper animate-fade-up" style="animation-delay: 0.1s;">
    <form action="{{ route('admin.log-aktivitas.index') }}" method="GET" class="row g-3 align-items-end">
        
        <div class="col-lg-3 col-md-6">
            <label class="filter-label"><i class="bi bi-search text-success me-1"></i> Cari Log</label>
            <div class="search-box">
                <i class="bi bi-keyboard"></i>
                <input type="text" name="search" class="form-control filter-input" placeholder="Kata kunci aktivitas..." value="{{ request('search') }}">
            </div>
        </div>
        
        <div class="col-lg-2 col-md-6">
            <label class="filter-label"><i class="bi bi-person-badge text-success me-1"></i> Tipe Akses</label>
            <input type="hidden" name="user_type" id="valUserType" value="{{ request('user_type') }}">
            <div class="dropdown">
                <button class="btn modern-dropdown-btn dropdown-toggle text-truncate" type="button" data-bs-toggle="dropdown">
                    <span id="lblUserType">
                        @php
                            if(request('user_type') == 'admin') echo 'Admin System';
                            elseif(request('user_type') == 'pengelola') echo 'Pengelola Mesin';
                            else echo 'Semua Role Akses';
                        @endphp
                    </span>
                </button>
                <ul class="dropdown-menu custom-dropdown-menu">
                    <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('user_type') == '' ? 'active-filter' : '' }}" href="#" data-val="" data-target="valUserType" data-label="lblUserType">Semua Role Akses</a></li>
                    <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('user_type') == 'admin' ? 'active-filter' : '' }}" href="#" data-val="admin" data-target="valUserType" data-label="lblUserType"><i class="bi bi-shield-lock text-success me-2"></i>Admin System</a></li>
                    <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('user_type') == 'pengelola' ? 'active-filter' : '' }}" href="#" data-val="pengelola" data-target="valUserType" data-label="lblUserType"><i class="bi bi-person-gear text-secondary me-2"></i>Pengelola Mesin</a></li>
                </ul>
            </div>
        </div>

        <div class="col-lg-2 col-md-4">
            <label class="filter-label"><i class="bi bi-calendar-event text-success me-1"></i> Dari Tanggal</label>
            <input type="date" name="tanggal_dari" class="form-control filter-input" value="{{ request('tanggal_dari') }}">
        </div>
        
        <div class="col-lg-2 col-md-4">
            <label class="filter-label"><i class="bi bi-calendar-check text-success me-1"></i> Sampai Tanggal</label>
            <input type="date" name="tanggal_sampai" class="form-control filter-input" value="{{ request('tanggal_sampai') }}">
        </div>
        
        <div class="col-lg-3 col-md-4 d-flex gap-2">
            <button type="submit" class="btn flex-grow-1 rounded-3 shadow-sm d-flex justify-content-center align-items-center fw-bold" style="height: 42px; background-color: #0f172a; color: #ffffff;">
                <i class="bi bi-funnel-fill me-2"></i> Filter Log
            </button>
            @if(request()->hasAny(['search', 'user_type', 'tanggal_dari', 'tanggal_sampai']) && (request('search') != '' || request('user_type') != '' || request('tanggal_dari') != '' || request('tanggal_sampai') != ''))
                <a href="{{ route('admin.log-aktivitas.index') }}" class="btn btn-light border text-danger rounded-3 shadow-sm d-flex justify-content-center align-items-center fw-bold px-3" style="height: 42px;" title="Reset Filter">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<div class="custom-card animate-fade-up" style="animation-delay: 0.2s;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-clean">
                <thead>
                    <tr>
                        <th width="5%" class="text-center ps-4">ID</th>
                        <th width="15%">Timestamp</th>
                        <th width="15%">Role Akses</th>
                        <th width="30%">Deskripsi Aktivitas</th>
                        <th width="15%">Target Tabel</th>
                        <th width="20%" class="pe-4">IP / Jaringan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                    <tr>
                        <td class="text-center text-muted fw-semibold ps-4">{{ $logs->firstItem() + $index }}</td>
                        
                        <td>
                            @if($log->created_at)
                                <div class="fw-bold" style="color: #0f172a;">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</div>
                                <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        
                        <td>
                            @if($log->user_type === 'admin')
                                <span class="badge px-3 py-1 rounded-2" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;"><i class="bi bi-shield-lock-fill me-1"></i> Admin</span>
                            @else
                                <span class="badge px-3 py-1 rounded-2" style="background: #f8fafc; color: #475569; border: 1px solid #e2e8f0;"><i class="bi bi-person-gear me-1"></i> Pengelola</span>
                            @endif
                        </td>
                        
                        <td>
                            <span class="fw-medium" style="color: #334155;">{{ $log->aktivitas }}</span>
                        </td>
                        
                        <td>
                            @if($log->tabel_target)
                                <div class="tech-code"><i class="bi bi-database me-1 opacity-50"></i>{{ $log->tabel_target }}</div>
                            @else
                                <span class="text-muted fst-italic">System Action</span>
                            @endif
                        </td>
                        
                        <td class="pe-4">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-globe text-muted opacity-50"></i>
                                <span class="fw-semibold" style="color: #64748b; font-family: monospace; font-size: 0.95rem;">
                                    {{ $log->ip_address ?? 'Unknown IP' }}
                                </span>
                            </div>
                        </td>
                    </tr>
                    
                    @empty
                    <tr>
                        <td colspan="6" class="border-0">
                            <div class="empty-state">
                                <i class="bi bi-journal-code"></i>
                                <div class="fw-bold text-secondary mb-1">Log Kosong</div>
                                <small>Belum ada riwayat aktivitas sistem yang tercatat.</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($logs->hasPages())
    <div class="pagination-wrapper">
        {{ $logs->links() }}
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
                
                // Update teks di tombol (menggunakan teks murni agar rapi)
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