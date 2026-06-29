@extends('layouts.admin')

@section('title', 'Kelola Lokasi')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama - Minimalis & Kotak */
    .custom-card { border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }

    /* Header & Toolbar Filter */
    .table-toolbar { padding: 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; background-color: #ffffff; }
    
    .search-box { position: relative; flex-grow: 1; max-width: 350px; }
    .search-box i { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    .search-box input { width: 100%; padding: 0.6rem 1rem 0.6rem 2.8rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; transition: all 0.2s; background-color: #f8fafc; }
    .search-box input:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }

    /* Location Info (Icon Box Soft) */
    .location-info { display: flex; align-items: center; gap: 1rem; }
    .location-icon-box {
        width: 42px; height: 42px; border-radius: 8px; display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; flex-shrink: 0;
        background-color: #ecfdf5; color: #047857; /* Tema Emerald Soft */
    }
    
    .location-details h6 { margin: 0; font-weight: 700; color: #0f172a; font-size: 0.95rem; }
    .location-details small { color: #64748b; font-size: 0.8rem; display: block; margin-top: 2px; }

    /* Badge Koordinat & Bak Sampah */
    .coord-badge { background-color: #f8fafc; color: #475569; font-family: 'Courier New', monospace; font-size: 0.75rem; padding: 4px 8px; border-radius: 6px; border: 1px solid #e2e8f0; }
    .unit-badge { background-color: #ecfdf5; color: #059669; font-weight: 600; padding: 5px 12px; border-radius: 6px; font-size: 0.8rem; border: 1px solid #a7f3d0; }
    .unit-badge-zero { background-color: #fef2f2; color: #dc2626; font-weight: 600; padding: 5px 12px; border-radius: 6px; font-size: 0.8rem; border: 1px solid #fecaca; }

    /* Styling Tabel Clean */
    .table-clean { margin-bottom: 0; width: 100%; }
    .table-clean th { background-color: #ffffff; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 1rem 1.5rem; border-bottom: 2px solid #f1f5f9; }
    .table-clean td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; color: #334155; }
    .table-clean tbody tr { transition: background-color 0.2s; }
    .table-clean tbody tr:hover { background-color: #f8fafc; }
    
    /* Tombol Aksi */
    .action-btns { display: flex; gap: 0.4rem; justify-content: flex-end; }
    .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: none; transition: all 0.2s; text-decoration: none; cursor: pointer; background-color: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }
    .btn-icon:hover { transform: translateY(-2px); }
    .btn-detail:hover { background-color: #ecfdf5; color: #059669; border-color: #a7f3d0; }
    .btn-edit:hover { background-color: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
    .btn-delete:hover { background-color: #fef2f2; color: #ef4444; border-color: #fecaca; }

    /* Empty State & Pagination */
    .empty-state { padding: 4rem 1rem; text-align: center; color: #94a3b8; }
    .empty-state i { font-size: 3.5rem; color: #e2e8f0; display: block; margin-bottom: 1rem; }
    .pagination-wrapper { padding: 1.25rem 1.5rem; background-color: #ffffff; border-top: 1px solid #f1f5f9; }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Master Data Lokasi</h4>
        <p class="text-muted mb-0 small">Kelola titik penempatan bak sampah pintar di area kampus.</p>
    </div>
    <a href="{{ route($routePrefix . '.lokasi.create') }}" class="btn btn-success rounded-3 px-4 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: #10b981; border: none;">
        <i class="bi bi-geo-alt-fill"></i> Tambah Lokasi
    </a>
</div>

<div class="custom-card animate-fade-up" style="animation-delay: 0.1s;">
    
    <div class="table-toolbar">
        <form action="{{ route($routePrefix . '.lokasi.index') }}" method="GET" class="d-flex flex-wrap flex-grow-1 gap-2 align-items-center">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" name="search" placeholder="Cari berdasarkan nama lokasi atau alamat..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-dark rounded-3 px-4 fw-medium shadow-sm" style="background: #0f172a;">
                Cari Lokasi
            </button>
            
            @if(request()->has('search') && request('search') != '')
                <a href="{{ route($routePrefix . '.lokasi.index') }}" class="btn btn-light border rounded-3 px-3 text-danger fw-medium" title="Reset Pencarian">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-clean">
            <thead>
                <tr>
                    <th width="5%" class="ps-4">No</th>
                    <th width="35%">Informasi Lokasi</th>
                    <th width="25%">Titik Koordinat (IoT)</th>
                    <th width="20%" class="text-center">Kapasitas Alat</th>
                    <th width="15%" class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lokasis as $index => $lokasi)
                <tr>
                    <td class="text-muted fw-semibold ps-4">{{ $lokasis->firstItem() + $index }}</td>
                    
                    <td>
                        <div class="location-info">
                            <div class="location-icon-box">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="location-details">
                                <h6>{{ $lokasi->nama_lokasi }}</h6>
                                <small><i class="bi bi-signpost-split text-muted me-1"></i> {{ Str::limit($lokasi->alamat, 60) ?? 'Alamat tidak tersedia' }}</small>
                            </div>
                        </div>
                    </td>
                    
                    <td>
                        @if($lokasi->koordinat)
                            <div class="coord-badge" title="Latitude & Longitude">
                                <i class="bi bi-crosshair me-1"></i>{{ $lokasi->koordinat }}
                            </div>
                        @else
                            <span class="text-muted small fst-italic">Belum di-mapping</span>
                        @endif
                    </td>
                    
                    <td class="text-center">
                        @if($lokasi->bak_sampahs_count > 0)
                            <span class="unit-badge">
                                <i class="bi bi-hdd-stack-fill me-1"></i> {{ $lokasi->bak_sampahs_count }} Unit Aktif
                            </span>
                        @else
                            <span class="unit-badge-zero" title="Belum ada alat IoT terpasang di lokasi ini">
                                <i class="bi bi-exclamation-circle me-1"></i> Kosong
                            </span>
                        @endif
                    </td>
                    
                    <td class="pe-4">
                        <div class="action-btns pe-2">
                            <a href="{{ route($routePrefix . '.lokasi.show', $lokasi) }}" class="btn-icon btn-detail" title="Lihat Detail Area">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="{{ route($routePrefix . '.lokasi.edit', $lokasi) }}" class="btn-icon btn-edit" title="Edit Lokasi">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button type="button" class="btn-icon btn-delete" title="Hapus Lokasi" onclick="confirmDelete('{{ route($routePrefix . '.lokasi.destroy', $lokasi) }}', '{{ $lokasi->nama_lokasi }}')">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                @empty
                <tr>
                    <td colspan="5" class="border-0">
                        <div class="empty-state">
                            <i class="bi bi-map"></i>
                            <div class="fw-bold text-secondary mb-1">Peta Lokasi Masih Kosong</div>
                            <small>Sistem belum mendeteksi adanya titik penempatan bak sampah.</small><br>
                            <a href="{{ route($routePrefix . '.lokasi.create') }}" class="btn btn-sm btn-outline-success rounded-3 mt-3 px-3" style="color: #10b981; border-color: #10b981;">Daftarkan Lokasi Pertama</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($lokasis->hasPages())
    <div class="pagination-wrapper">
        {{ $lokasis->links() }}
    </div>
    @endif
</div>

@include('components.delete-modal')

@endsection