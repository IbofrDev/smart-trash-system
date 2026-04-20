@extends('layouts.admin')

@section('title', 'Tambah Kategori Sampah Baru')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama Form */
    .form-card { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03); background-color: #ffffff; overflow: hidden; }
    .form-header { background-color: #f9fafb; padding: 1.5rem 2rem; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; gap: 1rem; }

    /* Styling Input Modern Dasar */
    .form-label { font-weight: 600; color: #4b5563; font-size: 0.9rem; margin-bottom: 0.5rem; }
    .input-group-custom { position: relative; margin-bottom: 1.5rem; }
    .input-group-custom i.input-icon { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 1.1rem; z-index: 10; transition: color 0.3s; }
    .input-group-custom i.textarea-icon { top: 1.2rem; transform: none; }
    
    .form-control-modern { width: 100%; padding: 0.8rem 1rem 0.8rem 3.2rem; border: 1.5px solid #e5e7eb; border-radius: 12px; font-size: 0.95rem; color: #1f2937; background-color: #fcfcfc; transition: all 0.3s; }
    textarea.form-control-modern { padding-top: 1rem; min-height: 100px; }
    
    .form-control-modern:focus { outline: none; border-color: #f59e0b; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1); }
    .form-control-modern:focus + i.input-icon, .input-group-custom input:focus ~ i.input-icon, .input-group-custom textarea:focus ~ i.input-icon { color: #f59e0b; }

    /* --- STYLING KHUSUS CUSTOM DROPDOWN STATUS --- */
    .custom-status-btn {
        background-color: #ffffff;
        border: 1.5px solid #f59e0b; /* Border Amber/Orange */
        border-radius: 12px;
        padding: 0.6rem 1rem;
        color: #1f2937;
        font-weight: 500;
        width: 100%;
        text-align: left;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s;
    }
    .custom-status-btn:focus, .custom-status-btn.show {
        border-color: #f59e0b;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15);
    }
    .custom-status-btn::after { color: #6b7280; } /* Warna panah dropdown */
    
    .custom-status-menu {
        border: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-radius: 12px;
        padding: 0.5rem;
        margin-top: 0.5rem !important;
    }
    .status-option {
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        color: #374151;
        font-weight: 500;
        transition: all 0.2s;
        display: flex;
        align-items: center;
    }
    .status-option:hover { background-color: #f3f4f6; }
    .status-option.selected-status {
        background-color: #eff6ff; /* Background biru muda cerah */
        color: #1d4ed8; /* Teks biru tua */
    }

    /* Panel Informasi Kiri */
    .info-panel { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 16px; padding: 2.5rem 2rem; height: 100%; color: #ffffff; box-shadow: 0 10px 25px rgba(217, 119, 6, 0.2); position: relative; overflow: hidden; }
    .info-panel::after { content: '\F5D3'; font-family: 'bootstrap-icons'; position: absolute; right: -20px; bottom: -20px; font-size: 12rem; opacity: 0.1; transform: rotate(-15deg); }
    .info-panel-icon { width: 80px; height: 80px; background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin-bottom: 1.5rem; }
    .info-panel h4 { font-weight: 800; margin-bottom: 0.5rem; line-height: 1.3; }
    .edu-box { background-color: rgba(0,0,0,0.15); padding: 1.25rem; border-radius: 12px; margin-top: 2rem; border: 1px solid rgba(255,255,255,0.2); border-left: 4px solid #fef08a; }
    .edu-box strong { color: #fef08a; display: block; margin-bottom: 0.5rem; font-size: 0.95rem; }
    .edu-box p { margin: 0; font-size: 0.85rem; color: #fef3c7; line-height: 1.5; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4 animate-fade-up">
    <div class="d-flex align-items-center">
        <a href="{{ route('admin.jenis-sampah.index') }}" class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-1">Registrasi Kategori Baru</h4>
            <p class="text-muted mb-0 small">Tambahkan klasifikasi sampah dan atur nilai tukar poinnya.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <div class="info-panel-icon">
                <i class="bi bi-patch-plus-fill text-white"></i>
            </div>
            <h4 class="mb-2">Sistem Reward</h4>
            <p class="text-white-50 text-sm">Menambahkan kategori baru berarti membuka peluang mahasiswa untuk menyetor jenis sampah yang lebih bervariasi.</p>
            
            <div class="edu-box">
                <strong><i class="bi bi-lightbulb-fill me-2"></i>Tips Gamifikasi</strong>
                <p>Berikan nilai poin yang lebih tinggi pada jenis sampah yang sulit diurai (seperti plastik botol atau kaleng) untuk mendorong mahasiswa lebih aktif menjaga lingkungan.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <i class="bi bi-tags fs-3" style="color: #f59e0b;"></i>
                <h5 class="m-0 fw-bold text-dark">Formulir Setup Kategori</h5>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('admin.jenis-sampah.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Nama Kategori Sampah <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="text" name="nama" class="form-control-modern @error('nama') is-invalid @enderror" 
                                    value="{{ old('nama') }}" placeholder="Contoh: Plastik PET, Botol Kaca, Kardus..." required autofocus>
                                <i class="bi bi-tag input-icon"></i>
                                @error('nama') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Deskripsi Singkat</label>
                            <div class="input-group-custom">
                                <textarea name="deskripsi" class="form-control-modern @error('deskripsi') is-invalid @enderror" 
                                    rows="2" placeholder="Contoh: Segala jenis botol minuman berbahan plastik transparan...">{{ old('deskripsi') }}</textarea>
                                <i class="bi bi-card-text input-icon textarea-icon"></i>
                                @error('deskripsi') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mt-2">
                            <label class="form-label">Nilai Konversi <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="number" name="poin_per_kg" class="form-control-modern fw-bold text-success @error('poin_per_kg') is-invalid @enderror" 
                                    value="{{ old('poin_per_kg', 100) }}" min="0" required>
                                <i class="bi bi-star-fill text-warning input-icon"></i>
                                @error('poin_per_kg') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-3 mt-2">
                            <label class="form-label">Satuan <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="text" name="satuan" class="form-control-modern @error('satuan') is-invalid @enderror" 
                                    value="{{ old('satuan', 'kg') }}" required>
                                <i class="bi bi-box input-icon"></i>
                                @error('satuan') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-5 mt-2">
                            <label class="form-label">Status Kategori <span class="text-danger">*</span></label>
                            
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
                                            <i class="bi bi-check2 text-primary me-2 check-indicator" style="opacity: {{ old('is_active', '1') == '1' ? '1' : '0' }}; font-weight: bold;"></i>
                                            <i class="bi bi-toggle-on text-success fs-4 me-2" style="line-height: 0;"></i> Aktif
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item status-option {{ old('is_active', '1') == '0' ? 'selected-status' : '' }}" href="#" data-value="0" data-text="Nonaktif" data-icon="bi-toggle-off text-secondary">
                                            <i class="bi bi-check2 text-primary me-2 check-indicator" style="opacity: {{ old('is_active', '1') == '0' ? '1' : '0' }}; font-weight: bold;"></i>
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
                        </div>

                    <div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top">
                        <a href="{{ route('admin.jenis-sampah.index') }}" class="btn btn-light rounded-pill px-4 fw-medium border shadow-sm">
                            Batal
                        </a>
                        <button type="submit" class="btn rounded-pill px-5 fw-bold shadow-sm d-flex align-items-center gap-2" style="background-color: #f59e0b; color: white; border: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="bi bi-plus-circle-fill"></i> Tambah Kategori
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
                
                // Ambil data atribut
                const value = this.getAttribute('data-value');
                const text = this.getAttribute('data-text');
                const iconClass = this.getAttribute('data-icon');
                
                // 1. Update Hidden Input (untuk disubmit ke Laravel)
                document.getElementById('input_is_active').value = value;
                
                // 2. Update Tulisan dan Ikon di Tombol Utama
                document.getElementById('status_btn_content').innerHTML = `
                    <i class="bi ${iconClass} fs-4 me-2" style="line-height: 0;"></i> ${text}
                `;
                
                // 3. Reset style semua opsi (hapus background biru & sembunyikan centang)
                statusOptions.forEach(opt => {
                    opt.classList.remove('selected-status');
                    opt.querySelector('.check-indicator').style.opacity = '0';
                });
                
                // 4. Tambahkan style aktif (biru muda) ke opsi yang diklik
                this.classList.add('selected-status');
                this.querySelector('.check-indicator').style.opacity = '1'; // Munculkan ikon centang biru
            });
        });
    });
</script>
@endpush