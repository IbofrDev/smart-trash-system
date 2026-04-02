<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Smart Trash System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #10b981;
            --primary-dark: #059669;
            --sidebar-width: 260px;
            --header-height: 60px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1f2937 0%, #111827 100%);
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar-brand {
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand img {
            width: 40px;
            height: 40px;
        }
        
        .sidebar-brand h5 {
            color: #fff;
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-section {
            padding: 0.5rem 1.25rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin-top: 0.5rem;
        }
        
        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.25rem;
            color: #9ca3af;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        
        .sidebar-nav .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.05);
        }
        
        .sidebar-nav .nav-link.active {
            color: #fff;
            background: rgba(16, 185, 129, 0.1);
            border-left-color: var(--primary-color);
        }
        
        .sidebar-nav .nav-link i {
            font-size: 1.1rem;
            width: 24px;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        
        /* Header */
        .main-header {
            height: var(--header-height);
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .main-header h4 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937;
        }
        
        .user-dropdown .btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background: #fff;
        }
        
        .user-dropdown .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        /* Content */
        .content-wrapper {
            padding: 1.5rem;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }
        
        /* Stats Card */
        .stats-card {
            padding: 1.25rem;
            border-radius: 0.75rem;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .stats-card .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stats-card .stats-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
        }
        
        .stats-card .stats-label {
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        /* Table */
        .table th {
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #6b7280;
            border-bottom-width: 1px;
        }
        
        .table td {
            vertical-align: middle;
            padding: 0.875rem 0.75rem;
        }
        
        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        /* Badge */
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div style="width:40px;height:40px;background:var(--primary-color);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-recycle text-white" style="font-size:1.5rem;"></i>
            </div>
            <div>
                <h5>Smart Trash</h5>
                <small class="text-muted" style="font-size:0.7rem;">Management System</small>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section">Menu Utama</div>
            
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
            
            @if(auth()->user()->role === 'admin')
            <div class="nav-section">Master Data</div>
            
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Users</span>
            </a>
            
            <a href="{{ route('admin.lokasi.index') }}" class="nav-link {{ request()->routeIs('admin.lokasi.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt-fill"></i>
                <span>Lokasi</span>
            </a>
            
            <a href="{{ route('admin.bak-sampah.index') }}" class="nav-link {{ request()->routeIs('admin.bak-sampah.*') ? 'active' : '' }}">
                <i class="bi bi-trash3-fill"></i>
                <span>Bak Sampah</span>
            </a>
            
            <a href="{{ route('admin.jenis-sampah.index') }}" class="nav-link {{ request()->routeIs('admin.jenis-sampah.*') ? 'active' : '' }}">
                <i class="bi bi-tags-fill"></i>
                <span>Jenis Sampah</span>
            </a>
            
            <a href="{{ route('admin.level.index') }}" class="nav-link {{ request()->routeIs('admin.level.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i>
                <span>Level</span>
            </a>
            
            <a href="{{ route('admin.achievement.index') }}" class="nav-link {{ request()->routeIs('admin.achievement.*') ? 'active' : '' }}">
                <i class="bi bi-trophy-fill"></i>
                <span>Achievement</span>
            </a>
            
            <a href="{{ route('admin.setting-poin.index') }}" class="nav-link {{ request()->routeIs('admin.setting-poin.*') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i>
                <span>Setting Poin</span>
            </a>
            @endif
            
            <div class="nav-section">Data Transaksi</div>
            
            <a href="{{ route('admin.mahasiswa.index') }}" class="nav-link {{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}">
                <i class="bi bi-mortarboard-fill"></i>
                <span>Mahasiswa</span>
            </a>
            
            <a href="{{ route('admin.transaksi.index') }}" class="nav-link {{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i>
                <span>Transaksi</span>
            </a>
            
            <div class="nav-section">Laporan</div>
            
            <a href="{{ route('admin.laporan.transaksi') }}" class="nav-link {{ request()->routeIs('admin.laporan.transaksi*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Laporan Transaksi</span>
            </a>
            
            <a href="{{ route('admin.laporan.mahasiswa') }}" class="nav-link {{ request()->routeIs('admin.laporan.mahasiswa*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-person"></i>
                <span>Laporan Mahasiswa</span>
            </a>
            
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.log-aktivitas.index') }}" class="nav-link {{ request()->routeIs('admin.log-aktivitas.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i>
                <span>Log Aktivitas</span>
            </a>
            @endif
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="main-header">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link d-lg-none p-0 text-dark" id="sidebarToggle">
                    <i class="bi bi-list" style="font-size:1.5rem;"></i>
                </button>
                <h4>@yield('title', 'Dashboard')</h4>
            </div>
            
            <div class="dropdown user-dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text text-muted small">{{ auth()->user()->email }}</span></li>
                    <li><span class="dropdown-item-text"><span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span></span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>
        
        <!-- Content -->
        <div class="content-wrapper">
            @include('components.alert')
            @yield('content')
        </div>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar Toggle (Mobile)
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
    </script>
    
    @stack('scripts')
</body>
</html>