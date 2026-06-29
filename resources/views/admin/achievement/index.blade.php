@extends('layouts.admin')

@section('title', 'Kelola Achievement')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama - Radius Dikurangi */
    .custom-card { border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }

    /* Header & Toolbar */
    .table-toolbar { padding: 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background-color: #ffffff; }

    /* Achievement Icon Box - Diseragamkan menjadi Soft Emerald */
    .achieve-icon-box {
        width: 46px; height: 46px; border-radius: 10px; display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; flex-shrink: 0; transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background-color: #ecfdf5; color: #047857; /* Emerald Theme */
    }
    tr:hover .achieve-icon-box { transform: scale(1.1) rotate(5deg); background-color: #d1fae5; }

    /* Custom Badge Syarat (Missions) - Soft Colors */
    .badge-mission { padding: 0.4rem 0.8rem; border-radius: 6px; font-weight: 600; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.3rem; border: 1px solid #e2e8f0; background-color: #f8fafc; color: #475569; }
    
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
    .empty-state i { font-size: 4rem; color: #e2e8f0; display: block; margin-bottom: 1rem; }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Misi & Achievement</h4>
        <p class="text-muted mb-0 small">Desain tantangan dan pencapaian untuk memotivasi mahasiswa.</p>
    </div>
    <a href="{{ route($routePrefix . '.achievement.create') }}" class="btn btn-success rounded-3 px-4 fw-bold shadow-sm d-flex align-items-center gap-2" style="background-color: #10b981; border: none;">
        <i class="bi bi-award-fill"></i> Buat Achievement
    </a>
</div>

<div class="custom-card animate-fade-up" style="animation-delay: 0.1s;">
    
    <div class="table-toolbar">
        <h6 class="m-0 fw-bold" style="color: #0f172a;"><i class="bi bi-controller text-success me-2"></i>Daftar Prestasi (Unlocked List)</h6>
    </div>

    <div class="table-responsive">
        <table class="table table-clean">
            <thead>
                <tr>
                    <th width="5%" class="ps-4">No</th>
                    <th width="30%">Detail Pencapaian</th>
                    <th width="20%">Syarat (Mission)</th>
                    <th width="20%">Hadiah Bonus</th>
                    <th width="15%" class="text-center">Diraih Oleh</th>
                    <th width="10%" class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($achievements as $index => $achievement)
                <tr>
                    <td class="text-muted fw-semibold ps-4">{{ $index + 1 }}</td>
                    
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="achieve-icon-box">
                                @if($achievement->icon)
                                    <i class="{{ $achievement->icon }}"></i>
                                @else
                                    <i class="bi bi-trophy-fill"></i>
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold" style="color: #0f172a; font-size: 0.95rem;">{{ $achievement->nama }}</h6>
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem; line-height: 1.3;">
                                    {{ Str::limit($achievement->deskripsi, 55) }}
                                </small>
                            </div>
                        </div>
                    </td>
                    
                    <td>
                        @switch($achievement->syarat_type)
                            @case('total_kg')
                                <div class="badge-mission" title="Syarat Berat Sampah">
                                    <i class="bi bi-speedometer text-success"></i> Akumulasi {{ $achievement->syarat_value }} kg
                                </div>
                                @break
                            @case('streak')
                                <div class="badge-mission" title="Syarat Konsistensi Hari">
                                    <i class="bi bi-fire text-success"></i> Runtun {{ $achievement->syarat_value }} Hari
                                </div>
                                @break
                            @case('transaksi_count')
                                <div class="badge-mission" title="Syarat Jumlah Transaksi">
                                    <i class="bi bi-arrow-repeat text-success"></i> {{ $achievement->syarat_value }}x Setoran
                                </div>
                                @break
                            @case('first_time')
                                <div class="badge-mission" title="Aksi Pertama Kali">
                                    <i class="bi bi-stars text-success"></i> Misi Pertama Kali
                                </div>
                                @break
                            @default
                                <div class="badge-mission"><i class="bi bi-question-circle"></i> Tidak Diketahui</div>
                        @endswitch
                    </td>

                    <td>
                        <span class="badge" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; padding: 6px 12px; border-radius: 6px; font-weight: 700;">
                            <i class="bi bi-coin text-warning me-1"></i> +{{ number_format($achievement->poin_bonus) }} <small class="fw-normal text-muted">pts</small>
                        </span>
                    </td>
                    
                    <td class="text-center">
                        <div class="d-inline-flex align-items-center justify-content-center" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 4px 12px;" title="{{ $achievement->mahasiswas_count }} mahasiswa telah meraih ini">
                            <i class="bi bi-people-fill text-muted me-2"></i>
                            <span class="fw-bold" style="color: #0f172a;">{{ number_format($achievement->mahasiswas_count) }}</span>
                        </div>
                    </td>
                    
                    <td class="pe-4">
                        <div class="action-btns">
                            <a href="{{ route($routePrefix . '.achievement.edit', $achievement) }}" class="btn-icon btn-edit" title="Edit Pencapaian">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button type="button" class="btn-icon btn-delete" title="Hapus Pencapaian" onclick="confirmDelete('{{ route($routePrefix . '.achievement.destroy', $achievement) }}', '{{ $achievement->nama }}')">
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
                            <div class="fw-bold text-secondary mb-1">Belum Ada Achievement</div>
                            <small>Sistem gamifikasi belum memiliki daftar pencapaian untuk diraih mahasiswa.</small><br>
                            <a href="{{ route($routePrefix . '.achievement.create') }}" class="btn btn-sm btn-outline-success rounded-3 mt-3 px-4" style="color: #10b981; border-color: #10b981;">Buat Misi Pertama</a>
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