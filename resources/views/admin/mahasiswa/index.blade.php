@extends('layouts.admin')

@section('title', 'Kelola Data Mahasiswa')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama */
    .custom-card { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03); background-color: #ffffff; overflow: hidden; }

    /* Header & Toolbar Filter */
    .table-toolbar { padding: 1.5rem; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; background-color: #ffffff; }
    
    .search-box { position: relative; flex-grow: 1; max-width: 350px; }
    .search-box i { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #9ca3af; }
    .search-box input { width: 100%; padding: 0.6rem 1rem 0.6rem 2.8rem; border: 1px solid #e5e7eb; border-radius: 50px; font-size: 0.9rem; transition: all 0.2s; background-color: #f9fafb; }
    .search-box input:focus { outline: none; border-color: #3b82f6; background-color: #ffffff; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }

    /* Custom Dropdown Modern */
    .modern-dropdown-btn { background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 50px; padding: 0.6rem 1.25rem; color: #4b5563; font-size: 0.9rem; font-weight: 500; display: flex; justify-content: space-between; align-items: center; min-width: 160px; transition: all 0.2s; }
    .modern-dropdown-btn:hover, .modern-dropdown-btn:focus, .modern-dropdown-btn.show { background-color: #ffffff; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); color: #1f2937; }
    
    .custom-dropdown-menu { border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 16px; padding: 0.5rem; min-width: 180px; margin-top: 0.5rem !important; max-height: 300px; overflow-y: auto; }
    .custom-dropdown-item { border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.9rem; color: #4b5563; font-weight: 500; transition: all 0.2s; }
    .custom-dropdown-item:hover, .custom-dropdown-item.active-filter { background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; }

    /* Avatar & Profile Box */
    .avatar-circle { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #e5e7eb; padding: 2px; }
    
    /* Styling Tabel Modern */
    .table-modern { margin-bottom: 0; width: 100%; }
    .table-modern th { background-color: #f9fafb; color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
    .table-modern td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f3f4f6; color: #4b5563; }
    .table-modern tbody tr { transition: background-color 0.2s; }
    .table-modern tbody tr:hover { background-color: #f8fafc; }
    
    /* Tombol Aksi */
    .action-btns { display: flex; gap: 0.4rem; justify-content: flex-end; }
    .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: all 0.2s; text-decoration: none; cursor: pointer; }
    
    .btn-detail { background-color: #f0fdf4; color: #16a34a; }
    .btn-detail:hover { background-color: #16a34a; color: #fff; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(22, 163, 74, 0.3); }
    
    .btn-edit { background-color: #eff6ff; color: #3b82f6; }
    .btn-edit:hover { background-color: #3b82f6; color: #fff; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3); }
    
    .btn-delete { background-color: #fef2f2; color: #ef4444; }
    .btn-delete:hover { background-color: #ef4444; color: #fff; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3); }

    /* Empty State */
    .empty-state { padding: 4rem 1rem; text-align: center; color: #9ca3af; }
    .empty-state i { font-size: 3.5rem; color: #e5e7eb; display: block; margin-bottom: 1rem; }
    .pagination-wrapper { padding: 1.25rem 1.5rem; background-color: #ffffff; border-top: 1px solid #f3f4f6; }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-dark mb-1">Direktori Mahasiswa</h4>
        <p class="text-muted mb-0 small">Pantau data, identitas RFID, dan pencapaian poin mahasiswa.</p>
    </div>
</div>

<div class="card custom-card animate-fade-up" style="animation-delay: 0.1s;">
    
    <form action="{{ route('admin.mahasiswa.index') }}" method="GET" class="table-toolbar">
        
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="search" placeholder="Cari nama, email, NIM, atau RFID..." value="{{ request('search') }}">
        </div>
        
        <input type="hidden" name="level_id" id="filterLevel" value="{{ request('level_id') }}">

        <div class="d-flex flex-wrap gap-2 align-items-center">
            
            <div class="dropdown">
                <button class="btn modern-dropdown-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="textLevel">
                        @php
                            $levelAktif = $levels->firstWhere('id', request('level_id'));
                            echo $levelAktif ? $levelAktif->nama_level : 'Semua Peringkat';
                        @endphp
                    </span>
                </button>
                <ul class="dropdown-menu custom-dropdown-menu">
                    <li><a class="dropdown-item custom-dropdown-item filter-opt {{ request('level_id') == '' ? 'active-filter' : '' }}" href="#" data-target="filterLevel" data-label="textLevel" data-value="">Semua Peringkat</a></li>
                    
                    @foreach($levels as $level)
                    <li>
                        <a class="dropdown-item custom-dropdown-item filter-opt {{ request('level_id') == $level->id ? 'active-filter' : '' }}" href="#" data-target="filterLevel" data-label="textLevel" data-value="{{ $level->id }}">
                            <i class="bi bi-award text-warning me-1"></i> {{ $level->nama_level }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <button type="submit" class="btn btn-dark rounded-pill px-4 fw-medium shadow-sm">
                Terapkan
            </button>

            @if(request()->hasAny(['search', 'level_id']) && (request('search') != '' || request('level_id') != ''))
            <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-light border rounded-pill px-3 text-danger fw-medium" title="Reset Filter">
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
                    <th width="30%">Profil Mahasiswa</th>
                    <th width="20%">Identitas Akademik</th>
                    <th width="15%">Peringkat (Tier)</th>
                    <th width="15%">Saldo Poin</th>
                    <th width="15%" class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswas as $index => $mhs)
                <tr>
                    <td class="text-muted fw-semibold">{{ $mahasiswas->firstItem() + $index }}</td>
                    
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($mhs->name) }}&background=eff6ff&color=1d4ed8&bold=true&rounded=true" alt="Avatar" class="avatar-circle shadow-sm">
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">{{ $mhs->name }}</h6>
                                <small class="text-muted">{{ $mhs->email }}</small>
                            </div>
                        </div>
                    </td>
                    
                    <td>
                        <div class="text-dark fw-bold" style="font-size: 0.95rem;">
                            {{ $mhs->nim ?? 'NIM Kosong' }}
                        </div>
                        <div class="text-muted" style="font-size: 0.75rem; font-family: monospace;">
                            @if($mhs->rfid_uid)
                                <i class="bi bi-upc-scan text-primary me-1"></i>{{ $mhs->rfid_uid }}
                            @else
                                <i class="bi bi-dash-circle me-1"></i>Belum Tap Kartu
                            @endif
                        </div>
                    </td>

                    <td>
                        @if($mhs->level)
                            <span class="badge bg-indigo bg-opacity-10 px-3 py-2 rounded-pill fw-semibold shadow-sm border" style="color: #4338ca; border-color: #c7d2fe !important;">
                                <i class="bi bi-shield-fill-check me-1 text-warning"></i> {{ $mhs->level->nama_level }}
                            </span>
                        @else
                            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill">Unranked</span>
                        @endif
                    </td>

                    <td>
                        <div class="bg-success bg-opacity-10 border border-success border-opacity-25 rounded-pill px-3 py-1 d-inline-flex align-items-center shadow-sm">
                            <i class="bi bi-star-fill text-warning me-2"></i>
                            <span class="fw-bold text-success" style="font-size: 1.05rem;">{{ number_format($mhs->total_poin) }}</span>
                        </div>
                    </td>
                    
                    <td>
                        <div class="action-btns pe-2">
                            <a href="{{ route('admin.mahasiswa.show', $mhs) }}" class="btn-icon btn-detail" title="Detail Riwayat">
                                <i class="bi bi-person-vcard"></i>
                            </a>
                            <a href="{{ route('admin.mahasiswa.edit', $mhs) }}" class="btn-icon btn-edit" title="Edit Data">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button type="button" class="btn-icon btn-delete" title="Hapus Akun" onclick="confirmDelete('{{ route('admin.mahasiswa.destroy', $mhs) }}', '{{ $mhs->name }}')">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                @empty
                <tr>
                    <td colspan="6" class="border-0">
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <div class="fw-bold text-secondary mb-1">Belum Ada Mahasiswa</div>
                            <small>Data pengguna mahasiswa belum terdaftar di sistem.</small>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($mahasiswas->hasPages())
    <div class="pagination-wrapper">
        {{ $mahasiswas->links() }}
    </div>
    @endif
</div>

@include('components.delete-modal')

@endsection

@push('scripts')
<script>
    // Script untuk menangani klik pada Custom Dropdown Filter GET Form
    document.addEventListener('DOMContentLoaded', function() {
        const filterOptions = document.querySelectorAll('.filter-opt');
        
        filterOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                
                const value = this.getAttribute('data-value');
                const labelText = this.innerText.trim();
                const targetInputId = this.getAttribute('data-target');
                const targetLabelId = this.getAttribute('data-label');
                
                // Set nilai hidden input
                document.getElementById(targetInputId).value = value;
                
                // Set teks yang tampil di tombol
                document.getElementById(targetLabelId).innerText = labelText;
                
                // Ganti class aktif
                const parentUl = this.closest('.custom-dropdown-menu');
                parentUl.querySelectorAll('.filter-opt').forEach(opt => opt.classList.remove('active-filter'));
                this.classList.add('active-filter');
            });
        });
    });
</script>
@endpush