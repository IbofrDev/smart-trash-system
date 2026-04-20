@extends('layouts.admin')

@section('title', 'Kelola Achievement')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama */
    .custom-card { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03); background-color: #ffffff; overflow: hidden; }

    /* Header & Toolbar */
    .table-toolbar { padding: 1.5rem; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; background-color: #ffffff; }

    /* Achievement Icon Box (Warna Acak Dinamis) */
    .achieve-icon-box {
        width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; flex-shrink: 0; transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    tr:hover .achieve-icon-box { transform: scale(1.15) rotate(5deg); }
    
    /* Kombinasi Gradasi Mewah untuk Ikon */
    tr:nth-child(4n+1) .achieve-icon-box { background: linear-gradient(135deg, #fef08a, #f59e0b); color: #fff; text-shadow: 1px 1px 2px rgba(154, 52, 18, 0.5); } 
    tr:nth-child(4n+2) .achieve-icon-box { background: linear-gradient(135deg, #a7f3d0, #10b981); color: #fff; text-shadow: 1px 1px 2px rgba(6, 78, 59, 0.5); } 
    tr:nth-child(4n+3) .achieve-icon-box { background: linear-gradient(135deg, #bfdbfe, #3b82f6); color: #fff; text-shadow: 1px 1px 2px rgba(30, 58, 138, 0.5); } 
    tr:nth-child(4n+4) .achieve-icon-box { background: linear-gradient(135deg, #e9d5ff, #8b5cf6); color: #fff; text-shadow: 1px 1px 2px rgba(76, 29, 149, 0.5); } 

    /* Custom Badge Syarat (Missions) */
    .badge-mission { padding: 0.4rem 0.8rem; border-radius: 8px; font-weight: 600; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.3rem; border: 1px solid transparent; }
    .mission-kg { background-color: #f0fdfa; color: #0d9488; border-color: #ccfbf1; }
    .mission-streak { background-color: #fff7ed; color: #ea580c; border-color: #ffedd5; }
    .mission-trx { background-color: #eff6ff; color: #2563eb; border-color: #dbeafe; }
    .mission-first { background-color: #fdf4ff; color: #c026d3; border-color: #fae8ff; }

    /* Styling Tabel Modern */
    .table-modern { margin-bottom: 0; width: 100%; }
    .table-modern th { background-color: #f9fafb; color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
    .table-modern td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f3f4f6; color: #4b5563; }
    .table-modern tbody tr { transition: background-color 0.2s; }
    .table-modern tbody tr:hover { background-color: #f8fafc; }
    
    /* Tombol Aksi */
    .action-btns { display: flex; gap: 0.4rem; justify-content: flex-end; }
    .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: all 0.2s; text-decoration: none; cursor: pointer; }
    
    .btn-edit { background-color: #eff6ff; color: #3b82f6; }
    .btn-edit:hover { background-color: #3b82f6; color: #fff; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3); }
    
    .btn-delete { background-color: #fef2f2; color: #ef4444; }
    .btn-delete:hover { background-color: #ef4444; color: #fff; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3); }

    /* Empty State */
    .empty-state { padding: 4rem 1rem; text-align: center; color: #9ca3af; }
    .empty-state i { font-size: 4rem; color: #e5e7eb; display: block; margin-bottom: 1rem; }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-dark mb-1">Misi & Achievement</h4>
        <p class="text-muted mb-0 small">Desain tantangan dan pencapaian untuk memotivasi mahasiswa.</p>
    </div>
    <a href="{{ route('admin.achievement.create') }}" class="btn rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-2 text-dark" style="background: linear-gradient(135deg, #fde047, #f59e0b); border: none;">
        <i class="bi bi-award-fill"></i> Buat Achievement
    </a>
</div>

<div class="card custom-card animate-fade-up" style="animation-delay: 0.1s;">
    
    <div class="table-toolbar">
        <h6 class="m-0 fw-bold text-dark"><i class="bi bi-controller text-primary me-2"></i>Daftar Prestasi (Unlocked List)</h6>
    </div>

    <div class="table-responsive">
        <table class="table table-modern">
            <thead>
                <tr>
                    <th width="5%">#</th>
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
                    <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                    
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
                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">{{ $achievement->nama }}</h6>
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem; line-height: 1.3;">
                                    {{ Str::limit($achievement->deskripsi, 55) }}
                                </small>
                            </div>
                        </div>
                    </td>
                    
                    <td>
                        @switch($achievement->syarat_type)
                            @case('total_kg')
                                <div class="badge-mission mission-kg" title="Syarat Berat Sampah">
                                    <i class="bi bi-speedometer"></i> Akumulasi {{ $achievement->syarat_value }} kg
                                </div>
                                @break
                            @case('streak')
                                <div class="badge-mission mission-streak" title="Syarat Konsistensi Hari">
                                    <i class="bi bi-fire"></i> Runtun {{ $achievement->syarat_value }} Hari
                                </div>
                                @break
                            @case('transaksi_count')
                                <div class="badge-mission mission-trx" title="Syarat Jumlah Transaksi">
                                    <i class="bi bi-arrow-repeat"></i> {{ $achievement->syarat_value }}x Setoran
                                </div>
                                @break
                            @case('first_time')
                                <div class="badge-mission mission-first" title="Aksi Pertama Kali">
                                    <i class="bi bi-stars"></i> Misi Pertama Kali
                                </div>
                                @break
                            @default
                                <div class="badge-mission bg-light text-dark border"><i class="bi bi-question-circle"></i> Tidak Diketahui</div>
                        @endswitch
                    </td>

                    <td>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-bold fs-6 shadow-sm">
                            <i class="bi bi-coin text-warning me-1"></i> +{{ number_format($achievement->poin_bonus) }} <small class="fw-normal">pts</small>
                        </span>
                    </td>
                    
                    <td class="text-center">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light border rounded-3 px-3 py-1" title="{{ $achievement->mahasiswas_count }} mahasiswa telah meraih ini">
                            <i class="bi bi-people-fill text-muted me-2"></i>
                            <span class="fw-bold text-dark">{{ number_format($achievement->mahasiswas_count) }}</span>
                        </div>
                    </td>
                    
                    <td>
                        <div class="action-btns pe-2">
                            <a href="{{ route('admin.achievement.edit', $achievement) }}" class="btn-icon btn-edit" title="Edit Pencapaian">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button type="button" class="btn-icon btn-delete" title="Hapus Pencapaian" onclick="confirmDelete('{{ route('admin.achievement.destroy', $achievement) }}', '{{ $achievement->nama }}')">
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
                            <div class="fw-bold text-secondary mb-1">Belum Ada Achievement</div>
                            <small>Sistem gamifikasi belum memiliki daftar pencapaian untuk diraih mahasiswa.</small><br>
                            <a href="{{ route('admin.achievement.create') }}" class="btn btn-sm btn-outline-warning text-dark fw-bold rounded-pill mt-3 px-4">Buat Misi Pertama</a>
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