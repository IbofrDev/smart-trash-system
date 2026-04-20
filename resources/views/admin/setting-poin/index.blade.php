@extends('layouts.admin')

@section('title', 'Setting Poin Gamifikasi')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama */
    .custom-card { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03); background-color: #ffffff; overflow: hidden; }

    /* Header & Toolbar */
    .table-toolbar { padding: 1.5rem; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; background-color: #ffffff; }

    /* Styling Tabel Modern */
    .table-modern { margin-bottom: 0; width: 100%; }
    .table-modern th { background-color: #f9fafb; color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
    .table-modern td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f3f4f6; color: #4b5563; }
    .table-modern tbody tr { transition: background-color 0.2s; }
    .table-modern tbody tr:hover { background-color: #f8fafc; }
    
    /* System Key Box (Nama Setting) */
    .sys-key-box {
        background-color: #f1f5f9; color: #334155; padding: 0.4rem 0.8rem;
        border-radius: 8px; font-family: monospace; font-weight: 600; font-size: 0.85rem;
        display: inline-block; border: 1px solid #e2e8f0;
    }

    /* Value Badge */
    .value-badge {
        background: linear-gradient(135deg, #3b82f6, #2563eb); color: white;
        padding: 0.5rem 1rem; border-radius: 50px; font-weight: bold; font-size: 0.9rem;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2); display: inline-flex; align-items: center; gap: 6px;
    }
    
    /* Tombol Aksi */
    .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: all 0.2s; text-decoration: none; cursor: pointer; }
    .btn-edit { background-color: #eff6ff; color: #3b82f6; }
    .btn-edit:hover { background-color: #3b82f6; color: #fff; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3); }

    /* Empty State */
    .empty-state { padding: 4rem 1rem; text-align: center; color: #9ca3af; }
    .empty-state i { font-size: 4rem; color: #e5e7eb; display: block; margin-bottom: 1rem; }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-dark mb-1">Konfigurasi Sistem Gamifikasi</h4>
        <p class="text-muted mb-0 small">Atur variabel inti yang mempengaruhi perhitungan poin mahasiswa.</p>
    </div>
</div>

<div class="card custom-card animate-fade-up" style="animation-delay: 0.1s;">
    
    <div class="table-toolbar">
        <h6 class="m-0 fw-bold text-dark"><i class="bi bi-sliders text-primary me-2"></i>Daftar Parameter Sistem</h6>
    </div>

    <div class="table-responsive">
        <table class="table table-modern">
            <thead>
                <tr>
                    <th width="5%" class="text-center">#</th>
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
                            <i class="bi bi-gear-fill text-secondary me-1"></i> {{ $setting->nama_setting }}
                        </div>
                    </td>
                    
                    <td>
                        <div class="value-badge">
                            {{ number_format($setting->value) }}
                        </div>
                    </td>

                    <td>
                        <span class="text-muted" style="font-size: 0.85rem; line-height: 1.4; display: block;">
                            {{ $setting->deskripsi ?? 'Tidak ada deskripsi untuk parameter ini.' }}
                        </span>
                    </td>
                    
                    <td>
                        <div class="d-flex justify-content-end pe-2">
                            <a href="{{ route('admin.setting-poin.edit', $setting) }}" class="btn-icon btn-edit" title="Konfigurasi Parameter">
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