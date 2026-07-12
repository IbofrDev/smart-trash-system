@extends('layouts.admin')

@section('title', 'Monitoring Kapasitas Bak')

@section('content')
<style>
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
    .custom-card { border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); background: #fff; overflow: hidden; margin-bottom: 1.5rem; }
    .card-header-custom { background: #fff; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; font-weight: 700; color: #0f172a; display: flex; justify-content: space-between; align-items: center; }
    .bak-card { border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; background: #fff; transition: all 0.2s; }
    .bak-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.04); }
    .bak-card.penuh { border-color: #fecaca; background: #fff5f5; }
    .bak-card.hampir_penuh { border-color: #fde68a; background: #fffbeb; }
    .progress-bar-custom { height: 10px; border-radius: 99px; background: #f1f5f9; overflow: hidden; }
    .progress-fill { height: 100%; border-radius: 99px; transition: width 0.5s; }
    .table-clean th { background: #fff; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; padding: 0.85rem 1rem; border-bottom: 2px solid #f1f5f9; }
    .table-clean td { padding: 0.85rem 1rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; font-size: 0.85rem; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="color: #0f172a;">Monitoring Kapasitas Bak</h4>
        <p class="text-muted mb-0 small">Pantau isi bak sampah dan lakukan pengosongan jika sudah penuh.</p>
    </div>
    <a href="{{ route($routePrefix . '.bak-sampah.index') }}" class="btn btn-light border rounded-3 fw-bold px-4" style="color: #64748b;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

{{-- Alert --}}
@if(session('success'))
    <div class="alert alert-success rounded-3 border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    </div>
@endif

{{-- Summary Badge --}}
@php
    $jumlahPenuh      = $bakSampahs->where('status_kapasitas', 'penuh')->count();
    $jumlahHampirPenuh = $bakSampahs->where('status_kapasitas', 'hampir_penuh')->count();
@endphp
@if($jumlahPenuh > 0)
    <div class="alert rounded-3 border-0 mb-4 d-flex align-items-center gap-3" style="background: #fff1f2; border-left: 4px solid #dc2626 !important; border-left-width: 4px;">
        <i class="bi bi-exclamation-triangle-fill fs-4" style="color: #dc2626;"></i>
        <div>
            <div class="fw-bold" style="color: #dc2626;">{{ $jumlahPenuh }} Bak Sudah Penuh!</div>
            <small class="text-muted">Segera lakukan pengosongan di lapangan.</small>
        </div>
    </div>
@endif
@if($jumlahHampirPenuh > 0)
    <div class="alert rounded-3 border-0 mb-4 d-flex align-items-center gap-3" style="background: #fffbeb; border-left: 4px solid #d97706 !important;">
        <i class="bi bi-exclamation-circle-fill fs-4" style="color: #d97706;"></i>
        <div>
            <div class="fw-bold" style="color: #d97706;">{{ $jumlahHampirPenuh }} Bak Hampir Penuh (≥80%)</div>
            <small class="text-muted">Perlu perhatian segera.</small>
        </div>
    </div>
@endif

{{-- Grid Bak Sampah --}}
<div class="row g-3 mb-4 animate-fade-up" style="animation-delay: 0.1s;">
    @forelse($bakSampahs as $bak)
    @php
        $fillColor = match($bak->status_kapasitas) {
            'penuh'       => '#dc2626',
            'hampir_penuh'=> '#d97706',
            default       => '#10b981',
        };
    @endphp
    <div class="col-md-6 col-lg-4">
        <div class="bak-card {{ $bak->status_kapasitas }}">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="fw-bold" style="color: #0f172a;">{{ $bak->nama }}</div>
                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $bak->lokasi->nama_lokasi ?? '-' }}</small>
                </div>
                @if($bak->status_kapasitas === 'penuh')
                    <span class="badge px-2 py-1 rounded-2" style="background: #fee2e2; color: #dc2626; border: 1px solid #fecaca;">PENUH</span>
                @elseif($bak->status_kapasitas === 'hampir_penuh')
                    <span class="badge px-2 py-1 rounded-2" style="background: #fef3c7; color: #d97706; border: 1px solid #fde68a;">HAMPIR PENUH</span>
                @else
                    <span class="badge px-2 py-1 rounded-2" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;">NORMAL</span>
                @endif
            </div>

            <div class="d-flex justify-content-between mb-1">
                <small class="text-muted fw-semibold">Terisi</small>
                <small class="fw-bold" style="color: {{ $fillColor }};">{{ $bak->jumlah_botol_terisi }} / {{ $bak->kapasitas_max_botol }} pcs ({{ $bak->persen }}%)</small>
            </div>
            <div class="progress-bar-custom mb-3">
                <div class="progress-fill" style="width: {{ $bak->persen }}%; background: {{ $fillColor }};"></div>
            </div>

            <form action="{{ route($routePrefix . '.bak-sampah.kosongkan', $bak) }}" method="POST"
                onsubmit="return confirm('Konfirmasi: Bak {{ $bak->nama }} sudah dikosongkan di lapangan?')">
                @csrf
                @method('PATCH')
                <input type="text" name="catatan" class="form-control form-control-sm mb-2 rounded-2"
                    placeholder="Catatan opsional (misal: diambil oleh petugas)" style="border-color: #e2e8f0; font-size: 0.8rem;">
                <button type="submit" class="btn w-100 rounded-2 fw-bold py-2"
                    style="background: #0f172a; color: #fff; font-size: 0.85rem; border: none;">
                    <i class="bi bi-trash3 me-1"></i> Tandai Sudah Dikosongkan
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">
        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
        <small>Tidak ada bak sampah aktif.</small>
    </div>
    @endforelse
</div>

{{-- Log Pengosongan --}}
<div class="custom-card animate-fade-up" style="animation-delay: 0.2s;">
    <div class="card-header-custom">
        <span><i class="bi bi-clock-history text-success me-2"></i>Riwayat Pengosongan</span>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Bak Sampah</th>
                    <th>Isi Sebelum Dikosongkan</th>
                    <th>Dikosongkan Oleh</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logPengosongan as $log)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ \Carbon\Carbon::parse($log->dikosongkan_at)->format('d M Y') }}</div>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($log->dikosongkan_at)->format('H:i') }} WITA</small>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $log->bakSampah->nama ?? '-' }}</div>
                        <small class="text-muted">{{ $log->bakSampah->lokasi->nama_lokasi ?? '-' }}</small>
                    </td>
                    <td>
                        <span class="badge px-2 py-1 rounded-2" style="background: #f1f5f9; color: #0f172a; border: 1px solid #e2e8f0;">
                            {{ number_format($log->jumlah_botol_sebelum) }} pcs
                        </span>
                    </td>
                    <td class="fw-semibold">{{ $log->user->name ?? '-' }}</td>
                    <td class="text-muted">{{ $log->catatan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox d-block mb-1 opacity-25"></i>
                        Belum ada riwayat pengosongan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection