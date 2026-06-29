@extends('layouts.admin')

@section('title', 'Kelola Level Gamifikasi')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama - Radius Dikurangi */
    .custom-card { border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }

    /* Header & Toolbar */
    .table-toolbar { padding: 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background-color: #ffffff; }

    /* Rank Badge Styling - Emerald Theme */
    .rank-circle {
        width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1rem; color: #ffffff; flex-shrink: 0;
        background-color: #10b981; 
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
        border: 2px solid #ecfdf5;
    }
    
    /* Styling Tabel Clean */
    .table-clean { margin-bottom: 0; width: 100%; }
    .table-clean th { background-color: #ffffff; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 1rem 1.5rem; border-bottom: 2px solid #f1f5f9; }
    .table-clean td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; color: #334155; }
    .table-clean tbody tr { transition: background-color 0.2s; }
    .table-clean tbody tr:hover { background-color: #f8fafc; }
    
    /* Custom Progress Bar - Emerald Theme */
    .progress-wrapper { display: flex; align-items: center; gap: 10px; }
    .progress-custom { height: 8px; width: 120px; background-color: #f1f5f9; border-radius: 4px; overflow: hidden; }
    .progress-bar-custom { height: 100%; background-color: #10b981; border-radius: 4px; }
    
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
        <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Tier & Level Gamifikasi</h4>
        <p class="text-muted mb-0 small">Tentukan jenjang peringkat mahasiswa berdasarkan akumulasi poin.</p>
    </div>
    <a href="{{ route($routePrefix . '.level.create') }}" class="btn btn-success rounded-3 px-4 fw-bold shadow-sm d-flex align-items-center gap-2" style="background-color: #10b981; border: none;">
        <i class="bi bi-plus-lg"></i> Tambah Peringkat
    </a>
</div>

<div class="custom-card animate-fade-up" style="animation-delay: 0.1s;">
    
    <div class="table-toolbar">
        <h6 class="m-0 fw-bold" style="color: #0f172a;"><i class="bi bi-award-fill text-success me-2"></i>Daftar Level Tersedia</h6>
    </div>

    <div class="table-responsive">
        <table class="table table-clean">
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
                        <div class="fw-bold" style="color: #0f172a; font-size: 1.05rem;">
                            {{ $level->nama_level }}
                        </div>
                        <small class="text-muted"><i class="bi bi-stars text-warning"></i> Tier {{ $level->urutan }}</small>
                    </td>
                    
                    <td>
                        <div class="d-flex align-items-center bg-light border rounded-2 px-3 py-1 d-inline-flex" style="background-color: #f8fafc !important;">
                            <span class="fw-bold" style="color: #0f172a;">{{ number_format($level->min_poin) }}</span>
                            <i class="bi bi-arrow-right-short text-muted mx-1"></i>
                            <span class="fw-bold" style="color: #0f172a;">{{ number_format($level->max_poin) }}</span>
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
                        <span class="badge" style="background-color: #f8fafc; color: #475569; border: 1px solid #e2e8f0; padding: 0.4rem 0.8rem; border-radius: 6px;">
                            <i class="bi bi-people-fill me-1 text-success"></i> {{ number_format($level->mahasiswas_count ?? 0) }}
                        </span>
                    </td>
                    
                    <td class="pe-4">
                        <div class="action-btns">
                            <a href="{{ route($routePrefix . '.level.edit', $level) }}" class="btn-icon btn-edit" title="Edit Level">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button type="button" class="btn-icon btn-delete" title="Hapus Level" onclick="confirmDelete('{{ route($routePrefix . '.level.destroy', $level) }}', '{{ $level->nama_level }}')">
                                <i class="bi bi-trash3"></i>
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
                            <a href="{{ route($routePrefix . '.level.create') }}" class="btn btn-sm rounded-3 mt-3 px-3 text-white" style="background-color: #10b981;">Buat Peringkat Pertama</a>
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