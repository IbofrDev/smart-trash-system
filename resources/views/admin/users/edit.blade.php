@extends('layouts.admin')

@section('title', 'Edit Pengguna')

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
    
    .avatar-lg {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-weight: 800;
        font-size: 1.5rem;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
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
        padding: 0.8rem 1rem 0.8rem 3rem; /* Padding kiri lebar untuk tempat ikon */
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
        color: #10b981; /* Ikon menyala saat input difokuskan */
    }

    /* Khusus untuk Select Dropdown agar ikonnya pas */
    select.form-control-modern {
        appearance: none;
        -moz-appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 16px 12px;
    }

    /* Switch Toggle Modern */
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
    }
    .form-switch .form-check-input:checked {
        background-color: #10b981;
        border-color: #10b981;
    }
    .form-switch .form-check-label {
        font-weight: 600;
        color: #374151;
        cursor: pointer;
        padding-top: 0.2rem;
        margin-left: 0.5rem;
    }

    /* Alert / Bantuan Info Area Kiri */
    .info-panel {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        padding: 2rem;
        height: 100%;
        border: 1px solid #e5e7eb;
    }
    .info-panel h5 { font-weight: 700; color: #1f2937; margin-bottom: 1rem; }
    .info-panel p { color: #6b7280; font-size: 0.9rem; line-height: 1.6; }
    
    .danger-zone {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }
</style>

<div class="d-flex align-items-center mb-4 animate-fade-up">
    <a href="{{ route('admin.users.index') }}" class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
        <i class="bi bi-arrow-left fs-5"></i>
    </a>
    <div>
        <h4 class="fw-bold text-dark mb-1">Edit Data Pengguna</h4>
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
                <span class="badge {{ $user->role === 'admin' ? 'bg-primary' : 'bg-info' }} bg-opacity-10 {{ $user->role === 'admin' ? 'text-primary' : 'text-info' }} rounded-pill px-3 py-1">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
            
            <hr class="my-4 border-light">
            
            <h6 class="fw-bold text-dark"><i class="bi bi-info-circle me-2 text-primary"></i>Petunjuk Edit Data</h6>
            <p class="mb-4">Pastikan alamat email yang dimasukkan aktif dan valid. Kosongkan kolom <strong>Password Baru</strong> jika Anda tidak ingin mengubah kata sandi pengguna ini.</p>

            <div class="danger-zone">
                <h6 class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Status Akun</h6>
                <p class="text-muted small mb-3">Akun yang dinonaktifkan tidak akan bisa login ke dalam sistem.</p>
                
                <div class="form-check form-switch">
                    <input type="hidden" name="is_active" value="0" form="editUserForm">
                    <input class="form-check-input" type="checkbox" id="statusSwitch" name="is_active" value="1" form="editUserForm" {{ $user->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="statusSwitch">Akun Aktif</label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <i class="bi bi-person-lines-fill fs-3 text-primary"></i>
                <h5 class="m-0 fw-bold text-dark">Formulir Identitas</h5>
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
                                    <option value="pengelola" {{ old('role', $user->role) === 'pengelola' ? 'selected' : '' }}>Pengelola (Akses Terbatas)</option>
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
                            <label class="form-label">Password Baru <span class="text-muted fw-normal">(Opsional)</span></label>
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
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light rounded-pill px-4 fw-medium border shadow-sm">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm d-flex align-items-center gap-2">
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
    // Opsional: Logika kecil untuk merubah teks status saat switch diklik
    document.addEventListener('DOMContentLoaded', function() {
        const switchInput = document.getElementById('statusSwitch');
        const switchLabel = document.querySelector('label[for="statusSwitch"]');
        
        if(switchInput && switchLabel) {
            switchInput.addEventListener('change', function() {
                if(this.checked) {
                    switchLabel.textContent = 'Akun Aktif';
                    switchLabel.classList.remove('text-danger');
                    switchLabel.classList.add('text-success');
                } else {
                    switchLabel.textContent = 'Akun Nonaktif';
                    switchLabel.classList.remove('text-success');
                    switchLabel.classList.add('text-danger');
                }
            });
            
            // Set warna awal saat dimuat
            if(!switchInput.checked) {
                switchLabel.classList.add('text-danger');
            } else {
                switchLabel.classList.add('text-success');
            }
        }
    });
</script>
@endpush