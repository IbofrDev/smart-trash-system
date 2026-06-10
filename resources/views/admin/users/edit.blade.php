@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama Form - Radius Dikurangi */
    .form-card { border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }
    .form-header { background-color: #ffffff; padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 1rem; }
    
    .avatar-lg { width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); display: flex; align-items: center; justify-content: center; color: #ffffff; font-weight: 800; font-size: 1.8rem; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2); }

    /* Styling Input Modern - Radius Dikurangi */
    .form-label { font-weight: 600; color: #475569; font-size: 0.85rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .input-group-custom { position: relative; margin-bottom: 1.5rem; }
    .input-group-custom i { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.1rem; z-index: 10; transition: color 0.3s; }
    
    .form-control-modern { width: 100%; padding: 0.8rem 1rem 0.8rem 3rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; color: #0f172a; background-color: #f8fafc; transition: all 0.3s; }
    .form-control-modern:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
    .form-control-modern:focus + i, .input-group-custom input:focus ~ i, .input-group-custom select:focus ~ i { color: #10b981; }

    select.form-control-modern { appearance: none; -moz-appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748b' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1.2rem center; background-size: 16px 12px; }

    /* Switch Toggle Modern - Radius Dikurangi */
    .form-switch .form-check-input { width: 3.5em; height: 1.8em; cursor: pointer; border-radius: 6px; background-color: #cbd5e1; border: none; }
    .form-switch .form-check-input:checked { background-color: #10b981; }
    .form-switch .form-check-input:focus { box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2); }
    .form-switch .form-check-label { font-weight: 700; cursor: pointer; padding-top: 0.3rem; margin-left: 0.5rem; }

    /* Alert / Bantuan Info Area Kiri */
    .info-panel { background-color: #ffffff; border: 1px solid #f1f5f9; border-radius: 12px; padding: 2.5rem 2rem; height: 100%; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); }
    .info-panel h5 { font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; }
    .info-panel p { color: #64748b; font-size: 0.9rem; line-height: 1.6; }
    
    .danger-zone { background-color: #fff1f2; border: 1px dashed #fecaca; border-radius: 8px; padding: 1.5rem; margin-top: 1.5rem; }
</style>

<div class="d-flex align-items-center mb-4 animate-fade-up">
    <a href="{{ route('admin.users.index') }}" class="btn btn-light rounded-3 shadow-sm me-3 bg-white border" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a;">
        <i class="bi bi-arrow-left fs-5"></i>
    </a>
    <div>
        <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Edit Data Pengguna</h4>
        <p class="text-muted mb-0 small">Perbarui informasi profil atau hak akses pengguna.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <div class="text-center mb-4">
                <div class="avatar-lg mx-auto mb-3">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 class="mb-1">{{ $user->name }}</h5>
                <span class="badge {{ $user->role === 'admin' ? 'bg-f1f5f9 text-0f172a' : 'bg-f8fafc text-475569' }} border rounded-2 px-3 py-1 mt-1">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
            
            <hr class="my-4 border-light" style="border-color: #f1f5f9 !important;">
            
            <h6 class="fw-bold" style="color: #0f172a;"><i class="bi bi-info-circle me-2 text-success"></i>Petunjuk Keamanan</h6>
            <p class="mb-4">Kosongkan kolom <strong>Password Baru</strong> di formulir kanan jika Anda tidak ingin mengubah kata sandi saat ini.</p>

            <div class="danger-zone">
                <h6 class="fw-bold text-danger mb-2"><i class="bi bi-shield-x me-2"></i>Status Akses Login</h6>
                <p class="text-muted small mb-3">Akun yang dinonaktifkan tidak akan dapat masuk ke sistem.</p>
                <div class="form-check form-switch d-flex align-items-center">
                    <input type="hidden" name="is_active" value="0" form="editUserForm">
                    <input class="form-check-input m-0" type="checkbox" id="statusSwitch" name="is_active" value="1" form="editUserForm" {{ $user->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="statusSwitch">Akun Aktif</label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <h5 class="m-0 fw-bold" style="color: #0f172a;"><i class="bi bi-person-lines-fill text-success me-2"></i>Formulir Perubahan Identitas</h5>
            </div>
            <div class="card-body p-4 p-md-5">
                <form id="editUserForm" action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="text" name="name" class="form-control-modern @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap..." value="{{ old('name', $user->name) }}" required>
                                <i class="bi bi-person"></i>
                                @error('name') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="email" name="email" class="form-control-modern @error('email') is-invalid @enderror" placeholder="contoh@email.com" value="{{ old('email', $user->email) }}" required>
                                <i class="bi bi-envelope"></i>
                                @error('email') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon</label>
                            <div class="input-group-custom">
                                <input type="text" name="phone" class="form-control-modern @error('phone') is-invalid @enderror" placeholder="0812xxxx..." value="{{ old('phone', $user->phone ?? '') }}">
                                <i class="bi bi-telephone"></i>
                                @error('phone') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <label class="form-label">Hak Akses (Role) <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <select name="role" class="form-control-modern @error('role') is-invalid @enderror" required>
                                    <option value="" disabled>Pilih peran pengguna...</option>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh)</option>
                                    <option value="pengelola" {{ old('role', $user->role) === 'pengelola' ? 'selected' : '' }}>Pengelola / Petugas (Akses Terbatas)</option>
                                </select>
                                <i class="bi bi-shield-check"></i>
                                @error('role') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 border-light" style="border-color: #f1f5f9 !important;">
                    <h6 class="fw-bold mb-4" style="color: #0f172a;"><i class="bi bi-key me-2 text-success"></i>Perbarui Password</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Password Baru <span class="text-muted fw-normal" style="text-transform: none;">(Opsional)</span></label>
                            <div class="input-group-custom">
                                <input type="password" name="password" class="form-control-modern @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak diubah">
                                <i class="bi bi-lock"></i>
                                @error('password') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <div class="input-group-custom">
                                <input type="password" name="password_confirmation" class="form-control-modern" placeholder="Ulangi password baru">
                                <i class="bi bi-lock-fill"></i>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-5">
                        <a href="{{ route('admin.users.index') }}" class="btn bg-white border rounded-3 px-4 fw-bold" style="color: #64748b;">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success rounded-3 px-5 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: #10b981; border: none;">
                            <i class="bi bi-save2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const switchInput = document.getElementById('statusSwitch');
        const switchLabel = document.querySelector('label[for="statusSwitch"]');
        
        if(switchInput && switchLabel) {
            switchInput.addEventListener('change', function() {
                if(this.checked) {
                    switchLabel.textContent = 'Akun Aktif (Dapat Login)';
                    switchLabel.classList.remove('text-danger');
                    switchLabel.classList.add('text-success');
                } else {
                    switchLabel.textContent = 'Akun Nonaktif (Terkunci)';
                    switchLabel.classList.remove('text-success');
                    switchLabel.classList.add('text-danger');
                }
            });
            if(!switchInput.checked) {
                switchLabel.classList.add('text-danger');
                switchLabel.textContent = 'Akun Nonaktif (Terkunci)';
            } else {
                switchLabel.classList.add('text-success');
                switchLabel.textContent = 'Akun Aktif (Dapat Login)';
            }
        }
    });
</script>
@endpush