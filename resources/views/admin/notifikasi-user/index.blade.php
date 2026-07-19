@extends('layouts.admin')
@section('title', 'Notifikasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #0f172a;">Notifikasi</h4>
        <p class="text-muted mb-0 small">Pemberitahuan status bak sampah.</p>
    </div>
    <form action="{{ route($routePrefix . '.notifikasi.mark-all-read') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-light border rounded-3 fw-semibold px-4" style="color:#64748b;">
            <i class="bi bi-check2-all me-1"></i> Tandai Semua Dibaca
        </button>
    </form>
</div>

@if(session('success'))
    <div class="alert alert-success rounded-3 border-0 mb-4">{{ session('success') }}</div>
@endif

<div class="custom-card" style="border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;">
    @forelse($notifikasis as $notif)
    <div class="d-flex align-items-start gap-3 p-3 border-bottom {{ !$notif->is_read ? 'bg-light' : '' }}"
        style="border-color:#f1f5f9 !important;">
        <div style="width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;
            background: {{ $notif->tipe === 'bak_penuh' ? '#fee2e2' : '#fef3c7' }};">
            <i class="bi {{ $notif->tipe === 'bak_penuh' ? 'bi-exclamation-triangle-fill' : 'bi-exclamation-circle-fill' }}"
                style="color: {{ $notif->tipe === 'bak_penuh' ? '#dc2626' : '#d97706' }};"></i>
        </div>
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-start">
                <div class="fw-bold" style="color:#0f172a; font-size:0.9rem;">
                    {{ $notif->judul }}
                    @if(!$notif->is_read)
                        <span class="badge ms-2 rounded-pill" style="background:#dcfce7; color:#166534; font-size:0.65rem;">Baru</span>
                    @endif
                </div>
                <small class="text-muted ms-3" style="white-space:nowrap;">
                    {{ $notif->created_at->diffForHumans() }}
                </small>
            </div>
            <div class="text-muted small mt-1">{{ $notif->pesan }}</div>
            @if($notif->bakSampah)
            <a href="{{ route($routePrefix . '.bak-sampah.monitoring') }}" class="btn btn-sm mt-2 rounded-2 fw-semibold"
                style="background:#f1f5f9; color:#0f172a; font-size:0.78rem; border:none;">
                <i class="bi bi-bar-chart-line me-1"></i> Lihat Monitoring
            </a>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-5 text-muted">
        <i class="bi bi-bell-slash fs-1 d-block mb-2 opacity-25"></i>
        <small>Belum ada notifikasi.</small>
    </div>
    @endforelse
</div>

@if($notifikasis->hasPages())
<div class="mt-3">{{ $notifikasis->links() }}</div>
@endif
@endsection