@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@section('content')

    <style>
        /* Animasi Masuk */
        .animate-fade-up {
            opacity: 0;
            transform: translateY(15px);
            animation: fadeUp 0.5s ease-out forwards;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Card Layout Standar Berkelas */
        .custom-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            background-color: #ffffff;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card-header-custom {
            background-color: #ffffff;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* List Group Custom (Boxy, Minimalist) */
        .list-group-custom .list-group-item {
            border-color: #f1f5f9;
            padding: 1.2rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: transparent;
        }

        .list-group-custom .list-group-item .item-label {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .list-group-custom .list-group-item .item-value {
            color: #0f172a;
            font-size: 0.95rem;
            font-weight: 700;
            text-align: right;
        }

        /* Section Title */
        .section-title {
            font-size: 0.8rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            margin-top: 1.5rem;
            padding-left: 1.5rem;
        }
    </style>

    <div class="d-flex align-items-center justify-content-between mb-4 animate-fade-up">
        <div class="d-flex align-items-center">
            <a href="{{ route($routePrefix . '.transaksi.index') }}"
                class="btn btn-light rounded-3 shadow-sm me-3 bg-white border"
                style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a;">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Detail Transaksi Session #{{ $session->id }}</h4>
                <p class="text-muted mb-0 small">Melihat rincian log setoran dan riwayat validasi alat.</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center animate-fade-up" style="animation-delay: 0.1s;">
        <div class="col-lg-8">
            <div class="custom-card">
                @php
                    $items = $session->transaksiItems;
                    $firstItem = $items->first();
                    $mahasiswa = $firstItem?->mahasiswa;
                    $hasAnomali = $items->contains('status_validasi', 'anomali');
                    $totalBerat = $items->sum('berat');
                    $totalPoin = $items->sum('poin_didapat');
                    $totalKoin = $items->sum('koin_didapat');
                    $totalFinal = $items->sum('jumlah_final');
                @endphp
                <div class="card-header-custom bg-light" style="background-color: #f8fafc !important;">
                    <span><i class="bi bi-receipt-cutoff text-success me-2"></i>Invoice / Log Pembukuan</span>
                    @if(!$hasAnomali)
                        <span class="badge px-3 py-1 rounded-2"
                            style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;"><i
                                class="bi bi-check-circle me-1"></i> Valid</span>
                    @else
                        <span class="badge px-3 py-1 rounded-2"
                            style="background: #fff1f2; color: #dc2626; border: 1px solid #fecaca;"><i
                                class="bi bi-exclamation-triangle me-1"></i> Anomali</span>
                    @endif
                </div>

                <div class="card-body p-0">

                    <div class="section-title">Informasi Umum</div>
                    <ul class="list-group list-group-flush list-group-custom">
                        <li class="list-group-item">
                            <span class="item-label"><i class="bi bi-hash text-muted"></i> ID Session</span>
                            <span class="item-value">#{{ $session->id }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="item-label"><i class="bi bi-calendar-event text-muted"></i> Waktu Setoran</span>
                            <span
                                class="item-value">{{ \Carbon\Carbon::parse($session->completed_at)->format('d M Y, H:i:s') }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="item-label"><i class="bi bi-person text-muted"></i> Pelaku Transaksi</span>
                            <span class="item-value">
                                {{ $mahasiswa->name ?? 'Tidak Diketahui' }}
                                @if($mahasiswa)
                                    <a href="{{ route($routePrefix . '.mahasiswa.show', $mahasiswa) }}"
                                        class="ms-2 text-success" title="Lihat Profil">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item">
                            <span class="item-label"><i class="bi bi-card-heading text-muted"></i> NIM Mahasiswa</span>
                            <span class="item-value font-monospace text-muted">{{ $mahasiswa->nim ?? '-' }}</span>
                        </li>
                    </ul>

                    <div class="section-title border-top pt-4">Rincian Fisik Sampah</div>
                    <ul class="list-group list-group-flush list-group-custom">
                        @foreach($items as $item)
                            <li class="list-group-item">
                                <span class="item-label"><i class="bi bi-tags text-success"></i>
                                    {{ $item->jenisSampah->nama ?? '-' }}</span>
                                <span class="item-value">
                                    {{ $item->jumlah_input_botol ?? 0 }} input →
                                    {{ $item->jumlah_final ?? 0 }} final
                                    @if(($item->jumlah_terhitung ?? 0) > ($item->jumlah_final ?? 0))
                                        <br><small class="text-danger fw-normal"><i class="bi bi-exclamation-triangle"></i> Selisih:
                                            {{ ($item->jumlah_terhitung ?? 0) - ($item->jumlah_final ?? 0) }} pcs</small>
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item">
                                <span class="item-label"><i class="bi bi-speedometer2 text-muted"></i> Berat</span>
                                <span
                                    class="item-value">{{ $item->berat >= 1000 ? number_format($item->berat / 1000, 3) . ' kg' : number_format($item->berat, 0) . ' gram' }}</span>
                            </li>
                        @endforeach
                        <li class="list-group-item bg-light" style="background-color: #f8fafc !important;">
                            <span class="item-label"><i class="bi bi-box-seam text-dark"></i> Total (Final)</span>
                            <span class="item-value fs-5">{{ $totalFinal }} pcs</span>
                        </li>
                        <li class="list-group-item bg-light" style="background-color: #f8fafc !important;">
                            <span class="item-label"><i class="bi bi-speedometer2 text-dark"></i> Total Berat</span>
                            <span
                                class="item-value fs-5">{{ $totalBerat >= 1000 ? number_format($totalBerat / 1000, 3) . ' kg' : number_format($totalBerat, 0) . ' gram' }}</span>
                        </li>
                    </ul>

                    <div class="section-title border-top pt-4">Distribusi Reward</div>
                    <ul class="list-group list-group-flush list-group-custom">
                        <li class="list-group-item" style="background-color: #ecfdf5;">
                            <span class="item-label" style="color: #047857;"><i class="bi bi-star-fill text-warning"></i>
                                Poin Mahasiswa</span>
                            <span class="item-value fs-5 text-success">+{{ number_format($totalPoin) }} Pts</span>
                        </li>
                        <li class="list-group-item" style="background-color: #fffbeb;">
                            <span class="item-label" style="color: #b45309;"><i class="bi bi-coin text-warning"></i> Koin
                                Voucher</span>
                            <span class="item-value fs-5" style="color: #d97706;">+{{ number_format($totalKoin) }}
                                Koin</span>
                        </li>
                    </ul>

                    <div class="section-title border-top pt-4">Data Perangkat (Mesin)</div>
                    <ul class="list-group list-group-flush list-group-custom">
                        <li class="list-group-item pb-4">
                            <span class="item-label"><i class="bi bi-hdd-network text-muted"></i> Bak Sampah
                                Terhubung</span>
                            <div class="text-end">
                                <span class="item-value d-block">{{ $firstItem?->bakSampah->nama ?? '-' }}</span>
                                <small class="text-muted d-block mt-1"><i
                                        class="bi bi-geo-alt me-1"></i>{{ $firstItem?->bakSampah->lokasi->nama_lokasi ?? 'Lokasi tidak ditemukan' }}</small>
                            </div>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
@endsection