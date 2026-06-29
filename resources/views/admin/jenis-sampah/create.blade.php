@extends('layouts.admin')

@section('title', 'Tambah Kategori Sampah Baru')

@section('content')

<!-- --- CUSTOM CSS KHUSUS HALAMAN FORM CREATE JENIS SAMPAH --- -->
<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama Form - Radius Dikurangi */
    .form-card { border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }
    .form-header { background-color: #ffffff; padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 1rem; }

    /* Styling Input Modern Dasar */
    .form-label { font-weight: 600; color: #475569; font-size: 0.85rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .input-group-custom { position: relative; margin-bottom: 1.5rem; }
    .input-group-custom i.input-icon { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.1rem; z-index: 10; transition: color 0.3s; pointer-events: none;}
    .input-group-custom i.textarea-icon { top: 1.2rem; transform: none; }
    
    .form-control-modern { width: 100%; padding: 0.8rem 1rem 0.8rem 3.2rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; color: #0f172a; background-color: #f8fafc; transition: all 0.3s; }
    textarea.form-control-modern { padding-top: 1rem; min-height: 100px; }
    
    .form-control-modern:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
    .form-control-modern:focus + i.input-icon, .input-group-custom textarea:focus ~ i.input-icon { color: #10b981; }

    /* --- STYLING KHUSUS CUSTOM DROPDOWN STATUS --- */
    .custom-status-btn {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0; 
        border-radius: 8px;
        padding: 0.6rem 1rem;
        color: #0f172a;
        font-size: 0.95rem;
        width: 100%;
        text-align: left;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s;
    }
    .custom-status-btn:focus, .custom-status-btn.show {
        border-color: #10b981;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }
    .custom-status-btn::after { color: #64748b; } 
    
    .custom-status-menu {
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border-radius: 10px;
        padding: 0.5rem;
        margin-top: 0.5rem !important;
    }
    .status-option {
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        color: #475569;
        font-weight: 500;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        font-size: 0.9rem;
    }
    .status-option:hover { background-color: #f8fafc; color: #0f172a; }
    .status-option.selected-status {
        background-color: #ecfdf5; /* Background Emerald muda */
        color: #047857; /* Teks Emerald tua */
    }

    /* Panel Informasi Kiri (Minimalist Emerald) */
    .info-panel { background-color: #ffffff; border: 1px solid #f1f5f9; border-left: 6px solid #10b981; border-radius: 12px; padding: 2.5rem 2rem; height: 100%; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); }
    .info-panel-icon { width: 64px; height: 64px; background-color: #ecfdf5; color: #047857; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 1.5rem; }
    .info-panel h4 { font-weight: 800; color: #0f172a; margin-bottom: 1rem; }
    .info-panel p { color: #64748b; font-size: 0.95rem; line-height: 1.6; }
    
    /* Box Edukasi di Panel Kiri */
    .edu-box { background-color: #f8fafc; padding: 1.25rem; border-radius: 8px; margin-top: 2rem; border: 1px dashed #cbd5e1; }
    .edu-box strong { color: #0f172a; display: block; margin-bottom: 0.5rem; font-size: 0.95rem; font-weight: 700; }
    .edu-box p { margin: 0; font-size: 0.85rem; color: #64748b; line-height: 1.5; }
</style>

<div class="d-flex align-items-center mb-4 animate-fade-up">
    <a href="{{ route($routePrefix . '.jenis-sampah.index') }}" class="btn btn-light rounded-3 shadow-sm me-3 bg-white border" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a;">
        <i class="bi bi-arrow-left fs-5"></i>
    </a>
    <div>
        <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Registrasi Kategori Baru</h4>
        <p class="text-muted mb-0 small">Tambahkan klasifikasi sampah dan atur nilai tukar poinnya.</p>
    </div>
</div>

<div class="row g-4">
    
    <!-- KOLOM KIRI: PANEL INFORMASI -->
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <div class="info-panel-icon">
                <i class="bi bi-patch-plus-fill"></i>
            </div>
            <h4>Sistem Reward</h4>
            <p>Menambahkan kategori baru berarti membuka peluang mahasiswa untuk menyetor jenis sampah yang lebih bervariasi.</p>
            
            <div class="edu-box">
                <strong><i class="bi bi-lightbulb-fill text-warning me-2"></i>Tips Gamifikasi</strong>
                <p>Berikan nilai poin yang lebih tinggi pada jenis sampah yang sulit diurai (seperti plastik botol atau kaleng) untuk mendorong mahasiswa lebih aktif menjaga lingkungan.</p>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: FORM INPUT -->
    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <h5 class="m-0 fw-bold" style="color: #0f172a;"><i class="bi bi-tags text-success me-2"></i>Formulir Setup Kategori</h5>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="{{ route($routePrefix . '.jenis-sampah.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <!-- NAMA KATEGORI -->
                        <div class="col-md-12">
                            <label class="form-label">Nama Kategori Sampah <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="text" name="nama" class="form-control-modern @error('nama') is-invalid @enderror" 
                                    value="{{ old('nama') }}" placeholder="Contoh: Plastik PET, Botol Kaca, Kardus..." required autofocus>
                                <i class="bi bi-tag input-icon"></i>
                                @error('nama') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- DESKRIPSI -->
                        <div class="col-md-12">
                            <label class="form-label">Deskripsi Singkat</label>
                            <div class="input-group-custom">
                                <textarea name="deskripsi" class="form-control-modern @error('deskripsi') is-invalid @enderror" 
                                    rows="2" placeholder="Contoh: Segala jenis botol minuman berbahan plastik transparan...">{{ old('deskripsi') }}</textarea>
                                <i class="bi bi-card-text input-icon textarea-icon"></i>
                                @error('deskripsi') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- POIN PER KG -->
                        <div class="col-md-4 mt-2">
                            <label class="form-label">Nilai Konversi <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="number" name="poin_per_kg" class="form-control-modern fw-bold text-success @error('poin_per_kg') is-invalid @enderror" 
                                    value="{{ old('poin_per_kg', 100) }}" min="0" required>
                                <i class="bi bi-star-fill text-warning input-icon"></i>
                                @error('poin_per_kg') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- SATUAN -->
                        <div class="col-md-3 mt-2">
                            <label class="form-label">Satuan <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="text" name="satuan" class="form-control-modern @error('satuan') is-invalid @enderror" 
                                    value="{{ old('satuan', 'kg') }}" required>
                                <i class="bi bi-box input-icon"></i>
                                @error('satuan') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- ============================================== -->
                        <!-- CUSTOM DROPDOWN STATUS KATEGORI                -->
                        <!-- ============================================== -->
                        <div class="col-md-5 mt-2">
                            <label class="form-label">Status Kategori <span class="text-danger">*</span></label>
                            
                            <!-- Input Hidden untuk menangkap nilai form -->
                            <input type="hidden" name="is_active" id="input_is_active" value="{{ old('is_active', '1') }}">
                            
                            <div class="dropdown">
                                <button class="btn custom-status-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span id="status_btn_content" class="d-flex align-items-center">
                                        @if(old('is_active', '1') == '1')
                                            <i class="bi bi-toggle-on text-success fs-4 me-2" style="line-height: 0;"></i> Aktif
                                        @else
                                            <i class="bi bi-toggle-off text-secondary fs-4 me-2" style="line-height: 0;"></i> Nonaktif
                                        @endif
                                    </span>
                                </button>
                                
                                <ul class="dropdown-menu custom-status-menu w-100">
                                    <li>
                                        <a class="dropdown-item status-option {{ old('is_active', '1') == '1' ? 'selected-status' : '' }}" href="#" data-value="1" data-text="Aktif" data-icon="bi-toggle-on text-success">
                                            <i class="bi bi-check2 text-success me-2 check-indicator" style="opacity: {{ old('is_active', '1') == '1' ? '1' : '0' }}; font-weight: bold;"></i>
                                            <i class="bi bi-toggle-on text-success fs-4 me-2" style="line-height: 0;"></i> Aktif
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item status-option {{ old('is_active', '1') == '0' ? 'selected-status' : '' }}" href="#" data-value="0" data-text="Nonaktif" data-icon="bi-toggle-off text-secondary">
                                            <i class="bi bi-check2 text-success me-2 check-indicator" style="opacity: {{ old('is_active', '1') == '0' ? '1' : '0' }}; font-weight: bold;"></i>
                                            <i class="bi bi-toggle-off text-secondary fs-4 me-2" style="line-height: 0;"></i> Nonaktif
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="form-text mt-2 small text-muted lh-sm">Tentukan apakah kategori ini aktif dalam sistem manajemen sampah.</div>
                            
                            @error('is_active') 
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                            @enderror
                        </div>
                        <!-- ============================================== -->
                        
                    </div>

                    <!-- TOMBOL AKSI -->
                    <div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top" style="border-color: #f1f5f9 !important;">
                        <a href="{{ route($routePrefix . '.jenis-sampah.index') }}" class="btn bg-white border rounded-3 px-4 fw-bold" style="color: #64748b;">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success rounded-3 px-5 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: #10b981; border: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="bi bi-save2"></i> Simpan Kategori
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
    // JS Logic untuk Custom Dropdown Status
    document.addEventListener('DOMContentLoaded', function() {
        const statusOptions = document.querySelectorAll('.status-option');
        
        statusOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                
                const value = this.getAttribute('data-value');
                const text = this.getAttribute('data-text');
                const iconClass = this.getAttribute('data-icon');
                
                // 1. Update Hidden Input 
                document.getElementById('input_is_active').value = value;
                
                // 2. Update Tulisan dan Ikon di Tombol Utama
                document.getElementById('status_btn_content').innerHTML = `
                    <i class="bi ${iconClass} fs-4 me-2" style="line-height: 0;"></i> ${text}
                `;
                
                // 3. Reset style opsi lain
                statusOptions.forEach(opt => {
                    opt.classList.remove('selected-status');
                    opt.querySelector('.check-indicator').style.opacity = '0';
                });
                
                // 4. Tambahkan style aktif ke opsi yang diklik
                this.classList.add('selected-status');
                this.querySelector('.check-indicator').style.opacity = '1'; 
            });
        });
    });
</script>
@endpush