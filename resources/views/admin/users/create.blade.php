@extends('layouts.admin')

@section('title', 'Tambah Pengguna Baru')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up {
        opacity: 0;
        transform: translateY(15px);
        animation: fadeUp 0.5s ease-out forwards;
    }
    @keyframes fadeUp {
        to { opacity: 1; transform: translateY(0); }
    }

    /* Card Utama Form */
    .form-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        background-color: #ffffff;
        overflow: hidden;
    }
    
    .form-header {
        background-color: #f9fafb;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    /* Styling Input Modern */
    .form-label {
        font-weight: 600;
        color: #4b5563;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .input-group-custom {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .input-group-custom i {
        position: absolute;
        left: 1.2rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 1.1rem;
        z-index: 10;
        transition: color 0.3s;
    }
    
    .form-control-modern {
        width: 100%;
        padding: 0.8rem 1rem 0.8rem 3rem; 
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.95rem;
        color: #1f2937;
        background-color: #fcfcfc;
        transition: all 0.3s;
    }
    
    .form-control-modern:focus {
        outline: none;
        border-color: #10b981;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }
    
    .form-control-modern:focus + i,
    .input-group-custom input:focus ~ i,
    .input-group-custom select:focus ~ i {
        color: #10b981;
    }

    select.form-control-modern {
        appearance: none;
        -moz-appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 16px 12px;
    }

    /* Alert / Bantuan Info Area Kiri */
    .info-panel {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 16px;
        padding: 2.5rem 2rem;
        height: 100%;
        color: #ffffff;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.2);
    }
    .info-panel-icon {
        width: 80px;
        height: 80px;
        background-color: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin-bottom: 1.5rem;
    }
    .info-panel h4 { font-weight: 800; margin-bottom: 1rem; }
    .info-panel p { color: rgba(255, 255, 255, 0.9); font-size: 0.95rem; line-height: 1.6; }
    .info-list { margin-top: 1.5rem; padding-left: 0; list-style: none; }
    .info-list li { margin-bottom: 0.8rem; display: flex; align-items: flex-start; gap: 10px; font-size: 0.9rem; }
    .info-list i { color: #fef08a; font-size: 1.1rem; }
</style>

<div class="d-flex align-items-center mb-4 animate-fade-up">
    <a href="{{ route('admin.users.index') }}" class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
        <i class="bi bi-arrow-left fs-5"></i>
    </a>
    <div>
        <h4 class="fw-bold text-dark mb-1">Tambah Pengguna Baru</h4>
        <p class="text-muted mb-0 small">Daftarkan akun administrator atau pengelola sistem yang baru.</p>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <div class="info-panel-icon">
                <i class="bi bi-person-plus-fill text-white"></i>
            </div>
            <h4>Akun Pengelola</h4>
            <p>Sistem Smart Waste Bank membagi hak akses menjadi dua peran utama:</p>
            
            <ul class="info-list">
                <li>
                    <i class="bi bi-shield-check"></i>
                    <div>
                        <strong>Administrator</strong><br>
                        <span class="text-white-50">Memiliki akses penuh ke seluruh menu, termasuk Master Data dan Laporan.</span>
                    </div>
                </li>
                <li>
                    <i class="bi bi-person-badge"></i>
                    <div>
                        <strong>Pengelola / Petugas</strong><br>
                        <span class="text-white-50">Hanya dapat mengakses data Mahasiswa, Transaksi, dan Voucher.</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <i class="bi bi-person-vcard fs-3 text-primary"></i>
                <h5 class="m-0 fw-bold text-dark">Formulir Pendaftaran Akun</h5>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="text" name="name" class="form-control-modern @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap..." value="{{ old('name') }}" required autofocus>
                                <i class="bi bi-person"></i>
                                @error('name') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="email" name="email" class="form-control-modern @error('email') is-invalid @enderror" placeholder="contoh@email.com" value="{{ old('email') }}" required>
                                <i class="bi bi-envelope"></i>
                                @error('email') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon</label>
                            <div class="input-group-custom">
                                <input type="text" name="phone" class="form-control-modern @error('phone') is-invalid @enderror" placeholder="0812xxxx..." value="{{ old('phone') }}">
                                <i class="bi bi-telephone"></i>
                                @error('phone') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <label class="form-label">Hak Akses (Role) <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <select name="role" class="form-control-modern @error('role') is-invalid @enderror" required>
                                    <option value="" disabled selected>Pilih peran pengguna...</option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh)</option>
                                    <option value="pengelola" {{ old('role') === 'pengelola' ? 'selected' : '' }}>Pengelola / Petugas (Akses Terbatas)</option>
                                </select>
                                <i class="bi bi-shield-check"></i>
                                @error('role') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 border-light">

                    <h6 class="fw-bold text-dark mb-4"><i class="bi bi-key me-2"></i>Keamanan Akun</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="password" name="password" class="form-control-modern @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                                <i class="bi bi-lock"></i>
                                @error('password') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="password" name="password_confirmation" class="form-control-modern" placeholder="Ulangi password di atas" required>
                                <i class="bi bi-lock-fill"></i>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-5">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light rounded-pill px-4 fw-medium border shadow-sm">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm d-flex align-items-center gap-2">
                            <i class="bi bi-person-plus-fill"></i> Simpan Pengguna
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection