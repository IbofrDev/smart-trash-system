@extends('layouts.admin')

@section('title', 'Edit Data Mahasiswa')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama Form - Radius Dikurangi */
    .form-card { border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }
    .form-header { background-color: #ffffff; padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 1rem; }

    /* Styling Input Modern */
    .form-label { font-weight: 600; color: #475569; font-size: 0.85rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .input-group-custom { position: relative; margin-bottom: 1.5rem; }
    .input-group-custom i.input-icon { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.1rem; z-index: 10; transition: color 0.3s; pointer-events: none; }
    
    .form-control-modern { width: 100%; padding: 0.8rem 1rem 0.8rem 3.2rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; color: #0f172a; background-color: #f8fafc; transition: all 0.3s; }
    
    .form-control-modern:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
    .form-control-modern:focus + i.input-icon, .input-group-custom select:focus ~ i.input-icon { color: #10b981; }

    /* Styling Select Bawaan Dashboard */
    select.form-control-modern {
        appearance: none; -moz-appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748b' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 1.2rem center; background-size: 16px 12px;
    }

    /* Input Terkunci (Readonly / Disabled) */
    .input-locked { background-color: #f1f5f9 !important; color: #64748b !important; font-weight: bold; pointer-events: none; border-color: #e2e8f0; opacity: 0.8; }
    
    /* Panel Informasi Kiri (Minimalist Emerald) */
    .info-panel { background-color: #ffffff; border: 1px solid #f1f5f9; border-left: 6px solid #10b981; border-radius: 12px; padding: 2.5rem 2rem; height: 100%; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); display: flex; flex-direction: column; align-items: center; text-align: center; }
    .info-panel::after { content: '\F4E1'; font-family: 'bootstrap-icons'; position: absolute; left: -10px; bottom: -20px; font-size: 12rem; color: #f8fafc; z-index: 0; transform: rotate(15deg); }
    
    .profile-avatar { width: 90px; height: 90px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.02); margin-bottom: 1.25rem; z-index: 2; position: relative; }
    
    /* Box Warning di Panel Kiri - Yellow Dashed Style */
    .sys-box { background-color: #fffbeb; padding: 1.25rem; border-radius: 8px; margin-top: auto; border: 1px dashed #fde68a; text-align: left; width: 100%; z-index: 2; position: relative; }
    .sys-box strong { color: #d97706; display: block; margin-bottom: 0.5rem; font-size: 0.95rem; font-weight: 700; }
    .sys-box p { margin: 0; font-size: 0.85rem; color: #64748b; line-height: 1.5; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4 animate-fade-up">
    <div class="d-flex align-items-center">
        <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-light rounded-3 shadow-sm me-3 bg-white border" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Perbarui Data Mahasiswa</h4>
            <p class="text-muted mb-0 small">Koreksi identitas, update RFID, atau sesuaikan level gamifikasi.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($mahasiswa->name) }}&background=ecfdf5&color=047857&size=128&bold=true" alt="Avatar" class="profile-avatar">
            
            <h5 class="fw-bold mb-1" style="color: #0f172a;">{{ $mahasiswa->name }}</h5>
            <div class="mt-1">
                <span class="badge px-3 py-1 rounded-2" style="background: #f8fafc; color: #0f172a; border: 1px solid #e2e8f0; font-weight: 600;">
                    <i class="bi bi-shield-check text-success me-1"></i> {{ $mahasiswa->level->nama_level ?? 'Unranked' }}
                </span>
            </div>
            
            <div class="sys-box mt-4">
                <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Keamanan Akun</strong>
                <p>Kolom Email sengaja dikunci untuk menjaga integritas login via Google (SSO).</p>
                <hr class="my-2" style="border-color: #e2e8f0 !important;">
                <p class="mb-0"><i class="bi bi-info-circle me-1"></i>Mengubah Total Poin secara manual dapat menyebabkan perhitungan Level menjadi tidak sinkron jika tidak disesuaikan.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <h5 class="m-0 fw-bold" style="color: #0f172a;"><i class="bi bi-person-lines-fill text-success me-2"></i>Formulir Identitas & Sistem</h5>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('admin.mahasiswa.update', $mahasiswa) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Alamat Email (Akun Google)</label>
                            <div class="input-group-custom mb-1">
                                <input type="email" class="form-control-modern input-locked" value="{{ $mahasiswa->email }}" disabled tabindex="-1">
                                <i class="bi bi-lock-fill input-icon text-secondary"></i>
                            </div>
                            <div class="form-text small mb-3"><i class="bi bi-shield-lock text-success me-1"></i>Terhubung dengan Google Sign-In Campus.</div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="text" name="name" class="form-control-modern fw-bold text-dark @error('name') is-invalid @enderror" 
                                    value="{{ old('name', $mahasiswa->name) }}" required autofocus>
                                <i class="bi bi-person input-icon"></i>
                                @error('name') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <label class="form-label">Nomor Induk Mahasiswa (NIM)</label>
                            <div class="input-group-custom">
                                <input type="text" name="nim" class="form-control-modern @error('nim') is-invalid @enderror" 
                                    value="{{ old('nim', $mahasiswa->nim) }}" placeholder="Contoh: C030322123">
                                <i class="bi bi-card-heading input-icon"></i>
                                @error('nim') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <label class="form-label">Program Studi</label>
                            <div class="input-group-custom">
                                <input type="text" name="prodi" class="form-control-modern @error('prodi') is-invalid @enderror" 
                                    value="{{ old('prodi', $mahasiswa->prodi) }}" placeholder="Contoh: D3 Teknik Informatika">
                                <i class="bi bi-book input-icon"></i>
                                @error('prodi') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <label class="form-label">RFID Card UID</label>
                            <div class="input-group-custom mb-1">
                                <input type="text" name="rfid_uid" class="form-control-modern text-success fw-semibold" style="font-family: monospace;" 
                                    value="{{ old('rfid_uid', $mahasiswa->rfid_uid) }}" placeholder="Tap kartu ke scanner atau ketik UID...">
                                <i class="bi bi-upc-scan input-icon"></i>
                            </div>
                            <div class="form-text small mb-3">Kosongkan jika mahasiswa belum memiliki kartu RFID aktif.</div>
                        </div>

                        <div class="col-12 mt-3">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3" style="color: #0f172a !important;"><i class="bi bi-controller text-success me-2"></i>Status Gamifikasi</h6>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">Total Poin <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="number" name="total_poin" class="form-control-modern text-success fw-bold fs-5 @error('total_poin') is-invalid @enderror" 
                                    value="{{ old('total_poin', $mahasiswa->total_poin) }}" min="0" required>
                                <i class="bi bi-star-fill input-icon text-warning"></i>
                                @error('total_poin') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-7">
                            <label class="form-label">Peringkat (Level) <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <select name="level_id" class="form-control-modern fw-semibold @error('level_id') is-invalid @enderror" required>
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}" {{ old('level_id', $mahasiswa->level_id) == $level->id ? 'selected' : '' }}>
                                            {{ $level->nama_level }} ({{ number_format($level->min_poin) }} - {{ number_format($level->max_poin) }} Pts)
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-shield-fill-check input-icon text-success"></i>
                                @error('level_id') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top" style="border-color: #f1f5f9 !important;">
                        <a href="{{ route('admin.mahasiswa.index') }}" class="btn bg-white border rounded-3 px-4 fw-bold" style="color: #64748b;">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success rounded-3 px-5 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: #10b981; border: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="bi bi-save2"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection