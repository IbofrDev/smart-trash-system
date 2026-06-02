<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Smart Trash System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #10b981; 
            --sidebar-width: 260px;
            --header-height: 70px;
            --body-bg: #f8fafc; /* Abu-abu sangat terang untuk membedakan dengan sidebar putih */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            color: #334155;
            overflow-x: hidden;
        }
        
        /* SIDEBAR STYLING - MINIMALIST LIGHT */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: #ffffff; /* Putih bersih */
            z-index: 1000;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #f1f5f9; /* Garis batas super halus */
            transition: transform 0.3s ease;
        }
        
        .sidebar-brand {
            height: 80px;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .brand-icon {
            width: 40px; height: 40px;
            background-color: #064e3b; /* Hijau Tua/Pine */
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; color: #ffffff;
            margin-right: 12px;
            box-shadow: 0 4px 10px rgba(6, 78, 59, 0.2);
        }

        .sidebar-brand h5 { color: #0f172a; margin: 0; font-weight: 800; font-size: 1.1rem; letter-spacing: -0.5px; }
        .sidebar-brand small { color: #94a3b8; font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-top: -2px; }
        
        .sidebar-nav { flex-grow: 1; overflow-y: auto; padding-bottom: 1rem; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; display: none; }
        .sidebar-nav:hover::-webkit-scrollbar { display: block; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        
        .nav-section {
            padding: 1.25rem 1.75rem 0.5rem 1.75rem;
            font-size: 0.7rem; font-weight: 600;
            text-transform: capitalize; color: #94a3b8;
        }
        
        .sidebar-nav .nav-link {
            position: relative;
            display: flex; align-items: center;
            padding: 0.7rem 1rem; margin: 0.2rem 1rem; 
            color: #64748b; 
            text-decoration: none;
            border-radius: 12px; 
            font-weight: 500; font-size: 0.9rem;
            transition: all 0.2s ease;
            overflow: hidden;
        }

        .sidebar-nav .nav-link i { font-size: 1.15rem; width: 24px; margin-right: 12px; text-align: center; transition: color 0.2s; opacity: 0.8; }
        
        .sidebar-nav .nav-link:hover {
            color: #0f172a; 
            background-color: #f8fafc;
        }
        .sidebar-nav .nav-link:hover i { opacity: 1; }

        /* TEMA MENU AKTIF: SENTUHAN HIJAU TUA (DARK EMERALD) */
        .sidebar-nav .nav-link.active {
            background-color: #f1f5f9; /* Latar abu-abu sangat muda */
            color: #047857 !important; /* Teks hijau tua yang elegan */
            font-weight: 700;
        }
        
        .sidebar-nav .nav-link.active i {
            color: #047857 !important; /* Ikon hijau tua */
            opacity: 1;
        }

        /* Borderline (Garis) Hijau Tua di sebelah kiri */
        .sidebar-nav .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: #047857; /* Warna Hijau Tua */
            border-radius: 12px 0 0 12px; /* Mengikuti lengkungan menu */
        }

        /* BADGE PASTEL */
        .menu-badge {
            background-color: #dcfce7; /* Soft Mint Green */
            color: #166534; /* Dark Green Text */
            font-size: 0.7rem; font-weight: 700;
            padding: 3px 8px; border-radius: 6px;
            margin-left: auto;
        }

        .sidebar-footer { padding: 1.5rem 1rem; margin-top: auto; }
        .help-card {
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 14px; padding: 1.25rem 1rem; text-align: center;
        }
        .help-card h6 { color: #0f172a; font-size: 0.85rem; font-weight: 700; margin-bottom: 8px; }
        .help-card p { font-size: 0.75rem; color: #64748b; margin-bottom: 12px; line-height: 1.4; }
        .btn-help {
            background: #ffffff; color: #334155;
            font-size: 0.8rem; font-weight: 600; padding: 0.5rem;
            border-radius: 8px; display: block; text-decoration: none;
            transition: all 0.2s; border: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .btn-help:hover { background: #f1f5f9; color: #047857; border-color: #047857; }

        /* MAIN CONTENT */
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }
        .main-header {
            height: var(--header-height); background: var(--body-bg);
            padding: 0 2rem;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        
        .user-dropdown .btn {
            display: flex; align-items: center; gap: 0.75rem; padding: 0.3rem 0.8rem 0.3rem 0.3rem; 
            border: 1px solid #e2e8f0; border-radius: 50px; background: #ffffff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        
        .user-dropdown .avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background-color: #064e3b;
            display: flex; align-items: center; justify-content: center;
            color: #ffffff; font-weight: 600; font-size: 0.85rem;
        }
        
        .content-wrapper { padding: 1rem 2rem 2rem 2rem; flex-grow: 1; }
        
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="bi bi-recycle"></i></div>
            <div>
                <h5>Smart Trash</h5>
                <small>Management System</small>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house"></i>
                <span>Dashboard</span>
            </a>

            @if(auth()->user()->role === 'admin')
            <div class="nav-section">Master Data</div>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i><span>Users</span>
            </a>
            <a href="{{ route('admin.lokasi.index') }}" class="nav-link {{ request()->routeIs('admin.lokasi.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt"></i><span>Lokasi</span>
            </a>
            <a href="{{ route('admin.bak-sampah.index') }}" class="nav-link {{ request()->routeIs('admin.bak-sampah.*') ? 'active' : '' }}">
                <i class="bi bi-trash3"></i><span>Bak Sampah</span>
            </a>
            <a href="{{ route('admin.jenis-sampah.index') }}" class="nav-link {{ request()->routeIs('admin.jenis-sampah.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i><span>Jenis Sampah</span>
            </a>
            <a href="{{ route('admin.level.index') }}" class="nav-link {{ request()->routeIs('admin.level.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-steps"></i><span>Level</span>
            </a>
            <a href="{{ route('admin.achievement.index') }}" class="nav-link {{ request()->routeIs('admin.achievement.*') ? 'active' : '' }}">
                <i class="bi bi-trophy"></i><span>Achievement</span>
            </a>
            <a href="{{ route('admin.setting-poin.index') }}" class="nav-link {{ request()->routeIs('admin.setting-poin.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i><span>Setting Poin</span>
            </a>
            @endif

            <div class="nav-section">Data Transaksi</div>
            <a href="{{ route('admin.mahasiswa.index') }}" class="nav-link {{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i><span>Mahasiswa</span>
            </a>
            <a href="{{ route('admin.transaksi.index') }}" class="nav-link {{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}">
                <i class="bi bi-receipt-cutoff"></i>
                <span>Transaksi</span>
                <span class="menu-badge">Baru</span>
            </a>
            <a href="{{ route('admin.voucher.index') }}" class="nav-link {{ request()->routeIs('admin.voucher.*') || request()->routeIs('pengelola.voucher.*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated"></i><span>Voucher</span>
            </a>

            <div class="nav-section">Laporan</div>
            <a href="{{ route('admin.laporan.transaksi') }}" class="nav-link {{ request()->routeIs('admin.laporan.transaksi*') ? 'active' : '' }}">
                <i class="bi bi-pie-chart"></i><span>Laporan Transaksi</span>
            </a>
            <a href="{{ route('admin.laporan.mahasiswa') }}" class="nav-link {{ request()->routeIs('admin.laporan.mahasiswa*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-person"></i><span>Laporan Mahasiswa</span>
            </a>

            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.log-aktivitas.index') }}" class="nav-link {{ request()->routeIs('admin.log-aktivitas.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i><span>Log Aktivitas</span>
            </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="help-card">
                <h6>Butuh Bantuan?</h6>
                <p>Panduan lengkap sistem admin.</p>
                <a href="javascript:void(0)" onclick="alert('Buku Panduan sedang dalam tahap penyusunan. Akan tersedia pada rilis final Tugas Akhir.')" class="btn-help">
                    Buka Panduan
                </a>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <header class="main-header">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link d-lg-none p-0 text-dark" id="sidebarToggle">
                    <i class="bi bi-list" style="font-size:1.8rem;"></i>
                </button>
                <h4 class="mb-0 fw-bold" style="letter-spacing: -0.5px; color: #0f172a;">@yield('title', 'Dashboard')</h4>
            </div>

            <div class="dropdown user-dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                    <span class="user-name d-none d-md-inline fw-semibold text-secondary" style="font-size: 0.9rem;">{{ auth()->user()->name ?? 'Admin' }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3 mt-2">
                    <li class="px-3 py-2">
                        <div class="fw-bold text-dark">{{ auth()->user()->name }}</div>
                        <div class="text-muted small">{{ auth()->user()->email }}</div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger fw-semibold d-flex align-items-center">
                                <i class="bi bi-box-arrow-right me-2"></i>Keluar Sistem
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <div class="content-wrapper">
            @include('components.alert')
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            if(toggleBtn) {
                toggleBtn.addEventListener('click', () => sidebar.classList.toggle('show'));
            }
        });
    </script>
    @stack('scripts')
</body>
</html>