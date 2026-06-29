@extends('layouts.admin')

@section('title', 'Kelola Jenis Sampah')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama - Minimalis & Kotak */
    .custom-card { border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }

    /* Header & Toolbar */
    .table-toolbar { padding: 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background-color: #ffffff; }

    /* Category Icon Box - Diseragamkan menjadi Soft Emerald */
    .category-icon-box {
        width: 42px; height: 42px; border-radius: 8px; display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; flex-shrink: 0; transition: transform 0.2s;
        background-color: #ecfdf5; color: #047857;
    }
    tr:hover .category-icon-box { transform: scale(1.05); background-color: #d1fae5; }

    /* Styling Tabel Clean */
    .table-clean { margin-bottom: 0; width: 100%; }
    .table-clean th { background-color: #ffffff; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 1rem 1.5rem; border-bottom: 2px solid #f1f5f9; }
    .table-clean td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; color: #334155; }
    .table-clean tbody tr { transition: background-color 0.2s; }
    .table-clean tbody tr:hover { background-color: #f8fafc; }
    
    /* Tombol Aksi */
    .action-btns { display: flex; gap: 0.4rem; justify-content: flex-end; }
    .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid #e2e8f0; background-color: #f8fafc; color: #64748b; transition: all 0.2s; text-decoration: none; cursor: pointer; }
    .btn-icon:hover { transform: translateY(-2px); }
    .btn-edit:hover { background-color: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
    .btn-delete:hover { background-color: #fef2f2; color: #ef4444; border-color: #fecaca; }

    /* Empty State */
    .empty-state { padding: 4rem 1rem; text-align: center; color: #94a3b8; }
    .empty-state i { font-size: 3.5rem; color: #e2e8f0; display: block; margin-bottom: 1rem; }
    .pagination-wrapper { padding: 1.25rem 1.5rem; background-color: #ffffff; border-top: 1px solid #f1f5f9; }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Katalog Jenis Sampah</h4>
        <p class="text-muted mb-0 small">Kelola kategori sampah beserta nilai konversi poinnya.</p>
    </div>
    <a href="{{ route($routePrefix . '.jenis-sampah.create') }}" class="btn btn-success rounded-3 px-4 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: #10b981; border: none;">
        <i class="bi bi-plus-lg"></i> Tambah Kategori
    </a>
</div>

<div class="custom-card animate-fade-up" style="animation-delay: 0.1s;">
    
    <div class="table-toolbar">
        <h6 class="m-0 fw-bold" style="color: #0f172a;"><i class="bi bi-list-ul text-success me-2"></i>Daftar Kategori Aktif</h6>
    </div>

    <div class="table-responsive">
        <table class="table table-clean">
            <thead>
                <tr>
                    <th width="5%" class="ps-4">No</th>
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
                    <td class="text-muted fw-semibold ps-4">{{ $jenisSampahs->firstItem() + $index }}</td>
                    
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="category-icon-box">
                                <i class="bi bi-recycle"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold" style="color: #0f172a;">{{ $jenis->nama }}</h6>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    {{ $jenis->deskripsi ? Str::limit($jenis->deskripsi, 45) : 'Tidak ada deskripsi' }}
                                </small>
                            </div>
                        </div>
                    </td>
                    
                    <td>
                        <span class="badge" style="background: #f8fafc; color: #0f172a; border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 6px; display: inline-flex; align-items: center;">
                            <i class="bi bi-star-fill text-warning me-2" style="font-size: 0.8rem;"></i>
                            {{ number_format($jenis->poin_per_kg) }} <small class="ms-1 fw-normal text-muted">pts / {{ strtolower($jenis->satuan) }}</small>
                        </span>
                    </td>

                    <td>
                        <div class="fw-bold" style="color: #0f172a;">
                            {{ number_format($jenis->transaksi_sampah_count) }} 
                            <small class="text-muted fw-medium ms-1">setoran</small>
                        </div>
                    </td>
                    
                    <td>
                        @if($jenis->is_active)
                            <span class="badge" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; padding: 6px 12px; border-radius: 6px;"><i class="bi bi-check-circle me-1"></i> Aktif</span>
                        @else
                            <span class="badge" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 12px; border-radius: 6px;"><i class="bi bi-x-circle me-1"></i> Nonaktif</span>
                        @endif
                    </td>
                    
                    <td class="pe-4">
                        <div class="action-btns pe-2">
                            <a href="{{ route($routePrefix . '.jenis-sampah.edit', $jenis) }}" class="btn-icon btn-edit" title="Edit Kategori">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button type="button" class="btn-icon btn-delete" title="Hapus Kategori" onclick="confirmDelete('{{ route($routePrefix . '.jenis-sampah.destroy', $jenis) }}', '{{ $jenis->nama }}')">
                                <i class="bi bi-trash3"></i>
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
                            <a href="{{ route($routePrefix . '.jenis-sampah.create') }}" class="btn btn-sm btn-outline-success rounded-3 mt-3 px-3" style="color: #10b981; border-color: #10b981;">Tambah Kategori Baru</a>
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