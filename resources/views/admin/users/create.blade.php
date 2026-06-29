@extends('layouts.admin')

@section('title', 'Tambah Pengguna Baru')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama Form - Radius Dikurangi */
    .form-card { border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }
    .form-header { background-color: #ffffff; padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 1rem; }

    /* Styling Input Modern - Radius Dikurangi */
    .form-label { font-weight: 600; color: #475569; font-size: 0.85rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .input-group-custom { position: relative; margin-bottom: 1.5rem; }
    .input-group-custom i { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.1rem; z-index: 10; transition: color 0.3s; }
    
    .form-control-modern { width: 100%; padding: 0.8rem 1rem 0.8rem 3rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; color: #0f172a; background-color: #f8fafc; transition: all 0.3s; }
    .form-control-modern:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
    .form-control-modern:focus + i, .input-group-custom input:focus ~ i, .input-group-custom select:focus ~ i { color: #10b981; }

    select.form-control-modern { appearance: none; -moz-appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748b' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1.2rem center; background-size: 16px 12px; }

    /* Alert / Bantuan Info Area Kiri - Radius Dikurangi */
    .info-panel { background-color: #ffffff; border: 1px solid #f1f5f9; border-left: 6px solid #10b981; border-radius: 12px; padding: 2.5rem 2rem; height: 100%; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); }
    .info-panel-icon { width: 64px; height: 64px; background-color: #ecfdf5; color: #047857; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 1.5rem; }
    .info-panel h4 { font-weight: 800; color: #0f172a; margin-bottom: 1rem; }
    .info-panel p { color: #64748b; font-size: 0.95rem; line-height: 1.6; }
    .info-list { margin-top: 1.5rem; padding-left: 0; list-style: none; }
    .info-list li { margin-bottom: 1rem; display: flex; align-items: flex-start; gap: 12px; font-size: 0.9rem; color: #334155; }
    .info-list i { color: #10b981; font-size: 1.2rem; }
</style>

<div class="d-flex align-items-center mb-4 animate-fade-up">
    <a href="{{ route($routePrefix . '.users.index') }}" class="btn btn-light rounded-3 shadow-sm me-3 bg-white border" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a;">
        <i class="bi bi-arrow-left fs-5"></i>
    </a>
    <div>
        <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Tambah Pengguna Baru</h4>
        <p class="text-muted mb-0 small">Daftarkan akun administrator atau pengelola sistem yang baru.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <div class="info-panel-icon">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <h4>Hak Akses Sistem</h4>
            <p>Sistem Smart Waste Bank membagi otoritas pengguna menjadi dua peran utama:</p>
            <ul class="info-list">
                <li>
                    <i class="bi bi-shield-check"></i>
                    <div>
                        <strong style="color: #0f172a;">Administrator</strong><br>
                        <span class="text-muted small">Akses penuh ke seluruh menu, konfigurasi Master Data, Level, dan pengaturan Poin Gamifikasi.</span>
                    </div>
                </li>
                <li>
                    <i class="bi bi-person-badge"></i>
                    <div>
                        <strong style="color: #0f172a;">Pengelola / Petugas</strong><br>
                        <span class="text-muted small">Akses operasional harian: pantau Transaksi, verifikasi Voucher, dan kelola data Mahasiswa.</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <h5 class="m-0 fw-bold" style="color: #0f172a;"><i class="bi bi-person-vcard text-success me-2"></i>Formulir Identitas Baru</h5>
            </div>
            <div class="card-body p-4 p-md-5">
                <form action="{{ route($routePrefix . '.users.store') }}" method="POST">
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
                    <h6 class="fw-bold mb-4" style="color: #0f172a;"><i class="bi bi-key me-2 text-success"></i>Keamanan Akun</h6>

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
                        <a href="{{ route($routePrefix . '.users.index') }}" class="btn bg-white border rounded-3 px-4 fw-bold" style="color: #64748b;">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success rounded-3 px-5 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: #10b981; border: none;">
                            <i class="bi bi-person-plus-fill"></i> Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection