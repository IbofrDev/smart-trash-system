@extends('layouts.admin')

@section('title', 'Setting Poin Gamifikasi')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama - Lebih Kotak & Bersih */
    .custom-card { border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }

    /* Header & Toolbar */
    .table-toolbar { padding: 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background-color: #ffffff; }

    /* Styling Tabel Clean */
    .table-clean { margin-bottom: 0; width: 100%; }
    .table-clean th { background-color: #ffffff; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 1rem 1.5rem; border-bottom: 2px solid #f1f5f9; }
    .table-clean td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; color: #334155; }
    .table-clean tbody tr { transition: background-color 0.2s; }
    .table-clean tbody tr:hover { background-color: #f8fafc; }
    
    /* System Key Box (Nama Setting) - Lebih Boxy */
    .sys-key-box {
        background-color: #f8fafc; color: #0f172a; padding: 0.5rem 1rem;
        border-radius: 6px; font-family: monospace; font-weight: 700; font-size: 0.85rem;
        display: inline-block; border: 1px solid #e2e8f0;
    }

    /* Value Badge - Diubah dari Blue Pill menjadi Modern Boxy Mint */
    .value-badge {
        background-color: #ecfdf5; color: #047857;
        padding: 0.5rem 1.25rem; border-radius: 6px; font-weight: 800; font-size: 0.95rem;
        border: 1px solid #a7f3d0; display: inline-flex; align-items: center; gap: 6px;
    }
    
    /* Tombol Aksi */
    .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid #e2e8f0; background-color: #f8fafc; color: #64748b; transition: all 0.2s; text-decoration: none; cursor: pointer; }
    .btn-icon:hover { transform: translateY(-2px); }
    .btn-edit:hover { background-color: #0f172a; color: #ffffff; border-color: #0f172a; }

    /* Empty State */
    .empty-state { padding: 4rem 1rem; text-align: center; color: #94a3b8; }
    .empty-state i { font-size: 4rem; color: #e2e8f0; display: block; margin-bottom: 1rem; }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Konfigurasi Sistem Gamifikasi</h4>
        <p class="text-muted mb-0 small">Atur variabel inti yang mempengaruhi perhitungan poin mahasiswa.</p>
    </div>
</div>

<div class="custom-card animate-fade-up" style="animation-delay: 0.1s;">
    
    <div class="table-toolbar">
        <h6 class="m-0 fw-bold" style="color: #0f172a;"><i class="bi bi-sliders text-success me-2"></i>Daftar Parameter Sistem</h6>
    </div>

    <div class="table-responsive">
        <table class="table table-clean">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="25%">Variabel Sistem</th>
                    <th width="20%">Nilai (Value)</th>
                    <th width="40%">Fungsi & Deskripsi</th>
                    <th width="10%" class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($settings as $index => $setting)
                <tr>
                    <td class="text-center text-muted fw-semibold">{{ $index + 1 }}</td>
                    
                    <td>
                        <div class="sys-key-box">
                            <i class="bi bi-gear-fill text-success me-1"></i> {{ $setting->nama_setting }}
                        </div>
                    </td>
                    
                    <td>
                        <div class="value-badge">
                            <i class="bi bi-star-fill text-warning me-1"></i> {{ number_format($setting->value) }}
                        </div>
                    </td>

                    <td>
                        <span class="text-muted" style="font-size: 0.85rem; line-height: 1.5; display: block;">
                            {{ $setting->deskripsi ?? 'Tidak ada deskripsi untuk parameter ini.' }}
                        </span>
                    </td>
                    
                    <td class="pe-4">
                        <div class="d-flex justify-content-end pe-2">
                            <a href="{{ route($routePrefix . '.setting-poin.edit', $setting) }}" class="btn-icon btn-edit" title="Konfigurasi Parameter">
                                <i class="bi bi-wrench-adjustable"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="border-0">
                        <div class="empty-state">
                            <i class="bi bi-server"></i>
                            <div class="fw-bold text-secondary mb-1">Parameter Kosong</div>
                            <small>Data konfigurasi sistem belum tersedia di database.</small>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection