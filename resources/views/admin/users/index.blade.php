@extends('layouts.admin')

@section('title', 'Kelola Users')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama - Lebih Kotak */
    .custom-card { border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }

    /* Header & Toolbar Filter */
    .table-toolbar { padding: 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; background-color: #ffffff; }
    
    .search-box { position: relative; flex-grow: 1; max-width: 300px; }
    .search-box i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    /* Radius dikurangi menjadi 8px */
    .search-box input { width: 100%; padding: 0.6rem 1rem 0.6rem 2.5rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; transition: all 0.2s; background-color: #f8fafc; }
    .search-box input:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }

    /* Custom Dropdown Filter Modern - Lebih Kotak */
    .modern-dropdown-btn { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.6rem 1.25rem; color: #475569; font-size: 0.9rem; font-weight: 500; display: flex; justify-content: space-between; align-items: center; min-width: 150px; transition: all 0.2s; }
    .modern-dropdown-btn:hover, .modern-dropdown-btn:focus, .modern-dropdown-btn.show { background-color: #ffffff; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); color: #0f172a; }
    
    .custom-dropdown-menu { border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-radius: 10px; padding: 0.5rem; min-width: 180px; margin-top: 0.5rem !important; border: 1px solid #f1f5f9; }
    .custom-dropdown-item { border-radius: 6px; padding: 0.5rem 1rem; font-size: 0.9rem; color: #475569; font-weight: 500; transition: all 0.2s; }
    .custom-dropdown-item:hover, .custom-dropdown-item.active-filter { background-color: #ecfdf5; color: #047857; }

    /* Avatar & User Info */
    .user-info { display: flex; align-items: center; gap: 1rem; }
    .avatar-sm { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #ffffff; font-size: 0.9rem; flex-shrink: 0; background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .user-details h6 { margin: 0; font-weight: 700; color: #0f172a; font-size: 0.95rem; }
    .user-details small { color: #64748b; font-size: 0.8rem; }

    /* Styling Tabel Modern */
    .table-clean { margin-bottom: 0; width: 100%; }
    .table-clean th { background-color: #ffffff; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 1rem 1.5rem; border-bottom: 2px solid #f1f5f9; }
    .table-clean td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; color: #334155; }
    .table-clean tbody tr { transition: background-color 0.2s; }
    .table-clean tbody tr:hover { background-color: #f8fafc; }
    
    /* Tombol Aksi */
    .action-btns { display: flex; gap: 0.5rem; }
    .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: none; transition: all 0.2s; text-decoration: none; cursor: pointer; background-color: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }
    .btn-icon:hover { transform: translateY(-2px); }
    .btn-edit:hover { background-color: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
    .btn-delete:hover { background-color: #fef2f2; color: #ef4444; border-color: #fecaca; }

    .empty-state { padding: 4rem 1rem; text-align: center; color: #94a3b8; }
    .empty-state i { font-size: 3.5rem; color: #e2e8f0; display: block; margin-bottom: 1rem; }
    .pagination-wrapper { padding: 1.25rem 1.5rem; background-color: #ffffff; border-top: 1px solid #f1f5f9; }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Kelola Users</h4>
        <p class="text-muted mb-0 small">Manajemen akun administrator dan pengelola sistem.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-success rounded-3 px-4 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: #10b981; border: none;">
        <i class="bi bi-plus-lg"></i> Tambah User
    </a>
</div>

<div class="custom-card animate-fade-up" style="animation-delay: 0.1s;">
    
    <form action="{{ route('admin.users.index') }}" method="GET" class="table-toolbar">
        
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="search" placeholder="Cari nama atau email..." value="{{ request('search') }}">
        </div>
        
        <input type="hidden" name="role" id="filterRole" value="{{ request('role') }}">
        <input type="hidden" name="status" id="filterStatus" value="{{ request('status') }}">

        <div class="d-flex flex-wrap gap-2 align-items-center">
            
            <div class="dropdown">
                <button class="btn modern-dropdown-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
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
                <button class="btn modern-dropdown-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
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

            <button type="submit" class="btn btn-dark rounded-3 px-4 fw-medium shadow-sm" style="background: #0f172a;">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>

            @if(request()->hasAny(['search', 'role', 'status']))
            <a href="{{ route('admin.users.index') }}" class="btn btn-light border rounded-3 px-3 text-danger fw-medium" title="Reset Filter">
                <i class="bi bi-x-lg"></i>
            </a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-clean">
            <thead>
                <tr>
                    <th width="5%" class="ps-4">No</th>
                    <th width="30%">Profil Pengguna</th>
                    <th width="15%">Kontak</th>
                    <th width="15%">Role</th>
                    <th width="10%">Status</th>
                    <th width="15%">Dibuat</th>
                    <th width="10%" class="text-center pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td class="text-muted fw-semibold ps-4">{{ $users->firstItem() + $index }}</td>
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
                            <span class="badge" style="background: #f1f5f9; color: #0f172a; border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 6px;">
                                <i class="bi bi-shield-lock me-1"></i> Admin
                            </span>
                        @else
                            <span class="badge" style="background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 6px;">
                                <i class="bi bi-person-badge me-1"></i> Pengelola
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="badge" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; padding: 6px 12px; border-radius: 6px;"><i class="bi bi-check-circle me-1"></i> Aktif</span>
                        @else
                            <span class="badge" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 12px; border-radius: 6px;"><i class="bi bi-x-circle me-1"></i> Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="text-dark fw-medium" style="font-size: 0.85rem;">{{ $user->created_at->format('d M Y') }}</div>
                    </td>
                    <td class="pe-4">
                        <div class="action-btns justify-content-center">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn-icon" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-icon btn-edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            
                            @if($user->id !== auth()->id())
                            <button type="button" class="btn-icon btn-delete" title="Hapus" onclick="confirmDelete('{{ route('admin.users.destroy', $user) }}', '{{ $user->name }}')">
                                <i class="bi bi-trash"></i>
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
    document.addEventListener('DOMContentLoaded', function() {
        const filterOptions = document.querySelectorAll('.filter-opt');
        filterOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const value = this.getAttribute('data-value');
                const labelText = this.innerText;
                const targetInputId = this.getAttribute('data-target');
                const targetLabelId = this.getAttribute('data-label');
                document.getElementById(targetInputId).value = value;
                document.getElementById(targetLabelId).innerText = labelText;
                const parentUl = this.closest('.custom-dropdown-menu');
                parentUl.querySelectorAll('.filter-opt').forEach(opt => opt.classList.remove('active-filter'));
                this.classList.add('active-filter');
            });
        });
    });
</script>
@endpush