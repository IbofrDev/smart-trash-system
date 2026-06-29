@extends('layouts.admin')

@section('title', 'Data Transaksi')

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

        /* Card Utama & Umum */
        .custom-card {
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            background-color: #ffffff;
            overflow: hidden;
        }

        /* Stat Cards - Minimalist Boxy */
        .stat-card-boxy {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            padding: 1.1rem 1.25rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: transform 0.2s;
        }

        .stat-card-boxy:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.03);
        }

        .stat-card-boxy h3 {
            font-weight: 800;
            font-size: 1.6rem;
            color: #0f172a;
            margin-bottom: 0.2rem;
            z-index: 1;
        }

        .stat-card-boxy p {
            margin: 0;
            font-size: 0.72rem;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            z-index: 1;
        }

        .stat-icon {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            font-size: 2rem;
            color: #f1f5f9;
            z-index: 0;
        }

        /* Filter Area */
        .filter-wrapper {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            position: relative;
            z-index: 50;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
        }

        .filter-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            display: block;
        }

        /* Input Date Normal */
        .filter-input {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            color: #0f172a;
            background-color: #f8fafc;
            transition: all 0.2s;
        }

        .filter-input:focus {
            outline: none;
            border-color: #10b981;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        /* --- Custom Dropdown Filter Modern --- */
        .modern-dropdown-btn {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.6rem 2.5rem 0.6rem 1rem;
            color: #0f172a;
            font-size: 0.9rem;
            font-weight: 500;
            width: 100%;
            text-align: left;
            transition: all 0.2s;
            position: relative;
        }

        .modern-dropdown-btn:hover,
        .modern-dropdown-btn:focus,
        .modern-dropdown-btn.show {
            background-color: #ffffff;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            outline: none;
        }

        .modern-dropdown-btn::after {
            display: none;
        }

        .modern-dropdown-btn::before {
            content: '\F282';
            font-family: 'bootstrap-icons';
            font-size: 0.85rem;
            color: #94a3b8;
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            transition: transform 0.3s;
        }

        .modern-dropdown-btn.show::before {
            transform: translateY(-50%) rotate(180deg);
            color: #10b981;
        }

        .custom-dropdown-menu {
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            padding: 0.5rem;
            margin-top: 0.5rem !important;
            width: 100%;
            max-height: 250px;
            overflow-y: auto;
            z-index: 1050;
        }

        .custom-dropdown-item {
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            color: #475569;
            font-weight: 500;
            transition: all 0.2s;
        }

        .custom-dropdown-item:hover,
        .custom-dropdown-item.active-filter {
            background-color: #ecfdf5;
            color: #047857;
        }

        /* Styling Tabel Clean */
        .table-clean {
            margin-bottom: 0;
            width: 100%;
        }

        .table-clean th {
            background-color: #ffffff;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.3px;
            padding: 0.85rem 0.75rem;
            border-bottom: 2px solid #f1f5f9;
            white-space: nowrap;
        }

        .table-clean td {
            padding: 0.85rem 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #f8fafc;
            color: #334155;
            font-size: 0.85rem;
        }

        .table-clean tbody tr {
            transition: background-color 0.2s;
        }

        .table-clean tbody tr:hover {
            background-color: #f8fafc;
        }

        .table-clean th:first-child,
        .table-clean td:first-child {
            padding-left: 1.25rem;
        }

        .table-clean th:last-child,
        .table-clean td:last-child {
            padding-right: 1.25rem;
        }

        /* Tombol Aksi */
        .action-btns {
            display: flex;
            gap: 0.4rem;
            justify-content: flex-end;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            color: #64748b;
            transition: all 0.2s;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-icon:hover {
            transform: translateY(-2px);
        }

        .btn-detail:hover {
            background-color: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
        }

        /* Empty State */
        .empty-state {
            padding: 4rem 1rem;
            text-align: center;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 4rem;
            color: #e2e8f0;
            display: block;
            margin-bottom: 1rem;
        }

        .pagination-wrapper {
            padding: 1.25rem 1.5rem;
            background-color: #ffffff;
            border-top: 1px solid #f1f5f9;
        }

        /* Avatar Kotak Halus */
        .avatar-square-soft {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
        }
    </style>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
        <div class="mb-3 mb-md-0">
            <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Riwayat Transaksi</h4>
            <p class="text-muted mb-0 small">Pantau aktivitas setoran sampah, validasi mesin, dan distribusi reward.</p>
        </div>
    </div>

    <div class="row g-3 mb-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="col-lg col-md-4 col-6">
            <div class="stat-card-boxy">
                <i class="bi bi-receipt-cutoff stat-icon"></i>
                <h3>{{ number_format($summary['total_transaksi']) }}</h3>
                <p>Total Transaksi</p>
            </div>
        </div>
        <div class="col-lg col-md-4 col-6">
            <div class="stat-card-boxy">
                <i class="bi bi-speedometer2 stat-icon"></i>
                <h3 style="color: #059669;">
                    @if(($summary['total_berat'] ?? 0) >= 1000)
                        {{ number_format($summary['total_berat'] / 1000, 1) }}<span
                            style="font-size: 1rem; font-weight: 600; color: #64748b;"> kg</span>
                    @else
                        {{ number_format($summary['total_berat'], 0) }}<span
                            style="font-size: 1rem; font-weight: 600; color: #64748b;"> g</span>
                    @endif
                </h3>
                <p>Volume Berat</p>
            </div>
        </div>
        <div class="col-lg col-md-4 col-6">
            <div class="stat-card-boxy">
                <i class="bi bi-star-fill stat-icon"></i>
                <h3>{{ number_format($summary['total_poin']) }}</h3>
                <p>Poin Dibagikan</p>
            </div>
        </div>
        <div class="col-lg col-md-4 col-6">
            <div class="stat-card-boxy">
                <i class="bi bi-coin stat-icon"></i>
                <h3>{{ number_format($summary['total_koin'] ?? 0) }}</h3>
                <p>Koin Voucher</p>
            </div>
        </div>
        <div class="col-lg col-md-4 col-6">
            <div class="stat-card-boxy"
                style="{{ ($summary['total_anomali'] ?? 0) > 0 ? 'border-color: #fecaca; background: #fff5f5;' : '' }}">
                <i class="bi bi-exclamation-triangle-fill stat-icon"
                    style="{{ ($summary['total_anomali'] ?? 0) > 0 ? 'color: #fecaca;' : '' }}"></i>
                <h3 style="{{ ($summary['total_anomali'] ?? 0) > 0 ? 'color: #dc2626;' : '' }}">
                    {{ number_format($summary['total_anomali'] ?? 0) }}
                </h3>
                <p>Anomali</p>
            </div>
        </div>
    </div>

    <div class="filter-wrapper animate-fade-up" style="animation-delay: 0.2s;">
        <form action="{{ route($routePrefix . '.transaksi.index') }}" method="GET" class="row g-3 align-items-end">

            <div class="col-lg-2 col-md-4">
                <label class="filter-label"><i class="bi bi-calendar-event me-1 text-success"></i> Mulai Tgl</label>
                <input type="date" name="tanggal_dari" class="form-control filter-input w-100"
                    value="{{ request('tanggal_dari') }}">
            </div>

            <div class="col-lg-2 col-md-4">
                <label class="filter-label"><i class="bi bi-calendar-check me-1 text-success"></i> Sampai Tgl</label>
                <input type="date" name="tanggal_sampai" class="form-control filter-input w-100"
                    value="{{ request('tanggal_sampai') }}">
            </div>

            <div class="col-lg-2 col-md-4">
                <label class="filter-label"><i class="bi bi-tags me-1 text-success"></i> Kategori</label>
                <input type="hidden" name="jenis_sampah_id" id="valJenisSampah" value="{{ request('jenis_sampah_id') }}">
                <div class="dropdown">
                    <button class="btn modern-dropdown-btn dropdown-toggle text-truncate" type="button"
                        data-bs-toggle="dropdown">
                        <span id="lblJenisSampah">
                            @php
                                $jenisAktif = $jenisSampahs->firstWhere('id', request('jenis_sampah_id'));
                                echo $jenisAktif ? $jenisAktif->nama : 'Semua Kategori';
                            @endphp
                        </span>
                    </button>
                    <ul class="dropdown-menu custom-dropdown-menu">
                        <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('jenis_sampah_id') == '' ? 'active-filter' : '' }}"
                                href="#" data-val="" data-target="valJenisSampah" data-label="lblJenisSampah">Semua
                                Kategori</a></li>
                        @foreach($jenisSampahs as $jenis)
                            <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('jenis_sampah_id') == $jenis->id ? 'active-filter' : '' }}"
                                    href="#" data-val="{{ $jenis->id }}" data-target="valJenisSampah"
                                    data-label="lblJenisSampah">{{ $jenis->nama }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-lg-2 col-md-4">
                <label class="filter-label"><i class="bi bi-hdd-network me-1 text-success"></i> Lokasi Mesin</label>
                <input type="hidden" name="bak_sampah_id" id="valBakSampah" value="{{ request('bak_sampah_id') }}">
                <div class="dropdown">
                    <button class="btn modern-dropdown-btn dropdown-toggle text-truncate" type="button"
                        data-bs-toggle="dropdown">
                        <span id="lblBakSampah">
                            @php
                                $bakAktif = $bakSampahs->firstWhere('id', request('bak_sampah_id'));
                                echo $bakAktif ? $bakAktif->nama : 'Semua Mesin';
                            @endphp
                        </span>
                    </button>
                    <ul class="dropdown-menu custom-dropdown-menu">
                        <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('bak_sampah_id') == '' ? 'active-filter' : '' }}"
                                href="#" data-val="" data-target="valBakSampah" data-label="lblBakSampah">Semua Mesin</a>
                        </li>
                        @foreach($bakSampahs as $bak)
                            <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('bak_sampah_id') == $bak->id ? 'active-filter' : '' }}"
                                    href="#" data-val="{{ $bak->id }}" data-target="valBakSampah"
                                    data-label="lblBakSampah">{{ $bak->nama }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-lg-2 col-md-4">
                <label class="filter-label"><i class="bi bi-shield-check me-1 text-success"></i> Validasi</label>
                <input type="hidden" name="status_validasi" id="valStatus" value="{{ request('status_validasi') }}">
                <div class="dropdown">
                    <button class="btn modern-dropdown-btn dropdown-toggle text-truncate" type="button"
                        data-bs-toggle="dropdown">
                        <span id="lblStatus">
                            @php
                                if (request('status_validasi') == 'valid')
                                    echo 'Valid';
                                elseif (request('status_validasi') == 'anomali')
                                    echo 'Anomali (Warning)';
                                else
                                    echo 'Semua Status';
                            @endphp
                        </span>
                    </button>
                    <ul class="dropdown-menu custom-dropdown-menu">
                        <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('status_validasi') == '' ? 'active-filter' : '' }}"
                                href="#" data-val="" data-target="valStatus" data-label="lblStatus">Semua Status</a></li>
                        <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('status_validasi') == 'valid' ? 'active-filter' : '' }}"
                                href="#" data-val="valid" data-target="valStatus" data-label="lblStatus"><i
                                    class="bi bi-check-circle text-success me-1"></i> Valid</a></li>
                        <li><a class="dropdown-item custom-dropdown-item filter-item {{ request('status_validasi') == 'anomali' ? 'active-filter' : '' }}"
                                href="#" data-val="anomali" data-target="valStatus" data-label="lblStatus"><i
                                    class="bi bi-exclamation-triangle text-danger me-1"></i> Anomali (Warning)</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 d-flex gap-2">
                <button type="submit"
                    class="btn w-100 rounded-3 shadow-sm d-flex justify-content-center align-items-center fw-bold"
                    style="height: 42px; background-color: #0f172a; color: #ffffff;">
                    <i class="bi bi-funnel-fill me-2"></i> Filter
                </button>
                @if(request()->hasAny(['tanggal_dari', 'tanggal_sampai', 'jenis_sampah_id', 'bak_sampah_id', 'status_validasi']))
                    <a href="{{ route($routePrefix . '.transaksi.index') }}"
                        class="btn btn-light border text-danger w-100 rounded-3 shadow-sm d-flex justify-content-center align-items-center fw-bold"
                        style="height: 42px;" title="Reset Filter">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="custom-card animate-fade-up" style="animation-delay: 0.3s;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-clean">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Waktu & Pelaku</th>
                            <th>Lokasi Mesin</th>
                            <th>Jenis Sampah</th>
                            <th>Setoran Masuk</th>
                            <th>Reward</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $index => $trx)
                            @php
                                $isAnomali = ($trx->status_validasi ?? 'valid') !== 'valid';
                                $jenisNama = strtolower($trx->jenisSampah->nama ?? '');
                                if (str_contains($jenisNama, 'botol') || str_contains($jenisNama, 'plastik')) {
                                    $jenisIcon = '🥤';
                                    $jenisColor = '#0ea5e9';
                                } elseif (str_contains($jenisNama, 'kaleng') || str_contains($jenisNama, 'logam')) {
                                    $jenisIcon = '🥫';
                                    $jenisColor = '#64748b';
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
                            <tr style="{{ $isAnomali ? 'background: #fef7f7;' : '' }}">
                                <td class="text-center text-muted fw-semibold">{{ $transaksis->firstItem() + $index }}</td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($trx->mahasiswa->name ?? 'Unknown') }}&background=ecfdf5&color=047857&bold=true"
                                            alt="Avatar" class="avatar-square-soft flex-shrink-0">
                                        <div style="min-width: 0;">
                                            <div class="fw-bold text-truncate" style="color: #0f172a; max-width: 140px;">
                                                {{ $trx->mahasiswa->name ?? 'Mahasiswa Dihapus' }}
                                            </div>
                                            <div class="text-muted" style="font-family: monospace; font-size: 0.7rem;">
                                                {{ $trx->mahasiswa->nim ?? '-' }}
                                            </div>
                                            <small class="text-muted d-block"
                                                title="{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('l, d F Y H:i:s') }}"
                                                data-bs-toggle="tooltip" style="cursor: help; font-size: 0.7rem;">
                                                <i class="bi bi-clock"></i>
                                                {{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-medium" style="color: #334155; white-space: nowrap;"><i
                                            class="bi bi-hdd-network text-muted"></i>
                                        {{ $trx->bakSampah->nama ?? 'Bak Dihapus' }}</div>
                                    @if($trx->bakSampah && isset($trx->bakSampah->lokasi))
                                        <small class="text-muted text-truncate d-block" style="max-width: 130px; font-size: 0.7rem;"
                                            title="{{ $trx->bakSampah->lokasi->nama_lokasi ?? '-' }}"><i class="bi bi-geo-alt"></i>
                                            {{ $trx->bakSampah->lokasi->nama_lokasi ?? '-' }}</small>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge rounded-pill px-2 py-1"
                                        style="background: {{ $jenisColor }}15; color: {{ $jenisColor }}; font-weight: 600; font-size: 0.75rem; white-space: nowrap;">
                                        {{ $jenisIcon }} {{ $trx->jenisSampah->nama ?? 'Lainnya' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="fw-bold" style="color: #0f172a; font-size: 0.85rem; white-space: nowrap;">
                                        @if(($trx->berat ?? 0) >= 1000)
                                            {{ number_format($trx->berat / 1000, 2) }} kg
                                        @else
                                            {{ number_format($trx->berat ?? 0, 0) }} g
                                        @endif
                                    </div>
                                    <small class="text-muted" style="font-size: 0.7rem; white-space: nowrap;">
                                        <i class="bi bi-box-seam"></i> {{ $trx->jumlah_final ?? $trx->jumlah_botol ?? 0 }} pcs
                                    </small>
                                </td>

                                <td>
                                    <div class="fw-bold" style="font-size: 0.85rem; color: #059669; white-space: nowrap;"><i
                                            class="bi bi-star-fill text-warning"></i>
                                        +{{ number_format($trx->poin_didapat ?? 0) }} Pts</div>
                                    <div class="fw-bold" style="font-size: 0.75rem; color: #d97706; white-space: nowrap;"><i
                                            class="bi bi-coin text-warning"></i> +{{ number_format($trx->koin_didapat ?? 0) }}
                                        Koin</div>
                                </td>

                                <td class="text-center">
                                    @if(($trx->status_validasi ?? 'valid') === 'valid')
                                        <span class="badge px-3 py-1 rounded-2"
                                            style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;">Valid</span>
                                    @else
                                        <span class="badge px-3 py-1 rounded-2"
                                            style="background: #fff1f2; color: #dc2626; border: 1px solid #fecaca;"
                                            title="Peringatan Anomali Sensor">Anomali</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="{{ route($routePrefix . '.transaksi.show', $trx) }}" class="btn-icon btn-detail mx-auto"
                                        title="Lihat Detail Transaksi">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="border-0">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <div class="fw-bold text-secondary mb-1">Riwayat Kosong</div>
                                        <small>Belum ada data transaksi yang sesuai dengan filter.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($transaksis->hasPages())
            <div class="pagination-wrapper">
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Logic untuk Custom Dropdown Filter ---
            const filterItems = document.querySelectorAll('.filter-item');

            filterItems.forEach(item => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();

                    const value = this.getAttribute('data-val');
                    const labelText = this.innerHTML; // Mengambil HTML agar ikon ikut terbawa
                    const plainText = this.innerText.trim();
                    const targetInputId = this.getAttribute('data-target');
                    const targetLabelId = this.getAttribute('data-label');

                    document.getElementById(targetInputId).value = value;
                    document.getElementById(targetLabelId).innerText = plainText;

                    const parentUl = this.closest('.custom-dropdown-menu');
                    parentUl.querySelectorAll('.filter-item').forEach(opt => opt.classList.remove('active-filter'));
                    this.classList.add('active-filter');
                });
            });
        });
    </script>
@endpush