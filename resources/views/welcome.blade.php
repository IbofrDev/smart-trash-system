<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Waste Bank - Sistem Informasi Terpadu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            overflow-x: hidden; 
        }

        /* --- NAVBAR STYLES --- */
        .navbar {
            position: fixed; 
            top: 0;
            width: 100%;
            z-index: 1000 !important; 
            transition: all 0.3s ease-in-out; 
        }

        .navbar-brand { font-weight: 700; }
        .navbar-brand span { color: #10b981; }

        .navbar-nav .nav-link {
            font-size: 1.1rem; 
            font-weight: 500; 
            padding-left: 1.2rem !important; 
            padding-right: 1.2rem !important;
            transition: color 0.3s ease;
        }

        .navbar-transparent { background-color: transparent !important; box-shadow: none !important; }
        .navbar-transparent .nav-link,
        .navbar-transparent .navbar-brand {
            color: #ffffff !important;
            text-shadow: 1px 2px 5px rgba(0, 0, 0, 0.8) !important;
        }
        .navbar-transparent .navbar-brand span { color: #34d399 !important; }
        .navbar-transparent .navbar-toggler-icon { filter: invert(1); }

        .navbar-scrolled {
            background-color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
            border-bottom: 1px solid #e5e7eb !important;
        }
        .navbar-scrolled .nav-link,
        .navbar-scrolled .navbar-brand { color: #1f2937 !important; text-shadow: none !important; }

        /* --- HERO SECTION --- */
        .hero-section {
            background-image: 
                linear-gradient(rgba(241, 245, 249, 0.37), rgba(241, 245, 249, 0.85)),
                url("{{ asset('images/background-sampah.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            padding-top: 15vh; 
            padding-bottom: 28vh; 
            overflow: hidden; 
        }

        .shape-ellipse-1 {
            position: absolute;
            top: -20px; left: -20px; width: 320px; height: auto; z-index: 1; pointer-events: none;
        }

        .shape-vector-1 {
            position: absolute;
            bottom: -5px; left: 0; width: 100vw; height: auto; object-fit: cover; 
            object-position: bottom; z-index: 1; pointer-events: none; display: block; 
        }

        .content-overlay { position: relative; z-index: 2; }
        .hero-title { font-weight: 800; font-size: 2.5rem; color: #1f2937; line-height: 1.2; }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        /* --- FINISHER HEADER CARD STYLING --- */
        .finisher-card {
            position: relative;
            overflow: hidden; 
            border: none !important;
            border-radius: 0 !important; 
        }

        .finisher-card canvas {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            z-index: 1 !important;
        }

        .finisher-card .card-body {
            position: relative;
            z-index: 2;
        }

        /* --- FITUR BOX HOVER STYLING --- */
        .feature-box {
            transition: all 0.3s ease;
            cursor: default;
        }
        .feature-box:hover {
            transform: translateY(-5px); 
            background-color: rgba(255, 255, 255, 0.95) !important; 
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08); 
        }

        /* --- ORNAMEN TENTANG --- */
        .tentang-blob {
            position: absolute;
            top: 0;
            right: -50px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            z-index: 0;
        }
        .tentang-dots {
            position: absolute;
            bottom: 20px;
            left: -20px;
            width: 150px;
            height: 150px;
            background-image: radial-gradient(rgba(16, 185, 129, 0.15) 2px, transparent 2px);
            background-size: 20px 20px;
            z-index: 0;
        }

        /* --- ORNAMEN BACKGROUND CARA KERJA --- */
        #cara-kerja {
            position: relative;
            overflow: hidden;
        }
        .ornament-blob-1 {
            position: absolute;
            top: -100px;
            left: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, rgba(248, 249, 250, 0) 70%);
            border-radius: 50%;
            z-index: 0;
        }
        .ornament-blob-2 {
            position: absolute;
            bottom: -150px;
            right: -100px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(31, 41, 55, 0.05) 0%, rgba(248, 249, 250, 0) 70%);
            border-radius: 50%;
            z-index: 0;
        }
        .ornament-dots {
            position: absolute;
            top: 20%;
            right: 5%;
            width: 120px;
            height: 120px;
            background-image: radial-gradient(rgba(16, 185, 129, 0.4) 2px, transparent 2px);
            background-size: 15px 15px;
            z-index: 0;
        }
        .ornament-dots-2 {
            position: absolute;
            bottom: 20%;
            left: 5%;
            width: 100px;
            height: 100px;
            background-image: radial-gradient(rgba(31, 41, 55, 0.2) 2px, transparent 2px);
            background-size: 15px 15px;
            z-index: 0;
        }
        #cara-kerja .container {
            position: relative;
            z-index: 2; 
        }

        .btn-download {
            background-color: #000; color: #fff; font-weight: 600; border-radius: 8px;
            padding: 12px 24px; display: inline-flex; align-items: center; gap: 10px;
            transition: all 0.3s ease;
        }
        .btn-download:hover { background-color: #333; color: #fff; }

        .btn-login {
            background-color: #000; color: #fff; font-weight: 600; padding: 12px;
            border-radius: 8px; transition: all 0.3s ease;
        }
        .btn-login:hover { background-color: #10b981; color: #fff; }

        .section-title { font-weight: 700; text-align: center; margin-bottom: 0; text-transform: uppercase; }
        .step-icon { font-size: 2.5rem; margin-bottom: 1rem; color: #1f2937; }
        .stat-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; text-align: center; height: 100%; }
        .stat-value { font-size: 1.5rem; font-weight: 700; margin-top: 0.5rem; }

        /* --- FAQ STYLING --- */
        .accordion-button:not(.collapsed) {
            background-color: #ecfdf5 !important;
            color: #059669 !important;
            box-shadow: none;
        }
        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(0,0,0,.125);
        }

        /* --- CTA BANNER STYLING --- */
        .cta-banner {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
        }
        .cta-banner .btn-light { color: #059669 !important; }
        .cta-banner .btn-light:hover { background-color: #f3f4f6; }

        /* --- FAT FOOTER STYLING --- */
        .fat-footer { 
            background-color: #000000; 
            color: #d1d5db; 
            padding: 4rem 0 2rem 0; 
            font-size: 0.9rem;
        }
        .fat-footer .footer-title {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            font-size: 1rem;
        }
        .fat-footer .footer-brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .fat-footer .footer-brand span { color: #10b981; }
        .fat-footer a { color: #9ca3af; text-decoration: none; transition: color 0.3s; }
        .fat-footer a:hover { color: #10b981; }
        .fat-footer ul { list-style: none; padding-left: 0; }
        .fat-footer ul li { margin-bottom: 0.75rem; }
        .footer-bottom {
            border-top: 1px solid #222222;
            margin-top: 3rem;
            padding-top: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>

    <nav id="mainNav" class="navbar navbar-expand-lg py-3 navbar-transparent">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <i class="bi bi-recycle" style="font-size: 1.8rem;"></i>
                <div class="fs-4"><span>SMART</span> WASTE BANK</div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#cara-kerja">Cara Kerja</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <!-- <img src="{{ asset('images/ellipse-1.png') }}" class="shape-ellipse-1" alt="Shape Atas"> -->
        <img src="{{ asset('images/vector-1.png') }}" class="shape-vector-1" alt="Shape Bawah">

        <div class="container content-overlay">
            <div class="row align-items-center gy-5">
                
                <div class="col-lg-7 pe-lg-5">
                    <h1 class="hero-title mb-4">Ubah Sampah Jadi Cuan & Poin Gamifikasi! 🌍</h1>
                    <p class="fs-5 text-dark mb-4">Sistem Bank Sampah Pintar Terintegrasi IoT Berbasis Mobile & Web Dashboard.</p>
                    
                    <a href="#" class="btn-download text-decoration-none mb-2">
                        <i class="bi bi-phone"></i> UNDUH APLIKASI MAHASISWA
                    </a>
                    <p class="text-muted small">Tersedia di Android & IOS</p>
                </div>

                <div class="col-lg-4 ms-auto col-md-8 offset-md-2 col-12">
                    <div class="login-card p-4">
                        <h4 class="fw-bold mb-1 text-dark fs-5">LOGIN DASHBOARD</h4>
                        <p class="text-muted small mb-4">Masuk sebagai Admin atau Pengelola Sistem</p>

                        @if ($errors->any())
                            <div class="alert alert-danger py-2 small border-0 bg-danger text-white rounded-3">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success py-2 small border-0 bg-success text-white rounded-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('login.submit') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-dark">Email</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="Masukkan email.." value="{{ old('email') }}" required autofocus>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label small fw-semibold text-dark">Password</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                    <input type="password" name="password" id="password" class="form-control border-start-0 border-end-0 ps-0" placeholder="••••••••••••" required>
                                    <button class="btn border border-start-0 bg-white" type="button" id="togglePassword">
                                        <i class="bi bi-eye text-muted"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-login w-100 py-2">MASUK SISTEM</button>
                            
                            <div class="text-center mt-3">
                                <a href="#" class="text-secondary small text-decoration-underline">Lupa Password?</a>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <section id="tentang" class="py-5 bg-white" style="position: relative; z-index: 5; margin-top: -30px; overflow: hidden;">
        <div class="tentang-blob"></div>
        <div class="tentang-dots"></div>
        
        <div class="container py-5" style="position: relative; z-index: 2;">
            
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <h5 class="section-title mb-3">💡 APA ITU SMART WASTE BANK?</h5>
                    <p class="text-muted fs-6">
                        Sebuah <strong class="text-dark">platform digital inovatif</strong> yang dirancang untuk mewujudkan <strong class="text-dark">lingkungan kampus yang lebih hijau</strong> dan terstruktur. Kami memadukan teknologi cerdas untuk memberikan <strong class="text-dark">nilai tambah</strong> pada setiap sampah daur ulang Anda.
                    </p>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card bg-light finisher-card finisher-header p-4 p-md-5 shadow-sm">
                        <div class="card-body">
                            <p class="fs-5 text-center text-dark mb-5">
                                <strong>Smart Waste Bank</strong> adalah solusi inovatif yang mengubah cara kita memandang sampah melalui integrasi kesadaran lingkungan dan teknologi <em>Internet of Things</em> (IoT).
                            </p>
                            
                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <div class="d-flex gap-3 bg-white bg-opacity-50 p-3 rounded-0 feature-box">
                                        <i class="bi bi-cpu fs-3 text-success"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1">Otomatisasi Cerdas</h6>
                                            <p class="text-muted small mb-0">Penimbangan sampah menggunakan sensor IoT dengan pencatatan data secara <em>real-time</em>.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex gap-3 bg-white bg-opacity-50 p-3 rounded-0 feature-box">
                                        <i class="bi bi-wallet2 fs-3 text-success"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1">Nilai Ekonomi Langsung</h6>
                                            <p class="text-muted small mb-0">Ubah sampah yang dipilah menjadi pundi-pundi saldo digital yang transparan dan dapat dicairkan.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex gap-3 bg-white bg-opacity-50 p-3 rounded-0 feature-box">
                                        <i class="bi bi-graph-up-arrow fs-3 text-success"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1">Transparansi Data</h6>
                                            <p class="text-muted small mb-0">Sistem terdigitalisasi mempermudah pemantauan laporan bagi nasabah maupun pengelola.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex gap-3 bg-white bg-opacity-50 p-3 rounded-0 feature-box">
                                        <i class="bi bi-buildings fs-3 text-success"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1">Visi Smart City</h6>
                                            <p class="text-muted small mb-0">Mendukung terwujudnya lingkungan perkotaan yang lebih bersih, hijau, dan berkelanjutan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section id="aplikasi" class="py-5 bg-white">
        <div class="container py-5">
            <div class="row align-items-center gy-5">
                
                <div class="col-lg-5 text-center">
                    <img src="{{ asset('images/Wireframe-HP.jpeg') }}" alt="Tampilan Aplikasi Smart Waste Bank" class="img-fluid rounded-4 shadow-lg" style="max-height: 550px; object-fit: cover;">
                </div>
                
                <div class="col-lg-7 ps-lg-5">
                    <span class="badge bg-success bg-opacity-10 text-success mb-3 px-3 py-2 rounded-pill fw-semibold">APLIKASI MOBILE</span>
                    <h3 class="fw-bold mb-4">Pantau Poin & Setoran Lebih Mudah di Genggaman</h3>
                    <p class="text-muted mb-4 fs-5">Aplikasi mahasiswa Smart Waste Bank didesain khusus agar Anda dapat memantau aktivitas daur ulang dengan cepat, kapan saja, dan di mana saja.</p>
                    
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="bg-light p-3 rounded-circle text-success fs-4">
                                    <i class="bi bi-wallet2"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Cek Saldo Real-time</h6>
                                    <p class="text-muted small mb-0">Poin masuk seketika setelah Anda menyetor sampah.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="bg-light p-3 rounded-circle text-success fs-4">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Riwayat Setoran</h6>
                                    <p class="text-muted small mb-0">Pantau terus rekam jejak kontribusi lingkungan Anda.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="bg-light p-3 rounded-circle text-success fs-4">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Lokasi Dropbox</h6>
                                    <p class="text-muted small mb-0">Temukan titik tong sampah pintar terdekat di kampus.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="bg-light p-3 rounded-circle text-success fs-4">
                                    <i class="bi bi-gift"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Tukar Poin Mudah</h6>
                                    <p class="text-muted small mb-0">Tukarkan poin Anda dengan berbagai reward menarik.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="cara-kerja" class="py-5 bg-light">
        <div class="ornament-blob-1"></div>
        <div class="ornament-blob-2"></div>
        <div class="ornament-dots"></div>
        <div class="ornament-dots-2"></div>

        <div class="container py-4 text-center">
            <h5 class="section-title mb-5">BAGAIMANA CARA KERJANYA?</h5>
            <div class="row gy-4">
                <div class="col-md-4">
                    <i class="bi bi-phone step-icon"></i>
                    <h6 class="fw-bold">1. Identifikasi Diri</h6>
                    <p class="text-muted small">Buka aplikasi dan scan barcode di bak.</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-speedometer2 step-icon"></i>
                    <h6 class="fw-bold">2. Timbang Otomatis</h6>
                    <p class="text-muted small">Masukkan sampah ke dalam tong IoT.</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-star-fill step-icon"></i>
                    <h6 class="fw-bold">3. Dapatkan Poin</h6>
                    <p class="text-muted small">Sistem IoT menimbang & mengirim poin ke HP-mu.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container py-4">
            <div class="row gy-3">
                <div class="col-md-3 col-6"><div class="stat-card"><div class="text-muted small">Mahasiswa</div><div class="stat-value">1,240</div></div></div>
                <div class="col-md-3 col-6"><div class="stat-card"><div class="text-muted small">Sampah</div><div class="stat-value">4,500 kg</div></div></div>
                <div class="col-md-3 col-6"><div class="stat-card"><div class="text-muted small">IoT Aktif</div><div class="stat-value">12 Unit</div></div></div>
                <div class="col-md-3 col-6"><div class="stat-card"><div class="text-muted small">Poin</div><div class="stat-value">125k pts</div></div></div>
            </div>
        </div>
    </section>

    <section id="faq" class="py-5 bg-light">
        <div class="container py-4">
            <h5 class="section-title mb-5">❓ PERTANYAAN UMUM (FAQ)</h5>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="accordionFAQ">
                        
                        <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Siapa saja yang bisa menggunakan Smart Waste Bank?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body text-muted small">
                                    Saat ini, Smart Waste Bank difokuskan untuk mahasiswa dan civitas akademika di lingkungan kampus. Anda bisa mendaftar dan memantau poin langsung melalui aplikasi mobile kami.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Bagaimana cara mendapatkan poin?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body text-muted small">
                                    Anda cukup datang ke lokasi tong sampah pintar (IoT) kami, buka aplikasi untuk identifikasi diri, lalu masukkan sampah Anda. Sistem IoT akan menimbang sampah secara otomatis dan poin akan langsung masuk ke akun Anda.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Apakah poin tersebut bisa diuangkan?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body text-muted small">
                                    Tentu saja! Poin yang telah Anda kumpulkan dapat dikonversi menjadi saldo digital yang bisa dicairkan (cuan) atau digunakan untuk berbagai keperluan sesuai kebijakan pengelola kampus.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Jenis sampah apa yang diterima oleh mesin IoT?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body text-muted small">
                                    Mesin IoT kami saat ini dirancang untuk menerima sampah daur ulang anorganik yang bernilai, seperti botol plastik, gelas plastik, dan material daur ulang sejenis yang telah ditentukan.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-banner py-5">
        <div class="container py-4">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-3">Siap Mengubah Sampahmu Menjadi Cuan?</h2>
                    <p class="fs-5 mb-4 text-white-50">Bergabunglah bersama ribuan mahasiswa lainnya. Kelola sampah lebih cerdas, raih poinnya, dan wujudkan lingkungan kampus yang lebih bersih.</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="#" class="btn btn-light btn-lg fw-bold px-4 rounded-pill">
                            <i class="bi bi-download me-2"></i> Unduh Aplikasi
                        </a>
                        <a href="#tentang" class="btn btn-outline-light btn-lg fw-bold px-4 rounded-pill">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="fat-footer">
        <div class="container">
            <div class="row gy-5">
                
                <div class="col-lg-5 pe-lg-5">
                    <div class="footer-brand">
                        <i class="bi bi-recycle"></i>
                        <div><span>SMART</span> WASTE BANK</div>
                    </div>
                    <p class="mb-4 text-secondary">
                        Sistem informasi inovatif yang memadukan kepedulian lingkungan dengan teknologi Internet of Things (IoT). Merubah sampah Anda menjadi saldo digital dengan cepat, transparan, dan terukur.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-secondary fs-5 hover-success"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-secondary fs-5 hover-success"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-secondary fs-5 hover-success"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="text-secondary fs-5 hover-success"><i class="bi bi-github"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-title">Tautan Cepat</h6>
                    <ul>
                        <li><a href="#">Beranda</a></li>
                        <li><a href="#tentang">Tentang Sistem</a></li>
                        <li><a href="#cara-kerja">Panduan Penggunaan</a></li>
                        <li><a href="#">Dashboard Admin</a></li>
                        <li><a href="#">Unduh Aplikasi</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h6 class="footer-title">Pusat Bantuan</h6>
                    <ul>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">FAQ / Pertanyaan Umum</a></li>
                    </ul>
                    
                    <h6 class="footer-title mt-4">Hubungi Kami</h6>
                    <p class="mb-1"><i class="bi bi-envelope me-2 text-success"></i> support@smartwaste.ac.id</p>
                    <p class="mb-0"><i class="bi bi-geo-alt me-2 text-success"></i> Politeknik Negeri Banjarmasin, Kalimantan Selatan</p>
                </div>

            </div>

            <div class="footer-bottom">
                <div>
                    &copy; {{ date('Y') }} Smart Waste Bank. Hak Cipta Dilindungi.
                </div>
                <div>
                    Dibuat untuk Tugas Akhir Sistem Informasi Terpadu.
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/finisher-header.es5.min.js') }}" type="text/javascript"></script>
    
    <script type="text/javascript">
        if (typeof FinisherHeader !== 'undefined') {
            new FinisherHeader({
                "count": 12,
                "size": { "min": 1300, "max": 1500, "pulse": 0.2 },
                "speed": { "x": { "min": 0.1, "max": 0.5 }, "y": { "min": 0.1, "max": 0.5 } },
                "colors": { "background": "#a3e5cd", "particles": [ "#364c44", "#6d9989", "#d1f2e6", "#e0f6ee" ] },
                "blending": "lighten",
                "opacity": { "center": 0.6, "edge": 0 },
                "skew": 0,
                "shapes": ["c"]
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            const navbar = document.getElementById("mainNav");
            window.addEventListener("scroll", function() {
                if (window.scrollY > 50) {
                    navbar.classList.add("navbar-scrolled");
                    navbar.classList.remove("navbar-transparent");
                } else {
                    navbar.classList.remove("navbar-scrolled");
                    navbar.classList.add("navbar-transparent");
                }
            });
        });

        document.getElementById('togglePassword').addEventListener('click', function () {
            const pwd = document.getElementById('password');
            const icon = this.querySelector('i');
            pwd.type = pwd.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>