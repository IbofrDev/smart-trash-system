@extends('layouts.admin')

@section('title', 'Kelola Jenis Sampah')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama */
    .custom-card { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03); background-color: #ffffff; overflow: hidden; }

    /* Header & Toolbar (Opsional Search) */
    .table-toolbar { padding: 1.5rem; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; background-color: #ffffff; }

    /* Category Icon Box */
    .category-icon-box {
        width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; flex-shrink: 0; transition: transform 0.2s;
    }
    
    /* Warna acak untuk ikon kategori berdasarkan urutan baris */
    tr:nth-child(4n+1) .category-icon-box { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #2563eb; } /* Biru */
    tr:nth-child(4n+2) .category-icon-box { background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #16a34a; } /* Hijau */
    tr:nth-child(4n+3) .category-icon-box { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; } /* Oranye */
    tr:nth-child(4n+4) .category-icon-box { background: linear-gradient(135deg, #f3e8ff, #e9d5ff); color: #9333ea; } /* Ungu */

    tr:hover .category-icon-box { transform: scale(1.1) rotate(5deg); }

    /* Styling Tabel Modern */
    .table-modern { margin-bottom: 0; width: 100%; }
    .table-modern th { background-color: #f9fafb; color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
    .table-modern td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f3f4f6; color: #4b5563; }
    .table-modern tbody tr { transition: background-color 0.2s; }
    .table-modern tbody tr:hover { background-color: #f9fafb; }
    
    /* Tombol Aksi */
    .action-btns { display: flex; gap: 0.4rem; justify-content: flex-end; }
    .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: all 0.2s; text-decoration: none; cursor: pointer; }
    
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
        <h4 class="fw-bold text-dark mb-1">Katalog Jenis Sampah</h4>
        <p class="text-muted mb-0 small">Kelola kategori sampah beserta nilai konversi poinnya.</p>
    </div>
    <a href="{{ route('admin.jenis-sampah.create') }}" class="btn btn-success rounded-pill px-4 fw-semibold shadow-sm d-flex align-items-center gap-2">
        <i class="bi bi-tags-fill"></i> Tambah Kategori
    </a>
</div>

<div class="card custom-card animate-fade-up" style="animation-delay: 0.1s;">
    
    <div class="table-toolbar">
        <h6 class="m-0 fw-bold text-dark"><i class="bi bi-list-ul me-2"></i>Daftar Kategori Aktif</h6>
        </div>

    <div class="table-responsive">
        <table class="table table-modern">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="35%">Kategori Sampah</th>
                    <th width="20%">Nilai Konversi</th>
                    <th width="15%">Popularitas</th>
                    <th width="15%">Status</th>
                    <th width="10%" class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jenisSampahs as $index => $jenis)
                <tr>
                    <td class="text-muted fw-semibold">{{ $jenisSampahs->firstItem() + $index }}</td>
                    
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="category-icon-box">
                                <i class="bi bi-recycle"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">{{ $jenis->nama }}</h6>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    {{ $jenis->deskripsi ? Str::limit($jenis->deskripsi, 45) : 'Tidak ada deskripsi' }}
                                </small>
                            </div>
                        </div>
                    </td>
                    
                    <td>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fs-6 d-inline-flex align-items-center">
                            <i class="bi bi-star-fill text-warning me-2" style="font-size: 0.8rem;"></i>
                            {{ number_format($jenis->poin_per_kg) }} <small class="ms-1 fw-normal text-muted">pts / {{ strtolower($jenis->satuan) }}</small>
                        </span>
                    </td>

                    <td>
                        <div class="text-dark fw-bold">
                            {{ number_format($jenis->transaksi_sampah_count) }} 
                            <small class="text-muted fw-normal ms-1">setoran</small>
                        </div>
                    </td>
                    
                    <td>
                        @if($jenis->is_active)
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Aktif</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-1 rounded-pill fw-semibold"><i class="bi bi-x-circle-fill me-1"></i> Nonaktif</span>
                        @endif
                    </td>
                    
                    <td>
                        <div class="action-btns pe-2">
                            <a href="{{ route('admin.jenis-sampah.edit', $jenis) }}" class="btn-icon btn-edit" title="Edit Kategori">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button type="button" class="btn-icon btn-delete" title="Hapus Kategori" onclick="confirmDelete('{{ route('admin.jenis-sampah.destroy', $jenis) }}', '{{ $jenis->nama }}')">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                @empty
                <tr>
                    <td colspan="6" class="border-0">
                        <div class="empty-state">
                            <i class="bi bi-tags"></i>
                            <div class="fw-bold text-secondary mb-1">Katalog Sampah Kosong</div>
                            <small>Sistem belum memiliki referensi harga dan jenis sampah.</small><br>
                            <a href="{{ route('admin.jenis-sampah.create') }}" class="btn btn-sm btn-outline-success rounded-pill mt-3 px-3">Tambah Kategori Baru</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($jenisSampahs->hasPages())
    <div class="pagination-wrapper">
        {{ $jenisSampahs->links() }}
    </div>
    @endif
</div>

@include('components.delete-modal')

@endsection