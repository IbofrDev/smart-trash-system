# File Tree: smart-trash-system

**Generated:** 4/15/2026, 3:09:59 PM
**Root Path:** `c:\tugasakhir\smart-trash-system`

```
├── 📁 app
│   ├── 📁 Events
│   │   └── 🐘 TransaksiCreated.php
│   ├── 📁 Http
│   │   ├── 📁 Controllers
│   │   │   ├── 📁 Api
│   │   │   │   ├── 🐘 AchievementController.php
│   │   │   │   ├── 🐘 AuthController.php
│   │   │   │   ├── 🐘 DashboardController.php
│   │   │   │   ├── 🐘 HardwareController.php
│   │   │   │   ├── 🐘 LeaderboardController.php
│   │   │   │   ├── 🐘 NotifikasiController.php
│   │   │   │   ├── 🐘 ProfileController.php
│   │   │   │   └── 🐘 TransaksiController.php
│   │   │   ├── 📁 Web
│   │   │   │   ├── 🐘 AchievementController.php
│   │   │   │   ├── 🐘 AuthController.php
│   │   │   │   ├── 🐘 BakSampahController.php
│   │   │   │   ├── 🐘 DashboardController.php
│   │   │   │   ├── 🐘 JenisSampahController.php
│   │   │   │   ├── 🐘 LaporanController.php
│   │   │   │   ├── 🐘 LevelController.php
│   │   │   │   ├── 🐘 LogAktivitasController.php
│   │   │   │   ├── 🐘 LokasiController.php
│   │   │   │   ├── 🐘 MahasiswaController.php
│   │   │   │   ├── 🐘 SettingPoinController.php
│   │   │   │   ├── 🐘 TransaksiController.php
│   │   │   │   └── 🐘 UserController.php
│   │   │   └── 🐘 Controller.php
│   │   └── 📁 Middleware
│   │       ├── 🐘 HardwareApiKeyMiddleware.php
│   │       └── 🐘 RoleMiddleware.php
│   ├── 📁 Listeners
│   │   ├── 🐘 ProcessGamifikasi.php
│   │   └── 🐘 UpdateLeaderboard.php
│   ├── 📁 Models
│   │   ├── 🐘 Achievement.php
│   │   ├── 🐘 BakSampah.php
│   │   ├── 🐘 JenisSampah.php
│   │   ├── 🐘 Leaderboard.php
│   │   ├── 🐘 Level.php
│   │   ├── 🐘 LogAktivitas.php
│   │   ├── 🐘 Lokasi.php
│   │   ├── 🐘 Mahasiswa.php
│   │   ├── 🐘 MahasiswaAchievement.php
│   │   ├── 🐘 Notifikasi.php
│   │   ├── 🐘 SettingPoin.php
│   │   ├── 🐘 TransaksiSampah.php
│   │   └── 🐘 User.php
│   ├── 📁 Providers
│   │   ├── 🐘 AppServiceProvider.php
│   │   └── 🐘 EventServiceProvider.php
│   └── 📁 Services
│       └── 🐘 GamifikasiService.php
├── 📁 bootstrap
│   ├── 🐘 app.php
│   └── 🐘 providers.php
├── 📁 config
│   ├── 🐘 app.php
│   ├── 🐘 auth.php
│   ├── 🐘 cache.php
│   ├── 🐘 database.php
│   ├── 🐘 db-snapshots.php
│   ├── 🐘 filesystems.php
│   ├── 🐘 logging.php
│   ├── 🐘 mail.php
│   ├── 🐘 queue.php
│   ├── 🐘 sanctum.php
│   ├── 🐘 services.php
│   └── 🐘 session.php
├── 📁 database
│   ├── 📁 factories
│   │   └── 🐘 UserFactory.php
│   ├── 📁 migrations
│   │   ├── 🐘 2026_03_19_032237_create_personal_access_tokens_table.php
│   │   ├── 🐘 2026_03_19_032302_create_cache_table.php
│   │   ├── 🐘 2026_03_19_032303_create_jobs_table.php
│   │   ├── 🐘 2026_03_19_032303_create_level_table.php
│   │   ├── 🐘 2026_03_19_032303_create_sessions_table.php
│   │   ├── 🐘 2026_03_19_032304_create_lokasi_table.php
│   │   ├── 🐘 2026_03_19_032304_create_mahasiswa_table.php
│   │   ├── 🐘 2026_03_19_032304_create_users_table.php
│   │   ├── 🐘 2026_03_19_032305_create_achievement_table.php
│   │   ├── 🐘 2026_03_19_032305_create_bak_sampah_table.php
│   │   ├── 🐘 2026_03_19_032305_create_jenis_sampah_table.php
│   │   ├── 🐘 2026_03_19_032306_create_leaderboard_table.php
│   │   ├── 🐘 2026_03_19_032306_create_setting_poin_table.php
│   │   ├── 🐘 2026_03_19_032306_create_transaksi_sampah_table.php
│   │   ├── 🐘 2026_03_19_032307_create_mahasiswa_achievement_table.php
│   │   ├── 🐘 2026_03_19_032307_create_notifikasi_table.php
│   │   ├── 🐘 2026_03_19_032309_create_log_aktivitas_table.php
│   │   ├── 🐘 2026_03_22_051905_add_timestamps_to_mahasiswa_achievement_table.php
│   │   └── 🐘 2026_03_22_052140_add_updated_at_to_notifikasi_table.php
│   ├── 📁 seeders
│   │   ├── 🐘 AchievementSeeder.php
│   │   ├── 🐘 BakSampahSeeder.php
│   │   ├── 🐘 DatabaseSeeder.php
│   │   ├── 🐘 JenisSampahSeeder.php
│   │   ├── 🐘 LevelSeeder.php
│   │   ├── 🐘 LokasiSeeder.php
│   │   ├── 🐘 SettingPoinSeeder.php
│   │   └── 🐘 UserSeeder.php
│   ├── 📁 snapshots
│   └── ⚙️ .gitignore
├── 📁 public
│   ├── ⚙️ .htaccess
│   ├── 📄 favicon.ico
│   ├── 🐘 index.php
│   └── 📄 robots.txt
├── 📁 resources
│   ├── 📁 css
│   │   └── 🎨 app.css
│   ├── 📁 js
│   │   ├── 📄 app.js
│   │   └── 📄 bootstrap.js
│   └── 📁 views
│       ├── 📁 admin
│       │   ├── 📁 achievement
│       │   │   ├── 🐘 create.blade.php
│       │   │   ├── 🐘 edit.blade.php
│       │   │   └── 🐘 index.blade.php
│       │   ├── 📁 bak-sampah
│       │   │   ├── 🐘 create.blade.php
│       │   │   ├── 🐘 edit.blade.php
│       │   │   ├── 🐘 index.blade.php
│       │   │   └── 🐘 show.blade.php
│       │   ├── 📁 jenis-sampah
│       │   │   ├── 🐘 create.blade.php
│       │   │   ├── 🐘 edit.blade.php
│       │   │   └── 🐘 index.blade.php
│       │   ├── 📁 laporan
│       │   │   ├── 🐘 mahasiswa-pdf.blade.php
│       │   │   ├── 🐘 mahasiswa.blade.php
│       │   │   ├── 🐘 transaksi-pdf.blade.php
│       │   │   └── 🐘 transaksi.blade.php
│       │   ├── 📁 level
│       │   │   ├── 🐘 create.blade.php
│       │   │   ├── 🐘 edit.blade.php
│       │   │   └── 🐘 index.blade.php
│       │   ├── 📁 log-aktivitas
│       │   │   └── 🐘 index.blade.php
│       │   ├── 📁 lokasi
│       │   │   ├── 🐘 create.blade.php
│       │   │   ├── 🐘 edit.blade.php
│       │   │   ├── 🐘 index.blade.php
│       │   │   └── 🐘 show.blade.php
│       │   ├── 📁 mahasiswa
│       │   │   ├── 🐘 edit.blade.php
│       │   │   ├── 🐘 index.blade.php
│       │   │   └── 🐘 show.blade.php
│       │   ├── 📁 setting-poin
│       │   │   ├── 🐘 edit.blade.php
│       │   │   └── 🐘 index.blade.php
│       │   ├── 📁 transaksi
│       │   │   ├── 🐘 index.blade.php
│       │   │   └── 🐘 show.blade.php
│       │   ├── 📁 users
│       │   │   ├── 🐘 create.blade.php
│       │   │   ├── 🐘 edit.blade.php
│       │   │   ├── 🐘 index.blade.php
│       │   │   └── 🐘 show.blade.php
│       │   └── 🐘 dashboard.blade.php
│       ├── 📁 auth
│       │   └── 🐘 login.blade.php
│       ├── 📁 components
│       │   ├── 🐘 alert.blade.php
│       │   ├── 🐘 delete-modal.blade.php
│       │   └── 🐘 pagination.blade.php
│       ├── 📁 layouts
│       │   └── 🐘 admin.blade.php
│       └── 🐘 welcome.blade.php
├── 📁 routes
│   ├── 🐘 api.php
│   ├── 🐘 console.php
│   └── 🐘 web.php
├── 📁 storage
│   ├── 📁 app
│   │   ├── 📁 laravel-db-snapshots
│   │   ├── 📁 private
│   │   │   └── ⚙️ .gitignore
│   │   ├── 📁 public
│   │   │   └── ⚙️ .gitignore
│   │   └── ⚙️ .gitignore
│   ├── 📁 framework
│   │   ├── 📁 sessions
│   │   │   └── ⚙️ .gitignore
│   │   ├── 📁 testing
│   │   │   └── ⚙️ .gitignore
│   │   ├── 📁 views
│   │   │   ├── ⚙️ .gitignore
│   │   │   ├── 🐘 01f67e443f5995cd9d91ea79659d0b71.php
│   │   │   ├── 🐘 046a78998bd9dcd326d65fafbe3cdef7.php
│   │   │   ├── 🐘 063d66f77900fb3ab6dfd559a60472a9.php
│   │   │   ├── 🐘 079724dac468ab0f6b4f83cc25c31c8c.php
│   │   │   ├── 🐘 0806d61b8a259f078e24f5fcce55900b.php
│   │   │   ├── 🐘 0c29d5926b76d1f42bd2e9822c5d428e.php
│   │   │   ├── 🐘 0f693cd0540e6212bbba85212d1608b3.php
│   │   │   ├── 🐘 126802033f42eecdec831a45b63a844d.php
│   │   │   ├── 🐘 15b9a14dcc9d6a5558ee48fffc4f08c6.php
│   │   │   ├── 🐘 1c7fc44fceaa9aff6ba0113640be98e5.php
│   │   │   ├── 🐘 1e154e4307f9f529ec1f3dff6312acf7.php
│   │   │   ├── 🐘 1fac2b42a72ad80ab0a9b87aaab294aa.php
│   │   │   ├── 🐘 22100dd25f2328ed06b291573bcce492.php
│   │   │   ├── 🐘 27711974ae05d1a410a473be4db51fa2.php
│   │   │   ├── 🐘 2bb93749965c9a8e68294ac4a7159f0b.php
│   │   │   ├── 🐘 30c6689b02d5ff88a6f2d34d267016c9.php
│   │   │   ├── 🐘 30eb4a1e9e82001e6c63bf2be163739d.php
│   │   │   ├── 🐘 3bca52330ec9e0bb328229aaf3a308bf.php
│   │   │   ├── 🐘 3c1057856f560dde92daa1ce92077230.php
│   │   │   ├── 🐘 4666cf6943ea9dfc84c9ba46956fad0a.php
│   │   │   ├── 🐘 4c9811afcf2951d3b68164367a8863d6.php
│   │   │   ├── 🐘 4fda1b921eb0eca7c21583ff79dbc345.php
│   │   │   ├── 🐘 50456b153e58224e268ce16cc30e63e1.php
│   │   │   ├── 🐘 510024bf9ad22238e9e606f5454e197b.php
│   │   │   ├── 🐘 5f1800c3b53dd7db82c28ccd11d6943e.php
│   │   │   ├── 🐘 61081e3ebe6fc918a47f3ef8fb95e468.php
│   │   │   ├── 🐘 6193e874c60238e5d4de374c3366fb72.php
│   │   │   ├── 🐘 643473f359f426d432ae9beaf48ece7b.php
│   │   │   ├── 🐘 6a35c31b1a13db1ddda85f658133fcca.php
│   │   │   ├── 🐘 6b5d23d7bca3c9703380a2dac20a60c5.php
│   │   │   ├── 🐘 6bd7936fe3fbb6141b98f48702efe60a.php
│   │   │   ├── 🐘 6e23d86e081aa4480d43597a7118bade.php
│   │   │   ├── 🐘 754c27eb8339c10f1cf9853a67060f62.php
│   │   │   ├── 🐘 76ad111c732aa95e0a4c1ce761c53895.php
│   │   │   ├── 🐘 7d064ef46a592db1be4c1a4a3e435ced.php
│   │   │   ├── 🐘 8052e1bfb4c8d0a1f5065ab671ecabfd.php
│   │   │   ├── 🐘 85fd305ed5763184c55106319ab01a7e.php
│   │   │   ├── 🐘 8628d995a19ffa02cc21524e994aec3d.php
│   │   │   ├── 🐘 8844cd79fd562c9b6cb01ad52cada7fc.php
│   │   │   ├── 🐘 8d5b6ead9149b285ba6124da7b3e57d3.php
│   │   │   ├── 🐘 9029966d749bcdc675f776e60ea62267.php
│   │   │   ├── 🐘 90c01398f93674cc51dd9c891f341bf9.php
│   │   │   ├── 🐘 92f2dd4733637fa1132822bbf72db439.php
│   │   │   ├── 🐘 977cbbbcf0712214379180c5a8455e26.php
│   │   │   ├── 🐘 a57f02142fa3b82a12c542bc55e9214f.php
│   │   │   ├── 🐘 a8881cbad82be202110c0271b5a23db2.php
│   │   │   ├── 🐘 a92fd56c66012a304fc0ab4e27bc8a80.php
│   │   │   ├── 🐘 ac4158e2876a8bf25d2eabaa601f6166.php
│   │   │   ├── 🐘 ad121e280a911166ba701004bb292d18.php
│   │   │   ├── 🐘 ae7cc2c2d6acc6d40a63980b54ed60e7.php
│   │   │   ├── 🐘 bacf80dd81d619ac4bc9a1352a9b4e0e.php
│   │   │   ├── 🐘 c5ef6b6380a488025013f4ed62e4b084.php
│   │   │   ├── 🐘 c9739f60918b417880802dbae1c40730.php
│   │   │   ├── 🐘 d83e5e710e6b6e33bf9dcb5b6a29b744.php
│   │   │   ├── 🐘 d95cb9abd0e9bdc21a4a23bee09408ac.php
│   │   │   ├── 🐘 e1051b98d33f9b1e4fe48ec6baeb1663.php
│   │   │   ├── 🐘 e1abdf3041cd9dab7ac4301d6033fbe1.php
│   │   │   ├── 🐘 e3f1f2e5297f80230459da0861c1ccb1.php
│   │   │   ├── 🐘 f573e6494d44d4f28440c574b0e90107.php
│   │   │   ├── 🐘 fe4d60d897f28c5ff5b78609eec38cbf.php
│   │   │   └── 🐘 fee6f930a86e9902d653b091080b17e3.php
│   │   └── ⚙️ .gitignore
│   └── 📁 logs
│       └── ⚙️ .gitignore
├── 📁 tests
│   ├── 📁 Feature
│   │   └── 🐘 ExampleTest.php
│   ├── 📁 Unit
│   │   └── 🐘 ExampleTest.php
│   └── 🐘 TestCase.php
├── ⚙️ .editorconfig
├── ⚙️ .env.example
├── ⚙️ .gitattributes
├── ⚙️ .gitignore
├── 📝 README.md
├── 📄 artisan
├── ⚙️ composer.json
├── 📄 grup1-controllers.txt
├── 📄 grup2-views.txt
├── 📄 grup3-4-routes-models.txt
├── ⚙️ package.json
├── ⚙️ phpunit.xml
├── 📄 smart_trash_db.sql
└── 📄 vite.config.js
```

---
*Generated by FileTree Pro Extension*