@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    @php
        // Logic untuk merubah ucapan berdasarkan jam
        $hour = now()->format('H');
        if ($hour < 11) {
            $greeting = 'Selamat Pagi';
        } elseif ($hour < 15) {
            $greeting = 'Selamat Siang';
        } elseif ($hour < 18) {
            $greeting = 'Selamat Sore';
        } else {
            $greeting = 'Selamat Malam';
        }
    @endphp

    <style>
        /* Animasi halus saat halaman dimuat */
        .animate-up {
            opacity: 0;
            transform: translateY(15px);
            animation: fadeInUp 0.5s ease-out forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Greeting Card (Sesuai Referensi) */
        .greeting-card {
            background-color: #ffffff;
            border-radius: 16px;
            border-left: 6px solid #10b981;
            /* Aksen hijau tebal di kiri */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f1f5f9;
            border-right: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
        }

        .greeting-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .greeting-subtitle {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0;
        }

        .btn-outline-dark-custom {
            border: 1.5px solid #0f172a;
            color: #0f172a;
            background: transparent;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-outline-dark-custom:hover {
            background-color: #f8fafc;
            color: #000;
            transform: translateY(-1px);
        }

        /* Card Styling */
        .dash-card {
            border: 1px solid #f1f5f9;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
        }

        .dash-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        /* Stat Card Icon (Monokromatik Hijau) */
        .stat-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            background-color: #ecfdf5;
            /* Hijau sangat pudar */
            color: #047857;
            /* Hijau tua elegan */
        }

        /* Text & Typography */
        .stat-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0;
        }

        /* Chart & Table Container */
        .section-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: #10b981;
        }

        /* Table Minimalist */
        .table-clean thead th {
            background-color: #ffffff;
            border-bottom: 2px solid #f1f5f9;
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 1rem;
        }

        .table-clean tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f8fafc;
            font-size: 0.9rem;
            color: #334155;
        }

        .table-clean tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Badges */
        .status-badge {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .status-online {
            background-color: #ecfdf5;
            color: #047857;
            border: 1px solid #d1fae5;
        }

        .status-offline {
            background-color: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }
    </style>

    <div class="container-fluid py-2">

        <div class="greeting-card mb-4 animate-up">
            <div>
                <h4 class="greeting-title">{{ $greeting }}, {{ auth()->user()->name ?? 'Administrator' }}! 👋</h4>
                <p class="greeting-subtitle">Sistem Smart Waste Bank siap dikelola.</p>
            </div>
            <div>
                <button onclick="window.location.reload();" class="btn-outline-dark-custom">
                    <i class="bi bi-arrow-clockwise"></i> Refresh Data
                </button>
            </div>
        </div>

        <div class="row g-4 mb-4 animate-up" style="animation-delay: 0.1s;">
            <div class="col-xl-3 col-md-6">
                <div class="dash-card p-4">
                    <div class="stat-icon-box">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <p class="stat-label">Total Mahasiswa</p>
                    <h3 class="stat-value">{{ number_format($stats['total_mahasiswa'] ?? 0) }}</h3>
                    <div class="mt-2 text-muted small fw-medium">
                        <i class="bi bi-check2-circle text-success me-1"></i> Terdaftar di sistem
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="dash-card p-4">
                    <div class="stat-icon-box">
                        <i class="bi bi-recycle"></i>
                    </div>
                    <p class="stat-label">Sampah Terkumpul</p>
                    <h3 class="stat-value">
                        @if(($stats['total_berat'] ?? 0) >= 1000)
                            {{ number_format(($stats['total_berat'] ?? 0) / 1000, 1) }} kg
                        @else
                            {{ number_format($stats['total_berat'] ?? 0, 0) }} g
                        @endif
                    </h3>
                    <div class="mt-2 text-muted small fw-medium">
                        <i class="bi bi-plugin text-success me-1"></i> {{ $stats['total_botol'] ?? 0 }} total item
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="dash-card p-4">
                    <div class="stat-icon-box">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <p class="stat-label">Poin Terdistribusi</p>
                    <h3 class="stat-value">{{ number_format($stats['total_poin'] ?? 0) }}</h3>
                    <div class="mt-2 text-muted small fw-medium">
                        <i class="bi bi-stars text-success me-1"></i> Total reward poin
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="dash-card p-4">
                    <div class="stat-icon-box">
                        <i class="bi bi-hdd-network-fill"></i>
                    </div>
                    <p class="stat-label">Status Mesin</p>
                    <h3 class="stat-value">{{ $stats['bak_aktif'] ?? 0 }} <span style="font-size: 1rem; color: #94a3b8;">/
                            {{ $stats['total_bak'] ?? 0 }}</span></h3>
                    <div class="mt-2">
                        <span class="status-badge status-online"><i class="bi bi-wifi me-1"></i> Normal</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 animate-up" style="animation-delay: 0.2s;">
            <div class="col-lg-8">
                <div class="dash-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div class="section-title mb-0">
                            <i class="bi bi-bar-chart-line-fill"></i> <span id="chartTitle">Tren Setoran Sampah (7 Hari
                                Terakhir)</span>
                        </div>
                        <div class="btn-group btn-group-sm" role="group" id="chartFilter">
                            <button type="button" class="btn btn-outline-success chart-filter-btn" data-periode="3">3
                                Hari</button>
                            <button type="button" class="btn btn-success chart-filter-btn active" data-periode="7">7
                                Hari</button>
                            <button type="button" class="btn btn-outline-success chart-filter-btn" data-periode="30">1
                                Bulan</button>
                        </div>
                    </div>
                    <canvas id="activityChart" style="max-height: 320px;"></canvas>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="dash-card p-4 h-100">
                    <div class="section-title">
                        <i class="bi bi-trophy-fill"></i> Peringkat Teratas
                    </div>
                    <div class="list-group list-group-flush mt-3">
                        @forelse($topMahasiswa ?? [] as $index => $mhs)
                            <div class="d-flex align-items-center mb-4">
                                <div class="position-relative">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($mhs->name ?? 'M') }}&background=f1f5f9&color=047857&bold=true"
                                        class="rounded-circle border" width="45">
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark"
                                        style="font-size: 10px;">
                                        #{{ $index + 1 }}
                                    </span>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 fw-bold" style="color: #1e293b;">{{ Str::limit($mhs->name ?? '-', 20) }}
                                    </h6>
                                    <small class="text-muted fw-medium"><span
                                            style="color: #059669;">{{ number_format($mhs->total_poin ?? 0) }} Pts</span> •
                                        {{ $mhs->level->nama_level ?? '-' }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-award fs-1 opacity-25 d-block mb-2"></i>
                                <small>Belum ada data peringkat</small>
                            </div>
                        @endforelse
                    </div>
                    <a href="{{ route(auth()->user()->role . '.laporan.mahasiswa') }}"
                        class="btn btn-light w-100 rounded-pill fw-bold py-2 mt-2"
                        style="color: #047857; background: #f8fafc; border: 1px solid #e2e8f0;">
                        Lihat Leaderboard Detail
                    </a>
                </div>
            </div>
        </div>

        <div class="row mt-4 animate-up" style="animation-delay: 0.3s;">
            <div class="col-12">
                <div class="dash-card p-0 overflow-hidden">
                    <div class="p-4 d-flex justify-content-between align-items-center border-bottom"
                        style="border-color: #f1f5f9 !important;">
                        <div class="section-title mb-0">
                            <i class="bi bi-clock-history"></i> Transaksi Terakhir
                        </div>
                        <a href="{{ route(auth()->user()->role . '.transaksi.index') }}"
                            class="fw-bold text-decoration-none small px-3 py-1 rounded-pill"
                            style="background: #ecfdf5; color: #047857;">Lihat Semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-clean mb-0 align-middle">
                            <thead>
                                <tr style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    <th class="ps-4">MAHASISWA</th>
                                    <th>LOKASI</th>
                                    <th>JENIS SAMPAH</th>
                                    <th>SETORAN</th>
                                    <th class="text-end">POIN</th>
                                    <th>WAKTU</th>
                                    <th class="text-center pe-4">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransaksi ?? [] as $trx)
                                    @php
                                        $isAnomali = ($trx->status_validasi ?? 'valid') !== 'valid';
                                        $jenisNama = strtolower($trx->jenisSampah->nama ?? '');
                                        if (str_contains($jenisNama, 'botol') || str_contains($jenisNama, 'plastik')) {
                                            $jenisIcon = '🥤';
                                            $jenisColor = '#0ea5e9';
                                        } elseif (str_contains($jenisNama, 'kaleng') || str_contains($jenisNama, 'logam')) {
                                            $jenisIcon = '🥫';
                                            $jenisColor = '#94a3b8';
                                        } elseif (str_contains($jenisNama, 'kertas')) {
                                            $jenisIcon = '📄';
                                            $jenisColor = '#f59e0b';
                                        } elseif (str_contains($jenisNama, 'organik')) {
                                            $jenisIcon = '🍃';
                                            $jenisColor = '#10b981';
                                        } else {
                                            $jenisIcon = '♻️';
                                            $jenisColor = '#64748b';
                                        }
                                    @endphp
                                    <tr style="{{ $isAnomali ? 'background: #fef2f2;' : '' }}">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($trx->mahasiswa->name ?? 'M') }}&background=ecfdf5&color=047857&bold=true&size=36"
                                                    class="rounded-circle me-2" width="36" height="36">
                                                <div>
                                                    <div class="fw-bold" style="color: #0f172a; font-size: 0.875rem;">
                                                        {{ Str::limit($trx->mahasiswa->name ?? '-', 18) }}</div>
                                                    <small class="text-muted"
                                                        style="font-family: monospace; font-size: 0.7rem;">{{ $trx->mahasiswa->nim ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-medium" style="color: #334155; font-size: 0.875rem;">
                                                <i class="bi bi-geo-alt-fill" style="color: #10b981; font-size: 0.75rem;"></i>
                                                {{ $trx->bakSampah->nama ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill px-2 py-1"
                                                style="background: {{ $jenisColor }}15; color: {{ $jenisColor }}; font-weight: 600;">
                                                {{ $jenisIcon }} {{ $trx->jenisSampah->nama ?? 'Lainnya' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-bold" style="color: #0f172a; font-size: 0.875rem;">
                                                @if(($trx->berat ?? 0) >= 1000)
                                                    {{ number_format($trx->berat / 1000, 2) }} kg
                                                @else
                                                    {{ number_format($trx->berat ?? 0, 0) }} g
                                                @endif
                                            </div>
                                            <small class="text-muted" style="font-size: 0.7rem;">
                                                <i class="bi bi-box-seam"></i> {{ $trx->jumlah_final ?? 0 }} item
                                            </small>
                                        </td>
                                        <td class="text-end">
                                            @if(($trx->poin_didapat ?? 0) > 0)
                                                <span class="fw-bold"
                                                    style="color: #059669; font-size: 0.95rem;">+{{ number_format($trx->poin_didapat) }}</span>
                                            @else
                                                <span class="fw-bold text-muted" style="font-size: 0.95rem;">0</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted small fw-medium"
                                                title="{{ $trx->tanggal_transaksi ? $trx->tanggal_transaksi->format('d M Y, H:i') : '-' }}"
                                                data-bs-toggle="tooltip" style="cursor: help;">
                                                <i class="bi bi-clock" style="font-size: 0.7rem;"></i>
                                                {{ $trx->tanggal_transaksi ? $trx->tanggal_transaksi->diffForHumans() : '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center pe-4">
                                            @if($isAnomali)
                                                <span class="badge rounded-pill px-3 py-2"
                                                    style="background: #fee2e2; color: #b91c1c; font-weight: 600;">
                                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                                    {{ ucfirst($trx->status_validasi) }}
                                                </span>
                                            @else
                                                <span class="badge rounded-pill px-3 py-2"
                                                    style="background: #d1fae5; color: #047857; font-weight: 600;">
                                                    <i class="bi bi-check-circle-fill"></i> Valid
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1 opacity-25 d-block mb-2"></i>
                                            <small>Belum ada transaksi terekam</small>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Ganti default font agar serasi dengan template
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#64748b';

            const ctx = document.getElementById('activityChart').getContext('2d');

            // Gradient Aksen Hijau untuk Area Bawah Grafik
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
            gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

            // Data awal (7 hari terakhir)
            const labelsData = {!! isset($perHari) && $perHari->count() > 0 ? json_encode($perHari->pluck('tanggal')->map(fn($t) => \Carbon\Carbon::parse($t)->format('d M'))) : '[]' !!};
            const chartData = {!! isset($perHari) && $perHari->count() > 0 ? json_encode($perHari->pluck('total_berat_gram')->map(fn($v) => (float) $v)) : '[]' !!};

            const activityChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labelsData,
                    datasets: [{
                        label: 'Volume Sampah (Gram)',
                        data: chartData,
                        borderColor: '#10b981',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#059669',
                        pointBorderWidth: 2,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 13 },
                            bodyFont: { size: 13 },
                            padding: 12,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#94a3b8',
                                maxRotation: 45,
                                minRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 15
                            }
                        },
                        y: { grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8' }, beginAtZero: true }
                    }
                }
            });

            // Filter Periode (AJAX)
            const chartUrl = "{{ route(auth()->user()->role === 'admin' ? 'admin.dashboard.chart-data' : 'pengelola.dashboard.chart-data') }}";
            const chartTitle = document.getElementById('chartTitle');
            const filterBtns = document.querySelectorAll('.chart-filter-btn');

            const periodeLabel = {
                3: 'Tren Setoran Sampah (3 Hari Terakhir)',
                7: 'Tren Setoran Sampah (7 Hari Terakhir)',
                30: 'Tren Setoran Sampah (1 Bulan Terakhir)'
            };

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const periode = this.dataset.periode;

                    // Update tampilan tombol
                    filterBtns.forEach(b => {
                        b.classList.remove('btn-success', 'active');
                        b.classList.add('btn-outline-success');
                    });
                    this.classList.add('btn-success', 'active');
                    this.classList.remove('btn-outline-success');

                    // Fetch data baru
                    fetch(chartUrl + '?periode=' + periode)
                        .then(response => response.json())
                        .then(result => {
                            activityChart.data.labels = result.labels;
                            activityChart.data.datasets[0].data = result.data;
                            activityChart.update();
                            chartTitle.textContent = periodeLabel[result.periode] || periodeLabel[7];
                        })
                        .catch(error => {
                            console.error('Error loading chart data:', error);
                        });
                });
            });
        });
    </script>
@endpush