@extends('layouts.admin')

@section('title', 'Kelola Users')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up {
        opacity: 0;
        transform: translateY(15px);
        animation: fadeUp 0.5s ease-out forwards;
    }
    @keyframes fadeUp {
        to { opacity: 1; transform: translateY(0); }
    }

    /* Card Utama */
    .custom-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        background-color: #ffffff;
        overflow: hidden;
    }

    /* Header & Toolbar Filter */
    .table-toolbar {
        padding: 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        background-color: #ffffff;
    }
    
    .search-box {
        position: relative;
        flex-grow: 1;
        max-width: 300px;
    }
    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }
    .search-box input {
        width: 100%;
        padding: 0.6rem 1rem 0.6rem 2.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 50px;
        font-size: 0.9rem;
        transition: all 0.2s;
        background-color: #f9fafb;
    }
    .search-box input:focus {
        outline: none;
        border-color: #10b981;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    /* =========================================
       CUSTOM DROPDOWN FILTER MODERN
    ========================================= */
    .modern-dropdown-btn {
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 50px;
        padding: 0.6rem 1.25rem;
        color: #4b5563;
        font-size: 0.9rem;
        font-weight: 500;
        display: flex;
        justify-content: space-between;
        align-items: center;
        min-width: 150px;
        transition: all 0.2s;
    }
    .modern-dropdown-btn:hover, .modern-dropdown-btn:focus, .modern-dropdown-btn.show {
        background-color: #ffffff;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        color: #1f2937;
    }
    
    .custom-dropdown-menu {
        border: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-radius: 16px;
        padding: 0.5rem;
        min-width: 180px;
        margin-top: 0.5rem !important;
    }
    .custom-dropdown-item {
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        color: #4b5563;
        font-weight: 500;
        transition: all 0.2s;
    }
    .custom-dropdown-item:hover, .custom-dropdown-item.active-filter {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    /* Avatar & User Info */
    .user-info { display: flex; align-items: center; gap: 1rem; }
    .avatar-sm {
        width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-weight: 700; color: #ffffff; font-size: 0.9rem; flex-shrink: 0;
    }
    tr:nth-child(4n+1) .avatar-sm { background: linear-gradient(135deg, #3b82f6, #2563eb); } 
    tr:nth-child(4n+2) .avatar-sm { background: linear-gradient(135deg, #10b981, #059669); } 
    tr:nth-child(4n+3) .avatar-sm { background: linear-gradient(135deg, #8b5cf6, #7c3aed); } 
    tr:nth-child(4n+4) .avatar-sm { background: linear-gradient(135deg, #f59e0b, #d97706); } 

    .user-details h6 { margin: 0; font-weight: 600; color: #1f2937; font-size: 0.95rem; }
    .user-details small { color: #6b7280; font-size: 0.8rem; }

    /* Styling Tabel Modern */
    .table-modern { margin-bottom: 0; width: 100%; }
    .table-modern th {
        background-color: #f9fafb; color: #6b7280; font-weight: 600; text-transform: uppercase;
        font-size: 0.75rem; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb;
    }
    .table-modern td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f3f4f6; color: #4b5563; }
    .table-modern tbody tr { transition: background-color 0.2s; }
    .table-modern tbody tr:hover { background-color: #f9fafb; }
    
    /* Tombol Aksi */
    .action-btns { display: flex; gap: 0.5rem; }
    .btn-icon {
        width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px; border: none; transition: all 0.2s; text-decoration: none; cursor: pointer;
    }
    .btn-edit { background-color: #eff6ff; color: #3b82f6; }
    .btn-edit:hover { background-color: #3b82f6; color: #fff; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3); }
    
    .btn-delete { background-color: #fef2f2; color: #ef4444; }
    .btn-delete:hover { background-color: #ef4444; color: #fff; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3); }

    .empty-state { padding: 4rem 1rem; text-align: center; color: #9ca3af; }
    .empty-state i { font-size: 3.5rem; color: #e5e7eb; display: block; margin-bottom: 1rem; }
    .pagination-wrapper { padding: 1.25rem 1.5rem; background-color: #ffffff; border-top: 1px solid #f3f4f6; }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-dark mb-1">Kelola Users</h4>
        <p class="text-muted mb-0 small">Manajemen akun administrator dan pengelola sistem.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-success rounded-pill px-4 fw-semibold shadow-sm d-flex align-items-center gap-2">
        <i class="bi bi-plus-circle"></i> Tambah User
    </a>
</div>

<div class="card custom-card animate-fade-up" style="animation-delay: 0.1s;">
    
    <form action="{{ route('admin.users.index') }}" method="GET" class="table-toolbar">
        
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="search" placeholder="Cari nama atau email..." value="{{ request('search') }}">
        </div>
        
        <input type="hidden" name="role" id="filterRole" value="{{ request('role') }}">
        <input type="hidden" name="status" id="filterStatus" value="{{ request('status') }}">

        <div class="d-flex flex-wrap gap-2 align-items-center">
            
            <div class="dropdown">
                <button class="btn modern-dropdown-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="textRole">
                        {{ request('role') == 'admin' ? 'Admin' : (request('role') == 'pengelola' ? 'Pengelola' : 'Semua Role') }}
                    </span>
                </button>
                <ul class="dropdown-menu custom-dropdown-menu">
                    <li><a class="dropdown-item custom-dropdown-item filter-opt {{ request('role') == '' ? 'active-filter' : '' }}" href="#" data-target="filterRole" data-label="textRole" data-value="">Semua Role</a></li>
                    <li><a class="dropdown-item custom-dropdown-item filter-opt {{ request('role') == 'admin' ? 'active-filter' : '' }}" href="#" data-target="filterRole" data-label="textRole" data-value="admin">Admin</a></li>
                    <li><a class="dropdown-item custom-dropdown-item filter-opt {{ request('role') == 'pengelola' ? 'active-filter' : '' }}" href="#" data-target="filterRole" data-label="textRole" data-value="pengelola">Pengelola</a></li>
                </ul>
            </div>
            
            <div class="dropdown">
                <button class="btn modern-dropdown-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="textStatus">
                        {{ request('status') === '1' ? 'Aktif' : (request('status') === '0' ? 'Nonaktif' : 'Semua Status') }}
                    </span>
                </button>
                <ul class="dropdown-menu custom-dropdown-menu">
                    <li><a class="dropdown-item custom-dropdown-item filter-opt {{ request('status') === null ? 'active-filter' : '' }}" href="#" data-target="filterStatus" data-label="textStatus" data-value="">Semua Status</a></li>
                    <li><a class="dropdown-item custom-dropdown-item filter-opt {{ request('status') === '1' ? 'active-filter' : '' }}" href="#" data-target="filterStatus" data-label="textStatus" data-value="1">Aktif</a></li>
                    <li><a class="dropdown-item custom-dropdown-item filter-opt {{ request('status') === '0' ? 'active-filter' : '' }}" href="#" data-target="filterStatus" data-label="textStatus" data-value="0">Nonaktif</a></li>
                </ul>
            </div>

            <button type="submit" class="btn btn-dark rounded-pill px-4 fw-medium shadow-sm">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>

            @if(request()->hasAny(['search', 'role', 'status']))
            <a href="{{ route('admin.users.index') }}" class="btn btn-light border rounded-pill px-3 text-danger fw-medium" title="Reset Filter">
                <i class="bi bi-x-lg"></i>
            </a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-modern">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="30%">Profil Pengguna</th>
                    <th width="15%">Kontak</th>
                    <th width="15%">Role</th>
                    <th width="10%">Status</th>
                    <th width="15%">Dibuat</th>
                    <th width="10%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td class="text-muted fw-semibold">{{ $users->firstItem() + $index }}</td>
                    <td>
                        <div class="user-info">
                            <div class="avatar-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="user-details">
                                <h6>{{ $user->name }}</h6>
                                <small>{{ $user->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-secondary fw-medium"><i class="bi bi-telephone text-muted me-1"></i> {{ $user->phone ?? '-' }}</span>
                    </td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill">
                                <i class="bi bi-shield-lock-fill me-1"></i> Admin
                            </span>
                        @else
                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2 rounded-pill">
                                <i class="bi bi-person-badge-fill me-1"></i> Pengelola
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Aktif</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-1 rounded-pill fw-semibold"><i class="bi bi-x-circle-fill me-1"></i> Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="text-dark fw-medium">{{ $user->created_at->format('d M Y') }}</div>
                    </td>
                    <td>
                        <div class="action-btns justify-content-center">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-icon btn-edit" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            
                            @if($user->id !== auth()->id())
                            <button type="button" class="btn-icon btn-delete" title="Hapus" onclick="confirmDelete('{{ route('admin.users.destroy', $user) }}', '{{ $user->name }}')">
                                <i class="bi bi-trash3"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="border-0">
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <div class="fw-bold text-secondary mb-1">Tidak ada data user</div>
                            <small>Data pengguna belum ditambahkan atau tidak ditemukan.</small>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="pagination-wrapper">
        {{ $users->links() }}
    </div>
    @endif
</div>

@include('components.delete-modal')
@endsection

@push('scripts')
<script>
    // Script untuk menangani klik pada Custom Dropdown Filter
    document.addEventListener('DOMContentLoaded', function() {
        const filterOptions = document.querySelectorAll('.filter-opt');
        
        filterOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah pindah halaman saat diklik
                
                // Ambil data dari atribut elemen yang diklik
                const value = this.getAttribute('data-value');
                const labelText = this.innerText;
                const targetInputId = this.getAttribute('data-target');
                const targetLabelId = this.getAttribute('data-label');
                
                // 1. Masukkan nilai (value) ke dalam input hidden agar bisa di-submit
                document.getElementById(targetInputId).value = value;
                
                // 2. Ubah teks di tombol dropdown agar sesuai dengan yang dipilih
                document.getElementById(targetLabelId).innerText = labelText;
                
                // 3. (Opsional) Styling: Hapus class active-filter dari semua opsi di dropdown yang sama
                const parentUl = this.closest('.custom-dropdown-menu');
                parentUl.querySelectorAll('.filter-opt').forEach(opt => opt.classList.remove('active-filter'));
                
                // Tambahkan class active-filter ke opsi yang baru diklik
                this.classList.add('active-filter');
            });
        });
    });
</script>
@endpush