@extends('layouts.admin')

@section('title', 'Kelola Level Gamifikasi')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama */
    .custom-card { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03); background-color: #ffffff; overflow: hidden; }

    /* Header & Toolbar */
    .table-toolbar { padding: 1.5rem; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; background-color: #ffffff; }

    /* Rank Badge Styling (Lingkaran Urutan) */
    .rank-circle {
        width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1rem; color: #ffffff; flex-shrink: 0;
        background: linear-gradient(135deg, #818cf8, #4f46e5); /* Indigo Gradient */
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25);
        border: 2px solid #e0e7ff;
    }
    
    /* Styling Tabel Modern */
    .table-modern { margin-bottom: 0; width: 100%; }
    .table-modern th { background-color: #f9fafb; color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
    .table-modern td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f3f4f6; color: #4b5563; }
    .table-modern tbody tr { transition: background-color 0.2s; }
    .table-modern tbody tr:hover { background-color: #f9fafb; }
    
    /* Custom Progress Bar */
    .progress-wrapper { display: flex; align-items: center; gap: 10px; }
    .progress-custom { height: 8px; width: 120px; background-color: #e5e7eb; border-radius: 50px; overflow: hidden; }
    .progress-bar-custom { height: 100%; background: linear-gradient(90deg, #6366f1, #8b5cf6); border-radius: 50px; }
    
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
        <h4 class="fw-bold text-dark mb-1">Tier & Level Gamifikasi</h4>
        <p class="text-muted mb-0 small">Tentukan jenjang peringkat mahasiswa berdasarkan akumulasi poin.</p>
    </div>
    <a href="{{ route('admin.level.create') }}" class="btn btn-success rounded-pill px-4 fw-semibold shadow-sm d-flex align-items-center gap-2" style="background-color: #4f46e5; border-color: #4f46e5;">
        <i class="bi bi-trophy-fill"></i> Tambah Peringkat
    </a>
</div>

<div class="card custom-card animate-fade-up" style="animation-delay: 0.1s;">
    
    <div class="table-toolbar">
        <h6 class="m-0 fw-bold text-dark"><i class="bi bi-award-fill text-warning me-2"></i>Daftar Level Tersedia</h6>
    </div>

    <div class="table-responsive">
        <table class="table table-modern">
            <thead>
                <tr>
                    <th width="10%" class="text-center">Urutan</th>
                    <th width="25%">Nama Peringkat (Tier)</th>
                    <th width="25%">Syarat Poin (Min - Max)</th>
                    <th width="20%">Rasio Poin Tertinggi</th>
                    <th width="10%" class="text-center">Populasi</th>
                    <th width="10%" class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($levels as $level)
                <tr>
                    <td class="text-center">
                        <div class="d-flex justify-content-center">
                            <div class="rank-circle">
                                {{ $level->urutan }}
                            </div>
                        </div>
                    </td>
                    
                    <td>
                        <div class="fw-bold text-dark" style="font-size: 1.05rem;">
                            {{ $level->nama_level }}
                        </div>
                        <small class="text-muted"><i class="bi bi-stars text-warning"></i> Tier {{ $level->urutan }}</small>
                    </td>
                    
                    <td>
                        <div class="d-flex align-items-center bg-light border rounded-pill px-3 py-1 d-inline-flex">
                            <span class="fw-bold text-dark">{{ number_format($level->min_poin) }}</span>
                            <i class="bi bi-arrow-right-short text-muted mx-1"></i>
                            <span class="fw-bold text-dark">{{ number_format($level->max_poin) }}</span>
                            <small class="text-muted ms-1">pts</small>
                        </div>
                    </td>

                    <td>
                        @php
                            $maxPoin = $levels->max('max_poin');
                            $width = $maxPoin > 0 ? ($level->max_poin / $maxPoin) * 100 : 0;
                        @endphp
                        <div class="progress-wrapper" title="{{ number_format($width, 1) }}% dari poin tertinggi">
                            <div class="progress-custom">
                                <div class="progress-bar-custom" style="width: {{ $width }}%"></div>
                            </div>
                            <small class="text-muted fw-bold">{{ number_format($width, 0) }}%</small>
                        </div>
                    </td>
                    
                    <td class="text-center">
                        <span class="badge" style="background-color: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; padding: 0.4rem 0.8rem; border-radius: 8px;">
                            <i class="bi bi-people-fill me-1"></i> {{ number_format($level->mahasiswas_count ?? 0) }}
                        </span>
                    </td>
                    
                    <td>
                        <div class="action-btns pe-2">
                            <a href="{{ route('admin.level.edit', $level) }}" class="btn-icon btn-edit" title="Edit Level">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button type="button" class="btn-icon btn-delete" title="Hapus Level" onclick="confirmDelete('{{ route('admin.level.destroy', $level) }}', '{{ $level->nama_level }}')">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                @empty
                <tr>
                    <td colspan="6" class="border-0">
                        <div class="empty-state">
                            <i class="bi bi-trophy"></i>
                            <div class="fw-bold text-secondary mb-1">Data Level Kosong</div>
                            <small>Sistem belum memiliki referensi peringkat gamifikasi mahasiswa.</small><br>
                            <a href="{{ route('admin.level.create') }}" class="btn btn-sm rounded-pill mt-3 px-3 text-white" style="background-color: #4f46e5;">Buat Peringkat Pertama</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@include('components.delete-modal')

@endsection