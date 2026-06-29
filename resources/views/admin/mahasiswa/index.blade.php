@extends('layouts.admin')

@section('title', 'Kelola Data Mahasiswa')

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

        /* Card Utama - Minimalis & Kotak */
        .custom-card {
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            background-color: #ffffff;
            overflow: hidden;
        }

        /* Header & Toolbar Filter */
        .table-toolbar {
            padding: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            background-color: #ffffff;
        }

        .search-box {
            position: relative;
            flex-grow: 1;
            max-width: 350px;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .search-box input {
            width: 100%;
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.2s;
            background-color: #f8fafc;
        }

        .search-box input:focus {
            outline: none;
            border-color: #10b981;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        /* Custom Dropdown Modern - Kotak */
        .modern-dropdown-btn {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.6rem 1.25rem;
            color: #475569;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            justify-content: space-between;
            align-items: center;
            min-width: 160px;
            transition: all 0.2s;
        }

        .modern-dropdown-btn:hover,
        .modern-dropdown-btn:focus,
        .modern-dropdown-btn.show {
            background-color: #ffffff;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            color: #0f172a;
        }

        .custom-dropdown-menu {
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            padding: 0.5rem;
            min-width: 180px;
            margin-top: 0.5rem !important;
            max-height: 300px;
            overflow-y: auto;
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

        /* Avatar Sesuai Pedoman (Kotak Halus) */
        .avatar-square-soft {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
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
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 1rem 1.5rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .table-clean td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid #f8fafc;
            color: #334155;
        }

        .table-clean tbody tr {
            transition: background-color 0.2s;
        }

        .table-clean tbody tr:hover {
            background-color: #f8fafc;
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

        .btn-edit:hover {
            background-color: #eff6ff;
            color: #2563eb;
            border-color: #bfdbfe;
        }

        .btn-delete:hover {
            background-color: #fef2f2;
            color: #ef4444;
            border-color: #fecaca;
        }

        /* Empty State & Badges */
        .empty-state {
            padding: 4rem 1rem;
            text-align: center;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 3.5rem;
            color: #e2e8f0;
            display: block;
            margin-bottom: 1rem;
        }

        .pagination-wrapper {
            padding: 1.25rem 1.5rem;
            background-color: #ffffff;
            border-top: 1px solid #f1f5f9;
        }
    </style>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animate-fade-up">
        <div class="mb-3 mb-md-0">
            <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Direktori Mahasiswa</h4>
            <p class="text-muted mb-0 small">Pantau data, identitas RFID, dan pencapaian poin mahasiswa.</p>
        </div>
    </div>

    <div class="custom-card animate-fade-up" style="animation-delay: 0.1s;">

        <form action="{{ route($routePrefix . '.mahasiswa.index') }}" method="GET" class="table-toolbar">

            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" name="search" placeholder="Cari nama, email, NIM, atau RFID..."
                    value="{{ request('search') }}">
            </div>

            <input type="hidden" name="level_id" id="filterLevel" value="{{ request('level_id') }}">

            <div class="d-flex flex-wrap gap-2 align-items-center">

                <div class="dropdown">
                    <button class="btn modern-dropdown-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span id="textLevel">
                            @php
                                $levelAktif = $levels->firstWhere('id', request('level_id'));
                                echo $levelAktif ? $levelAktif->nama_level : 'Semua Peringkat';
                            @endphp
                        </span>
                    </button>
                    <ul class="dropdown-menu custom-dropdown-menu">
                        <li><a class="dropdown-item custom-dropdown-item filter-opt {{ request('level_id') == '' ? 'active-filter' : '' }}"
                                href="#" data-target="filterLevel" data-label="textLevel" data-value="">Semua Peringkat</a>
                        </li>

                        @foreach($levels as $level)
                            <li>
                                <a class="dropdown-item custom-dropdown-item filter-opt {{ request('level_id') == $level->id ? 'active-filter' : '' }}"
                                    href="#" data-target="filterLevel" data-label="textLevel" data-value="{{ $level->id }}">
                                    <i class="bi bi-award text-success me-1"></i> {{ $level->nama_level }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <button type="submit" class="btn btn-dark rounded-3 px-4 fw-medium shadow-sm" style="background: #0f172a;">
                    Terapkan
                </button>

                @if(request()->hasAny(['search', 'level_id']) && (request('search') != '' || request('level_id') != ''))
                    <a href="{{ route($routePrefix . '.mahasiswa.index') }}"
                        class="btn btn-light border rounded-3 px-3 text-danger fw-medium" title="Reset Filter">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-clean">
                <thead>
                    <tr>
                        <th width="5%" class="ps-4">No</th>
                        <th width="30%">Profil Mahasiswa</th>
                        <th width="20%">Identitas Academic</th>
                        <th width="15%">Peringkat (Tier)</th>
                        <th width="15%">Saldo Poin</th>
                        <th width="15%" class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $index => $mhs)
                        <tr>
                            <td class="text-muted fw-semibold ps-4">{{ $mahasiswas->firstItem() + $index }}</td>

                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($mhs->name) }}&background=ecfdf5&color=047857&bold=true"
                                        alt="Avatar" class="avatar-square-soft">
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark" style="color: #0f172a;">{{ $mhs->name }}</h6>
                                        <small class="text-muted">{{ $mhs->email }}</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold" style="color: #0f172a; font-size: 0.95rem;">
                                    {{ $mhs->nim ?? 'NIM Kosong' }}
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem; font-family: monospace;">
                                    @if($mhs->rfid_uid)
                                        <i class="bi bi-upc-scan text-success me-1"></i>{{ $mhs->rfid_uid }}
                                    @else
                                        <i class="bi bi-dash-circle me-1"></i>Belum Tap Kartu
                                    @endif
                                </div>
                            </td>

                            <td>
                                @if($mhs->level)
                                    <span class="badge"
                                        style="background: #f8fafc; color: #0f172a; border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 6px;">
                                        <i class="bi bi-shield-fill-check me-1 text-success"></i> {{ $mhs->level->nama_level }}
                                    </span>
                                @else
                                    <span class="badge"
                                        style="background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 6px;">Unranked</span>
                                @endif
                            </td>

                            <td>
                                <div class="badge"
                                    style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; padding: 6px 12px; border-radius: 6px; font-weight: 700;">
                                    <i class="bi bi-star-fill text-warning me-2"></i>{{ number_format($mhs->total_poin) }}
                                </div>
                            </td>

                            <td class="pe-4">
                                <div class="action-btns">
                                    <a href="{{ route($routePrefix . '.mahasiswa.show', $mhs) }}" class="btn-icon btn-detail"
                                        title="Detail Riwayat">
                                        <i class="bi bi-person-vcard"></i>
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route($routePrefix . '.mahasiswa.edit', $mhs) }}" class="btn-icon btn-edit"
                                            title="Edit Data">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="button" class="btn-icon btn-delete" title="Hapus Akun"
                                            onclick="confirmDelete('{{ route($routePrefix . '.mahasiswa.destroy', $mhs) }}', '{{ $mhs->name }}')">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border-0">
                                <div class="empty-state">
                                    <i class="bi bi-people"></i>
                                    <div class="fw-bold text-secondary mb-1">Belum Ada Mahasiswa</div>
                                    <small>Data pengguna mahasiswa belum terdaftar di sistem.</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mahasiswas->hasPages())
            <div class="pagination-wrapper">
                {{ $mahasiswas->links() }}
            </div>
        @endif
    </div>

    @include('components.delete-modal')

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterOptions = document.querySelectorAll('.filter-opt');

            filterOptions.forEach(option => {
                option.addEventListener('click', function (e) {
                    e.preventDefault();

                    const value = this.getAttribute('data-value');
                    const labelText = this.innerText.trim();
                    const targetInputId = this.getAttribute('data-target');
                    const targetLabelId = this.getAttribute('data-label');

                    document.getElementById(targetInputId).value = value;
                    document.getElementById(targetLabelId).innerText = labelText;

                    const parentUl = this.closest('.custom-dropdown-menu');
                    parentUl.querySelectorAll('.filter-opt').forEach(opt => opt.classList.remove('active-filter'));
                    this.classList.add('active-filter');
                });
            });
        });
    </script>
@endpush