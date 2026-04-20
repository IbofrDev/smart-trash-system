@extends('layouts.admin')

@section('title', 'Kelola Lokasi')

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
    .search-box input:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }

    /* Location Info (Avatar Peta) */
    .location-info { display: flex; align-items: center; gap: 1rem; }
    .location-icon-box {
        width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; color: #ffffff; flex-shrink: 0;
        background: linear-gradient(135deg, #14b8a6, #0d9488); /* Warna Teal/Tosca Peta */
        box-shadow: 0 4px 10px rgba(13, 148, 136, 0.2);
    }
    
    .location-details h6 { margin: 0; font-weight: 700; color: #1f2937; font-size: 0.95rem; }
    .location-details small { color: #6b7280; font-size: 0.8rem; display: block; margin-top: 2px; }

    /* Badge Koordinat & Bak Sampah */
    .coord-badge { background-color: #f3f4f6; color: #4b5563; font-family: 'Courier New', monospace; font-size: 0.75rem; padding: 4px 8px; border-radius: 6px; border: 1px solid #e5e7eb; }
    .unit-badge { background-color: #eff6ff; color: #2563eb; font-weight: 600; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; border: 1px solid #bfdbfe; }
    .unit-badge-zero { background-color: #fef2f2; color: #ef4444; font-weight: 600; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; border: 1px solid #fecaca; }

    /* Styling Tabel Modern */
    .table-modern { margin-bottom: 0; width: 100%; }
    .table-modern th { background-color: #f9fafb; color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
    .table-modern td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f3f4f6; color: #4b5563; }
    .table-modern tbody tr { transition: background-color 0.2s; }
    .table-modern tbody tr:hover { background-color: #f9fafb; }
    
    /* Tombol Aksi */
    .action-btns { display: flex; gap: 0.4rem; justify-content: flex-end; }
    .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: all 0.2s; text-decoration: none; cursor: pointer; }
    
    .btn-detail { background-color: #f0fdf4; color: #16a34a; } /* Hijau terang */
    .btn-detail:hover { background-color: #16a34a; color: #fff; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(22, 163, 74, 0.3); }
    
    .btn-edit { background-color: #eff6ff; color: #3b82f6; } /* Biru */
    .btn-edit:hover { background-color: #3b82f6; color: #fff; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3); }
    
    .btn-delete { background-color: #fef2f2; color: #ef4444; } /* Merah */
    .btn-delete:hover { background-color: #ef4444; color: #fff; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3); }

    /* Empty State & Pagination */
    .empty-state { padding: 4rem 1rem; text-align: center; color: #9ca3af; }
    .empty-state i { font-size: 3.5rem; color: #e5e7eb; display: block; margin-bottom: 1rem; }
    .pagination-wrapper { padding: 1.25rem 1.5rem; background-color: #ffffff; border-top: 1px solid #f3f4f6; }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-dark mb-1">Master Data Lokasi</h4>
        <p class="text-muted mb-0 small">Kelola titik penempatan bak sampah pintar di area kampus.</p>
    </div>
    <a href="{{ route('admin.lokasi.create') }}" class="btn btn-success rounded-pill px-4 fw-semibold shadow-sm d-flex align-items-center gap-2">
        <i class="bi bi-geo-alt-fill"></i> Tambah Lokasi
    </a>
</div>

<div class="card custom-card animate-fade-up" style="animation-delay: 0.1s;">
    
    <div class="table-toolbar">
        <form action="{{ route('admin.lokasi.index') }}" method="GET" class="d-flex flex-wrap flex-grow-1 gap-2 align-items-center">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" name="search" placeholder="Cari berdasarkan nama lokasi atau alamat..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-dark rounded-pill px-4 fw-medium shadow-sm">
                Cari Lokasi
            </button>
            
            @if(request()->has('search') && request('search') != '')
                <a href="{{ route('admin.lokasi.index') }}" class="btn btn-light border rounded-pill px-3 text-danger fw-medium" title="Reset Pencarian">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-modern">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="35%">Informasi Lokasi</th>
                    <th width="25%">Titik Koordinat (IoT)</th>
                    <th width="20%" class="text-center">Kapasitas Alat</th>
                    <th width="15%" class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lokasis as $index => $lokasi)
                <tr>
                    <td class="text-muted fw-semibold">{{ $lokasis->firstItem() + $index }}</td>
                    
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
                    
                    <td>
                        <div class="action-btns pe-2">
                            <a href="{{ route('admin.lokasi.show', $lokasi) }}" class="btn-icon btn-detail" title="Lihat Detail Area">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="{{ route('admin.lokasi.edit', $lokasi) }}" class="btn-icon btn-edit" title="Edit Lokasi">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button type="button" class="btn-icon btn-delete" title="Hapus Lokasi" onclick="confirmDelete('{{ route('admin.lokasi.destroy', $lokasi) }}', '{{ $lokasi->nama_lokasi }}')">
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
                            <a href="{{ route('admin.lokasi.create') }}" class="btn btn-sm btn-outline-success rounded-pill mt-3 px-3">Daftarkan Lokasi Pertama</a>
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