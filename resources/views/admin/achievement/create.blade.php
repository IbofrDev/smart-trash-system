@extends('layouts.admin')

@section('title', 'Tambah Achievement Baru')

@section('content')

<style>
    /* Animasi Masuk */
    .animate-fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease-out forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card Utama Form - Radius Dikurangi */
    .form-card { border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); background-color: #ffffff; overflow: hidden; }
    .form-header { background-color: #ffffff; padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 1rem; }

    /* Styling Input Modern Dasar */
    .form-label { font-weight: 600; color: #475569; font-size: 0.85rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .input-group-custom { position: relative; margin-bottom: 1.5rem; }
    .input-group-custom i.input-icon { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.1rem; z-index: 10; transition: color 0.3s; pointer-events: none; }
    .input-group-custom i.textarea-icon { top: 1.2rem; transform: none; }
    
    .form-control-modern { width: 100%; padding: 0.8rem 1rem 0.8rem 3.2rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; color: #0f172a; background-color: #f8fafc; transition: all 0.3s; }
    textarea.form-control-modern { padding-top: 1rem; min-height: 100px; }
    
    .form-control-modern:focus { outline: none; border-color: #10b981; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
    .form-control-modern:focus + i.input-icon, .input-group-custom input:focus ~ i.input-icon, .input-group-custom textarea:focus ~ i.input-icon { color: #10b981; }

    /* --- STYLING KHUSUS CUSTOM DROPDOWN TIPE MISI --- */
    .custom-select-btn {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0; 
        border-radius: 8px;
        padding: 0.8rem 1rem 0.8rem 3.2rem; 
        color: #0f172a;
        font-size: 0.95rem;
        width: 100%;
        text-align: left;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s;
    }
    .custom-select-btn:focus, .custom-select-btn.show {
        border-color: #10b981;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }
    .custom-select-btn::after { display: none; }
    .custom-select-btn .chevron-icon { color: #94a3b8; font-size: 0.85rem; transition: transform 0.3s; }
    .custom-select-btn.show .chevron-icon { transform: rotate(180deg); }
    
    .custom-select-menu {
        border: 1px solid #f1f5f9; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-radius: 10px;
        padding: 0.5rem; margin-top: 0.5rem !important; width: 100%; z-index: 1000;
    }
    .custom-option {
        border-radius: 6px; padding: 0.6rem 1rem; color: #475569;
        font-weight: 500; transition: all 0.2s; display: flex; align-items: center; font-size: 0.9rem;
    }
    .custom-option:hover { background-color: #f8fafc; color: #0f172a; }
    .custom-option.selected-opt { background-color: #ecfdf5; color: #047857; font-weight: 600; }

    /* Live Icon Preview Box - Emerald */
    .icon-preview-box { width: 48px; height: 48px; border-radius: 8px; background-color: #ecfdf5; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #047857; flex-shrink: 0; }

    /* Panel Informasi Kiri (Minimalist Emerald) */
    .info-panel { background-color: #ffffff; border: 1px solid #f1f5f9; border-left: 6px solid #10b981; border-radius: 12px; padding: 2.5rem 2rem; height: 100%; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); }
    .info-panel-icon { width: 64px; height: 64px; background-color: #ecfdf5; color: #047857; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 1.5rem; }
    .info-panel h4 { font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; line-height: 1.3; }
    .info-panel p { color: #64748b; font-size: 0.95rem; line-height: 1.6; }
    
    /* Box Edukasi di Panel Kiri - Light Theme */
    .guide-box { background-color: #f8fafc; padding: 1.25rem; border-radius: 8px; margin-top: 2rem; border: 1px dashed #cbd5e1; }
    .guide-box strong { color: #0f172a; display: block; margin-bottom: 0.5rem; font-size: 0.95rem; font-weight: 700; }
    .guide-box p { margin: 0; font-size: 0.85rem; color: #64748b; line-height: 1.5; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4 animate-fade-up">
    <div class="d-flex align-items-center">
        <a href="{{ route($routePrefix . '.achievement.index') }}" class="btn btn-light rounded-3 shadow-sm me-3 bg-white border" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-1" style="color: #0f172a;">Buat Misi Baru</h4>
            <p class="text-muted mb-0 small">Rancang target pencapaian dan tentukan hadiah poinnya.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="info-panel">
            <div class="info-panel-icon">
                <i class="bi bi-controller"></i>
            </div>
            <h4>Quest Creator</h4>
            <p>Desain misi yang menarik untuk memicu semangat kompetisi di kalangan mahasiswa.</p>
            
            <div class="guide-box">
                <strong><i class="bi bi-lightbulb-fill text-success me-2"></i>Strategi Misi</strong>
                <p>Gunakan syarat <strong>Pertama Kali</strong> untuk memancing mahasiswa baru mencoba sistem, dan gunakan <strong>Streak Hari</strong> untuk membentuk kebiasaan membuang sampah secara rutin setiap hari.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="form-card h-100">
            <div class="form-header">
                <h5 class="m-0 fw-bold" style="color: #0f172a;"><i class="bi bi-award text-success me-2"></i>Formulir Detail Achievement</h5>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="{{ route($routePrefix . '.achievement.store') }}" method="POST" id="achievementForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Nama Achievement (Gelar) <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="text" name="nama" class="form-control-modern fw-bold text-dark @error('nama') is-invalid @enderror" 
                                    value="{{ old('nama') }}" placeholder="Contoh: Sang Pemula, Master Plastik..." required autofocus>
                                <i class="bi bi-patch-check input-icon text-success"></i>
                                @error('nama') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Deskripsi Misi</label>
                            <div class="input-group-custom">
                                <textarea name="deskripsi" class="form-control-modern @error('deskripsi') is-invalid @enderror" 
                                    rows="2" placeholder="Ceritakan apa yang harus dilakukan mahasiswa untuk meraih gelar ini...">{{ old('deskripsi') }}</textarea>
                                <i class="bi bi-card-text input-icon textarea-icon"></i>
                                @error('deskripsi') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-12 mb-2">
                            <label class="form-label">Ikon Achievement <span class="text-muted fw-normal small ms-1">(Bootstrap Class)</span></label>
                            <div class="d-flex gap-3 align-items-start">
                                <div class="input-group-custom flex-grow-1 mb-0">
                                    <input type="text" id="iconInput" name="icon" class="form-control-modern @error('icon') is-invalid @enderror" 
                                        value="{{ old('icon', 'bi bi-star-fill') }}" placeholder="Contoh: bi bi-fire">
                                    <i class="bi bi-bootstrap input-icon"></i>
                                </div>
                                <div class="icon-preview-box" id="iconPreview" title="Live Preview">
                                    <i class="{{ old('icon', 'bi bi-star-fill') }}"></i>
                                </div>
                            </div>
                            @error('icon') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            <div class="form-text small mt-1"><i class="bi bi-info-circle me-1"></i>Cari referensi ikon di <a href="https://icons.getbootstrap.com/" target="_blank" class="text-decoration-none fw-bold" style="color: #10b981;">Bootstrap Icons <i class="bi bi-box-arrow-up-right" style="font-size: 0.7rem;"></i></a></div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label">Tipe Misi (Syarat) <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                
                                <input type="hidden" name="syarat_type" id="inputSyaratType" value="{{ old('syarat_type') }}" required>
                                
                                <div class="dropdown w-100">
                                    <button class="btn custom-select-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="{{ old('syarat_type') ? 'border-color: #10b981; background-color: #ffffff;' : '' }}">
                                        <span id="syaratBtnText" class="{{ old('syarat_type') ? '' : 'text-muted' }}">
                                            @if(old('syarat_type') == 'total_kg') Total Berat (Kg)
                                            @elseif(old('syarat_type') == 'streak') Streak (Hari Berturut)
                                            @elseif(old('syarat_type') == 'transaksi_count') Jumlah Setoran (Kali)
                                            @elseif(old('syarat_type') == 'first_time') Aksi Pertama Kali
                                            @else Pilih jenis misi...
                                            @endif
                                        </span>
                                        <i class="bi bi-chevron-down chevron-icon"></i>
                                    </button>
                                    
                                    <i class="bi bi-list-check input-icon text-success"></i>
                                    
                                    <ul class="dropdown-menu custom-select-menu">
                                        <li><a class="dropdown-item custom-option {{ old('syarat_type') == 'total_kg' ? 'selected-opt' : '' }}" href="#" data-value="total_kg"><i class="bi bi-speedometer2 me-2 text-success"></i> Total Berat (Kg)</a></li>
                                        <li><a class="dropdown-item custom-option {{ old('syarat_type') == 'streak' ? 'selected-opt' : '' }}" href="#" data-value="streak"><i class="bi bi-fire me-2 text-success"></i> Streak (Hari Berturut)</a></li>
                                        <li><a class="dropdown-item custom-option {{ old('syarat_type') == 'transaksi_count' ? 'selected-opt' : '' }}" href="#" data-value="transaksi_count"><i class="bi bi-arrow-repeat me-2 text-success"></i> Jumlah Setoran (Kali)</a></li>
                                        <li><a class="dropdown-item custom-option {{ old('syarat_type') == 'first_time' ? 'selected-opt' : '' }}" href="#" data-value="first_time"><i class="bi bi-stars me-2 text-success"></i> Aksi Pertama Kali</a></li>
                                    </ul>
                                </div>

                                @error('syarat_type') 
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label class="form-label">Target Nilai <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="number" id="syaratValue" name="syarat_value" class="form-control-modern fw-semibold @error('syarat_value') is-invalid @enderror" 
                                    value="{{ old('syarat_value', 0) }}" min="0" required>
                                <i class="bi bi-bullseye input-icon"></i>
                                @error('syarat_value') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label">Hadiah Poin <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <input type="number" name="poin_bonus" class="form-control-modern text-success fw-bold @error('poin_bonus') is-invalid @enderror" 
                                    value="{{ old('poin_bonus', 0) }}" min="0" required>
                                <i class="bi bi-coin input-icon text-warning"></i>
                                @error('poin_bonus') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top" style="border-color: #f1f5f9 !important;">
                        <a href="{{ route($routePrefix . '.achievement.index') }}" class="btn bg-white border rounded-3 px-4 fw-bold" style="color: #64748b;">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success rounded-3 px-5 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: #10b981; border: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="bi bi-save-fill"></i> Simpan Pencapaian
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
        // --- 1. Logic Live Preview Icon ---
        const iconInput = document.getElementById('iconInput');
        const iconPreview = document.getElementById('iconPreview');

        iconInput.addEventListener('input', function() {
            let iconClass = this.value.trim();
            if(iconClass === '') iconClass = 'bi bi-trophy';
            iconPreview.innerHTML = `<i class="${iconClass}"></i>`;
        });


        // --- 2. Custom Dropdown Tipe Syarat & Auto-Lock Target ---
        const inputSyaratType = document.getElementById('inputSyaratType');
        const syaratBtnText = document.getElementById('syaratBtnText');
        const customOptions = document.querySelectorAll('.custom-option');
        const syaratValue = document.getElementById('syaratValue');
        const customSelectBtn = document.querySelector('.custom-select-btn');

        // Fungsi untuk mengunci/membuka target nilai berdasarkan tipe
        function applySyaratLogic(type) {
            if(type === 'first_time') {
                syaratValue.value = 1;
                syaratValue.setAttribute('readonly', true);
                syaratValue.style.backgroundColor = '#f1f5f9'; // warna abu-abu
            } else {
                syaratValue.removeAttribute('readonly');
                syaratValue.style.backgroundColor = '#f8fafc'; // warna normal form modern
                
                // Reset nilai jika sebelumnya terkunci di angka 1
                if(syaratValue.value == 1 && type !== 'first_time'){
                     syaratValue.value = 0; 
                }
            }
            if(type) {
                customSelectBtn.style.borderColor = '#10b981';
                customSelectBtn.style.backgroundColor = '#ffffff';
            }
        }

        // Event listener saat item dropdown diklik
        customOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                
                const value = this.getAttribute('data-value');
                const text = this.innerText.trim();
                
                // 1. Update Hidden Input
                inputSyaratType.value = value;
                
                // 2. Update Teks Tombol & Hapus class text-muted
                syaratBtnText.innerHTML = text;
                syaratBtnText.classList.remove('text-muted');
                
                // 3. Update styling aktif di dropdown menu
                customOptions.forEach(opt => opt.classList.remove('selected-opt'));
                this.classList.add('selected-opt');
                
                // 4. Jalankan trigger "first_time" logic
                applySyaratLogic(value);
            });
        });

        // Tandai jika admin sengaja mengubah target nilai manual
        syaratValue.addEventListener('input', function() {
            this.setAttribute('data-touched', 'true');
        });

        // Panggil logic saat pertama kali diload (jika ada nilai dari validasi/old)
        if(inputSyaratType.value) {
            applySyaratLogic(inputSyaratType.value);
        }
        
        // Validasi Manual Sebelum Submit
        document.getElementById('achievementForm').addEventListener('submit', function(e) {
            if(!inputSyaratType.value) {
                e.preventDefault();
                alert('Mohon pilih Tipe Misi (Syarat) terlebih dahulu.');
                customSelectBtn.focus();
            }
        });
    });
</script>
@endpush