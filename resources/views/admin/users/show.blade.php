@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama - Radius Dikurangi (Tegas & Kotak) */
    .custom-card { 
        background: #ffffff; 
        border: 1px solid #e2e8f0; 
        border-radius: 12px; 
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); 
        overflow: hidden; 
    }

    /* Profil Bagian Kiri */
    .avatar-xl { 
        width: 100px; 
        height: 100px; 
        border-radius: 50%; 
        background: linear-gradient(135deg, #10b981 0%, #059669 100%); 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        color: #ffffff; 
        font-weight: 800; 
        font-size: 2.5rem; 
        box-shadow: 0 8px 16px rgba(16, 185, 129, 0.25); 
        border: 4px solid #ffffff;
        outline: 1px solid #e2e8f0;
    }

    .profile-name { font-size: 1.25rem; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; }
    .profile-role { font-size: 0.85rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .badge-status { padding: 6px 16px; border-radius: 6px; font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
    .status-active { background-color: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .status-inactive { background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    /* Profil Bagian Kanan (Detail Panel) */
    .panel-header { background-color: #ffffff; padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 0.75rem; }
    .panel-header h5 { color: #0f172a; font-weight: 700; margin: 0; }
    
    .section-label { font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1rem; display: block; }
    
    /* Soft Icon Box */
    .icon-box-soft {
        width: 42px; height: 42px;
        background-color: #f1f5f9;
        color: #64748b;
        border-radius: 8px; 
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        transition: all 0.3s;
    }
    
    .item-card:hover .icon-box-soft.primary-icon { background-color: #ecfdf5; color: #047857; }
    .item-card { padding: 0.5rem; transition: background 0.2s; border-radius: 8px; display: flex; align-items: center; }
    .item-card:hover { background-color: #f8fafc; }
    
    .item-label { font-size: 0.8rem; color: #64748b; margin-bottom: 2px; }
    .item-value { font-size: 0.95rem; font-weight: 700; color: #1e293b; }

    /* Info Card Kecil (Untuk mengisi ruang kosong) */
    .info-card-mini { background-color: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 10px; padding: 1rem; transition: all 0.2s; }
    .info-card-mini:hover { border-color: #10b981; background-color: #ffffff; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.05); }

    /* Tombol Outline Gelap */
    .btn-outline-dark-custom { border: 1px solid #cbd5e1; color: #0f172a; background: transparent; font-weight: 600; padding: 0.6rem 1.5rem; border-radius: 8px; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; }
    .btn-outline-dark-custom:hover { background-color: #0f172a; color: #ffffff; border-color: #0f172a; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4 animate-fade-up">
    <div class="d-flex align-items-center">
        <a href="{{ route('admin.users.index') }}" class="btn btn-light rounded-3 shadow-sm me-3 bg-white border" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Profil Pengguna</h4>
            <p class="text-muted mb-0 small">Detail informasi, kontak, dan hak akses sistem.</p>
        </div>
    </div>
    
    <div class="d-none d-md-block">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-success rounded-3 px-4 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: #10b981; border: none;">
            <i class="bi bi-pencil-square"></i> Edit Profil
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="custom-card p-4 text-center h-100 d-flex flex-column">
            <div class="mt-4 mb-3">
                <div class="avatar-xl mx-auto">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            </div>
            
            <h1 class="profile-name mt-2">{{ $user->name }}</h1>
            <div class="profile-role mb-3">
                @if($user->role === 'admin')
                    <i class="bi bi-shield-check text-success me-1"></i> Administrator
                @else
                    <i class="bi bi-person-badge text-secondary me-1"></i> Pengelola Sistem
                @endif
            </div>

            <div class="mb-4">
                @if($user->is_active)
                    <span class="badge-status status-active">
                        <i class="bi bi-check-circle-fill"></i> Akun Aktif
                    </span>
                @else
                    <span class="badge-status status-inactive">
                        <i class="bi bi-lock-fill"></i> Akun Terkunci
                    </span>
                @endif
            </div>

            <hr class="my-4 border-light" style="border-color: #f1f5f9 !important; width: 80%; margin-left: auto; margin-right: auto;">

            <div class="mt-auto mb-2 text-start px-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Registrasi:</span>
                    <span class="fw-bold text-dark small">{{ $user->created_at->format('d M Y') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">Terakhir Update:</span>
                    <span class="fw-bold text-dark small">{{ $user->updated_at->format('d M Y') }}</span>
                </div>
            </div>
            
            <a href="{{ route('admin.users.edit', $user) }}" class="btn-outline-dark-custom w-100 mt-4 d-md-none">
                <i class="bi bi-pencil-square"></i> Edit Profil
            </a>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="custom-card h-100 d-flex flex-column">
            <div class="panel-header">
                <i class="bi bi-person-lines-fill text-success fs-4"></i>
                <h5>Informasi Lengkap</h5>
            </div>
            
            <div class="card-body p-4 p-md-5 d-flex flex-column flex-grow-1">
                
                <div class="mb-4">
                    <span class="section-label">Informasi Kontak</span>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="item-card">
                                <div class="icon-box-soft primary-icon me-3"><i class="bi bi-envelope-at"></i></div>
                                <div>
                                    <div class="item-label">Alamat Email</div>
                                    <div class="item-value">{{ $user->email }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="item-card">
                                <div class="icon-box-soft primary-icon me-3"><i class="bi bi-telephone"></i></div>
                                <div>
                                    <div class="item-label">Nomor Telepon</div>
                                    <div class="item-value">{{ $user->phone ?? 'Belum diatur' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-light my-4" style="border-color: #f1f5f9 !important;">

                <div class="mb-4">
                    <span class="section-label">Otoritas & Sistem</span>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="item-card">
                                <div class="icon-box-soft me-3"><i class="bi bi-shield-lock"></i></div>
                                <div>
                                    <div class="item-label">Hak Akses / Role</div>
                                    <div class="item-value text-capitalize">{{ $user->role }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="item-card">
                                <div class="icon-box-soft me-3"><i class="bi bi-clock-history"></i></div>
                                <div>
                                    <div class="item-label">Detail Waktu Registrasi</div>
                                    <div class="item-value">{{ $user->created_at->format('H:i:s') }} WIB</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-auto pt-4 border-top" style="border-color: #f8fafc !important;">
                    <span class="section-label">Keamanan & Riwayat Sistem</span>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-card-mini d-flex align-items-center gap-3">
                                <i class="bi bi-shield-check text-success fs-3"></i>
                                <div>
                                    <div class="fw-bold" style="color: #0f172a; font-size: 0.9rem;">Autentikasi Aman</div>
                                    <div class="text-muted mt-1" style="font-size: 0.75rem;">Password dienkripsi sistem</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card-mini d-flex align-items-center gap-3">
                                <i class="bi bi-activity text-primary fs-3"></i>
                                <div>
                                    <div class="fw-bold" style="color: #0f172a; font-size: 0.9rem;">Status Riwayat</div>
                                    <div class="text-muted mt-1" style="font-size: 0.75rem;">Terakhir aktif: {{ $user->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection