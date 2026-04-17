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
            --primary-dark: #059669;  
            --sidebar-bg: #111827;    
            --sidebar-width: 260px;
            --header-height: 70px;
            --body-bg: #f3f4f6;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            color: #374151;
            overflow-x: hidden;
        }
        
        /* SIDEBAR STYLING */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--sidebar-bg);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        
        .sidebar-brand {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            margin-bottom: 0.5rem;
        }
        
        .brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; color: #ffffff;
            margin-right: 12px;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        }

        .sidebar-brand h5 { color: #ffffff; margin: 0; font-weight: 700; font-size: 1.1rem; }
        .sidebar-brand small { color: #9ca3af; font-size: 0.7rem; font-weight: 500; text-transform: uppercase; }
        
        .sidebar-nav { flex-grow: 1; overflow-y: auto; padding-bottom: 1rem; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }
        
        .nav-section {
            padding: 1.5rem 1.5rem 0.5rem 1.5rem;
            font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.5px; color: #6b7280;
        }
        
        .sidebar-nav .nav-link {
            position: relative;
            display: flex; align-items: center;
            padding: 0.75rem 1rem; margin: 0.25rem 1rem; 
            color: #9ca3af; text-decoration: none;
            border-radius: 12px; font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar-nav .nav-link i { font-size: 1.1rem; width: 24px; margin-right: 12px; text-align: center; }
        
        .sidebar-nav .nav-link:hover {
            color: #ffffff; background-color: rgba(255, 255, 255, 0.05);
            transform: translateX(4px); 
        }

        .sidebar-nav .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: #ffffff; font-weight: 600;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35); 
        }

        .menu-badge {
            background-color: #ef4444; color: #fff;
            font-size: 0.65rem; font-weight: 800;
            padding: 3px 8px; border-radius: 20px;
            margin-left: auto;
        }

        .sidebar-footer { padding: 1.5rem 1rem; margin-top: auto; }
        .help-card {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 16px; padding: 1.25rem 1rem; text-align: center;
        }
        .help-card h6 { color: #ffffff; font-size: 0.9rem; font-weight: 700; }
        .btn-help {
            background: var(--primary-color); color: #ffffff;
            font-size: 0.8rem; font-weight: 600; padding: 0.5rem;
            border-radius: 8px; display: block; text-decoration: none;
        }

        /* MAIN CONTENT */
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }
        .main-header {
            height: var(--header-height); background: #ffffff;
            border-bottom: 1px solid #e5e7eb; padding: 0 2rem;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        
        .user-dropdown .btn {
            display: flex; align-items: center; gap: 0.75rem; padding: 0.4rem 1rem 0.4rem 0.4rem; 
            border: 1px solid #e5e7eb; border-radius: 50px; background: #ffffff;
        }
        
        .user-dropdown .avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            display: flex; align-items: center; justify-content: center;
            color: #ffffff; font-weight: 700;
        }
        
        .content-wrapper { padding: 2rem; flex-grow: 1; }
        
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
            <div class="nav-section">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>

            @if(auth()->user()->role === 'admin')
            <div class="nav-section">Master Data</div>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i><span>Users</span>
            </a>
            <a href="{{ route('admin.lokasi.index') }}" class="nav-link {{ request()->routeIs('admin.lokasi.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt-fill"></i><span>Lokasi</span>
            </a>
            <a href="{{ route('admin.bak-sampah.index') }}" class="nav-link {{ request()->routeIs('admin.bak-sampah.*') ? 'active' : '' }}">
                <i class="bi bi-trash3-fill"></i><span>Bak Sampah</span>
            </a>
            <a href="{{ route('admin.jenis-sampah.index') }}" class="nav-link {{ request()->routeIs('admin.jenis-sampah.*') ? 'active' : '' }}">
                <i class="bi bi-tags-fill"></i><span>Jenis Sampah</span>
            </a>
            <a href="{{ route('admin.level.index') }}" class="nav-link {{ request()->routeIs('admin.level.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-steps"></i><span>Level</span>
            </a>
            <a href="{{ route('admin.achievement.index') }}" class="nav-link {{ request()->routeIs('admin.achievement.*') ? 'active' : '' }}">
                <i class="bi bi-trophy-fill"></i><span>Achievement</span>
            </a>
            <a href="{{ route('admin.setting-poin.index') }}" class="nav-link {{ request()->routeIs('admin.setting-poin.*') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i><span>Setting Poin</span>
            </a>
            @endif

            <div class="nav-section">Data Transaksi</div>
            <a href="{{ route('admin.mahasiswa.index') }}" class="nav-link {{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill"></i><span>Mahasiswa</span>
            </a>
            <a href="{{ route('admin.transaksi.index') }}" class="nav-link {{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}">
                <i class="bi bi-receipt-cutoff"></i>
                <span>Transaksi</span>
                <span class="menu-badge">Baru</span>
            </a>
            <a href="{{ route('admin.voucher.index') }}" class="nav-link {{ request()->routeIs('admin.voucher.*') || request()->routeIs('pengelola.voucher.*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated-fill"></i><span>Voucher</span>
            </a>

            <div class="nav-section">Laporan</div>
            <a href="{{ route('admin.laporan.transaksi') }}" class="nav-link {{ request()->routeIs('admin.laporan.transaksi*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph-fill"></i><span>Laporan Transaksi</span>
            </a>
            <a href="{{ route('admin.laporan.mahasiswa') }}" class="nav-link {{ request()->routeIs('admin.laporan.mahasiswa*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-person-fill"></i><span>Laporan Mahasiswa</span>
            </a>

            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.log-aktivitas.index') }}" class="nav-link {{ request()->routeIs('admin.log-aktivitas.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i><span>Log Aktivitas</span>
            </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="help-card">
                <i class="bi bi-question-circle-fill"></i>
                <h6>Butuh Bantuan?</h6>
                <a href="#" class="btn-help">Buka Panduan</a>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <header class="main-header">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link d-lg-none p-0 text-dark" id="sidebarToggle">
                    <i class="bi bi-list" style="font-size:1.8rem;"></i>
                </button>
                <h4>@yield('title', 'Dashboard')</h4>
            </div>

            <div class="dropdown user-dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                    <span class="user-name d-none d-md-inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
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