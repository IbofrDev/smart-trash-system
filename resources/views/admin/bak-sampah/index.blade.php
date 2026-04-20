@extends('layouts.admin')

@section('title', 'Kelola Bak Sampah')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama */
    .custom-card { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03); background-color: #ffffff; overflow: hidden; }

    /* Header & Toolbar Filter */
    .table-toolbar { padding: 1.5rem; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; background-color: #ffffff; }
    
    .search-box { position: relative; flex-grow: 1; max-width: 300px; }
    .search-box i { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #9ca3af; }
    .search-box input { width: 100%; padding: 0.6rem 1rem 0.6rem 2.8rem; border: 1px solid #e5e7eb; border-radius: 50px; font-size: 0.9rem; transition: all 0.2s; background-color: #f9fafb; }
    .search-box input:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }

    /* Custom Dropdown Modern */
    .modern-dropdown-btn { background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 50px; padding: 0.6rem 1.25rem; color: #4b5563; font-size: 0.9rem; font-weight: 500; display: flex; justify-content: space-between; align-items: center; min-width: 160px; transition: all 0.2s; }
    .modern-dropdown-btn:hover, .modern-dropdown-btn:focus, .modern-dropdown-btn.show { background-color: #ffffff; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); color: #1f2937; }
    
    .custom-dropdown-menu { border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 16px; padding: 0.5rem; min-width: 180px; margin-top: 0.5rem !important; max-height: 300px; overflow-y: auto; }
    .custom-dropdown-item { border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.9rem; color: #4b5563; font-weight: 500; transition: all 0.2s; }
    .custom-dropdown-item:hover, .custom-dropdown-item.active-filter { background-color: rgba(16, 185, 129, 0.1); color: #10b981; }

    /* Device Icon Styling */
    .device-icon-box {
        width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; color: #ffffff; flex-shrink: 0;
        background: linear-gradient(135deg, #374151, #111827); /* Dark Theme for Device */
        box-shadow: 0 4px 8px rgba(17, 24, 39, 0.2);
    }
    
    /* Styling Tabel Modern */
    .table-modern { margin-bottom: 0; width: 100%; }
    .table-modern th { background-color: #f9fafb; color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
    .table-modern td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f3f4f6; color: #4b5563; }
    .table-modern tbody tr { transition: background-color 0.2s; }
    .table-modern tbody tr:hover { background-color: #f9fafb; }
    
    /* Tombol Aksi */
    .action-btns { display: flex; gap: 0.4rem; }
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
        <h4 class="fw-bold text-dark mb-1">Device Manager</h4>
        <p class="text-muted mb-0 small">Kelola dan pantau seluruh unit bak sampah pintar (IoT).</p>
    </div>
    <a href="{{ route('admin.bak-sampah.create') }}" class="btn btn-success rounded-pill px-4 fw-semibold shadow-sm d-flex align-items-center gap-2">
        <i class="bi bi-hdd-network-fill"></i> Tambah Perangkat
    </a>
</div>

<div class="card custom-card animate-fade-up" style="animation-delay: 0.1s;">
    
    <form action="{{ route('admin.bak-sampah.index') }}" method="GET" class="table-toolbar">
        
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="search" placeholder="Cari nama perangkat..." value="{{ request('search') }}">
        </div>
        
        <input type="hidden" name="lokasi_id" id="filterLokasi" value="{{ request('lokasi_id') }}">
        <input type="hidden" name="status" id="filterStatus" value="{{ request('status') }}">

        <div class="d-flex flex-wrap gap-2 align-items-center">
            
            <div class="dropdown">
                <button class="btn modern-dropdown-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="textLokasi">
                        @php
                            $lokasiAktif = $lokasis->firstWhere('id', request('lokasi_id'));
                            echo $lokasiAktif ? $lokasiAktif->nama_lokasi : 'Semua Lokasi';
                        @endphp
                    </span>
                </button>
                <ul class="dropdown-menu custom-dropdown-menu">
                    <li><a class="dropdown-item custom-dropdown-item filter-opt {{ request('lokasi_id') == '' ? 'active-filter' : '' }}" href="#" data-target="filterLokasi" data-label="textLokasi" data-value="">Semua Lokasi</a></li>
                    
                    @foreach($lokasis as $lokasi)
                    <li>
                        <a class="dropdown-item custom-dropdown-item filter-opt {{ request('lokasi_id') == $lokasi->id ? 'active-filter' : '' }}" href="#" data-target="filterLokasi" data-label="textLokasi" data-value="{{ $lokasi->id }}">
                            {{ $lokasi->nama_lokasi }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            
            <div class="dropdown">
                <button class="btn modern-dropdown-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="textStatus">
                        {{ request('status') == 'aktif' ? 'Aktif' : (request('status') == 'nonaktif' ? 'Nonaktif' : (request('status') == 'maintenance' ? 'Maintenance' : 'Semua Status')) }}
                    </span>
                </button>
                <ul class="dropdown-menu custom-dropdown-menu">
                    <li><a class="dropdown-item custom-dropdown-item filter-opt {{ request('status') == '' ? 'active-filter' : '' }}" href="#" data-target="filterStatus" data-label="textStatus" data-value="">Semua Status</a></li>
                    <li><a class="dropdown-item custom-dropdown-item filter-opt {{ request('status') == 'aktif' ? 'active-filter' : '' }}" href="#" data-target="filterStatus" data-label="textStatus" data-value="aktif">Aktif</a></li>
                    <li><a class="dropdown-item custom-dropdown-item filter-opt {{ request('status') == 'maintenance' ? 'active-filter' : '' }}" href="#" data-target="filterStatus" data-label="textStatus" data-value="maintenance">Maintenance</a></li>
                    <li><a class="dropdown-item custom-dropdown-item filter-opt {{ request('status') == 'nonaktif' ? 'active-filter' : '' }}" href="#" data-target="filterStatus" data-label="textStatus" data-value="nonaktif">Nonaktif</a></li>
                </ul>
            </div>

            <button type="submit" class="btn btn-dark rounded-pill px-4 fw-medium shadow-sm">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>

            @if(request()->hasAny(['search', 'lokasi_id', 'status']))
            <a href="{{ route('admin.bak-sampah.index') }}" class="btn btn-light border rounded-pill px-3 text-danger fw-medium" title="Reset Filter">
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
                    <th width="25%">Perangkat IoT</th>
                    <th width="20%">Lokasi Terpasang</th>
                    <th width="15%">Kapasitas Max</th>
                    <th width="20%">Status Koneksi</th>
                    <th width="15%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bakSampahs as $index => $bak)
                <tr>
                    <td class="text-muted fw-semibold">{{ $bakSampahs->firstItem() + $index }}</td>
                    
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="device-icon-box">
                                <i class="bi bi-router-fill"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">{{ $bak->nama }}</h6>
                                <small class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-upc-scan me-1"></i>NodeMCU/ESP32</small>
                            </div>
                        </div>
                    </td>
                    
                    <td>
                        @if($bak->lokasi)
                            <div class="text-dark fw-medium"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $bak->lokasi->nama_lokasi }}</div>
                        @else
                            <span class="text-muted fst-italic small">Belum diatur</span>
                        @endif
                    </td>

                    <td>
                        <div class="bg-light border rounded-3 px-3 py-1 d-inline-block">
                            <span class="fw-bold text-dark">{{ $bak->kapasitas_max ? number_format($bak->kapasitas_max, 1) : '0' }}</span> <small class="text-muted">kg</small>
                        </div>
                    </td>
                    
                    <td>
                        @if($bak->status === 'aktif')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill d-inline-flex align-items-center">
                                <span class="spinner-grow spinner-grow-sm text-success me-2" style="width: 10px; height: 10px;" role="status"></span> Online
                            </span>
                        @elseif($bak->status === 'maintenance')
                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill d-inline-flex align-items-center">
                                <i class="bi bi-tools me-2"></i> Maintenance
                            </span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill d-inline-flex align-items-center">
                                <i class="bi bi-power me-2"></i> Offline
                            </span>
                        @endif
                    </td>
                    
                    <td>
                        <div class="action-btns justify-content-center">
                            <a href="{{ route('admin.bak-sampah.show', $bak) }}" class="btn-icon btn-detail" title="Lihat Detail & Monitor">
                                <i class="bi bi-display"></i>
                            </a>
                            <a href="{{ route('admin.bak-sampah.edit', $bak) }}" class="btn-icon btn-edit" title="Edit Perangkat">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button type="button" class="btn-icon btn-delete" title="Hapus Perangkat" onclick="confirmDelete('{{ route('admin.bak-sampah.destroy', $bak) }}', '{{ $bak->nama }}')">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                @empty
                <tr>
                    <td colspan="6" class="border-0">
                        <div class="empty-state">
                            <i class="bi bi-hdd-network"></i>
                            <div class="fw-bold text-secondary mb-1">Belum Ada Perangkat IoT</div>
                            <small>Data bak sampah pintar masih kosong. Daftarkan perangkat pertama Anda.</small>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($bakSampahs->hasPages())
    <div class="pagination-wrapper">
        {{ $bakSampahs->links() }}
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