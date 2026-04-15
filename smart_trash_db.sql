-- MySQL dump 10.13  Distrib 8.4.2, for Win64 (x86_64)
--
-- Host: localhost    Database: smart_trash_db
-- ------------------------------------------------------
-- Server version	8.4.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `achievement`
--

DROP TABLE IF EXISTS `achievement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `achievement` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `syarat_type` enum('total_kg','streak','transaksi_count','first_time') COLLATE utf8mb4_unicode_ci NOT NULL,
  `syarat_value` int NOT NULL DEFAULT '0',
  `poin_bonus` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `achievement`
--

LOCK TABLES `achievement` WRITE;
/*!40000 ALTER TABLE `achievement` DISABLE KEYS */;
INSERT INTO `achievement` VALUES (1,'First Timer','Selamat! Kamu telah melakukan setoran sampah pertama.','first_timer.png','first_time',1,50,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(2,'Week Streak','Melakukan setoran sampah selama 7 hari berturut-turut.','week_streak.png','streak',7,500,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(3,'Monthly Hero','Melakukan 20 kali transaksi dalam satu bulan.','monthly_hero.png','transaksi_count',20,500,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(4,'Century Club','Total berat sampah yang disetorkan mencapai 100 kg.','century_club.png','total_kg',100,1000,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(5,'Half Ton Hero','Total berat sampah yang disetorkan mencapai 500 kg.','half_ton_hero.png','total_kg',500,2000,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(6,'Two Week Warrior','Melakukan setoran sampah selama 14 hari berturut-turut.','two_week_warrior.png','streak',14,1000,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(7,'Fifty Transactions','Melakukan 50 kali transaksi setoran sampah.','fifty_transactions.png','transaksi_count',50,1500,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(8,'Ton Master','Total berat sampah yang disetorkan mencapai 1000 kg.','ton_master.png','total_kg',1000,5000,'2026-03-18 19:37:00','2026-03-18 19:37:00');
/*!40000 ALTER TABLE `achievement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bak_sampah`
--

DROP TABLE IF EXISTS `bak_sampah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bak_sampah` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi_id` bigint unsigned NOT NULL,
  `api_key` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','nonaktif','maintenance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `kapasitas_max` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bak_sampah_api_key_unique` (`api_key`),
  KEY `bak_sampah_lokasi_id_foreign` (`lokasi_id`),
  CONSTRAINT `bak_sampah_lokasi_id_foreign` FOREIGN KEY (`lokasi_id`) REFERENCES `lokasi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bak_sampah`
--

LOCK TABLES `bak_sampah` WRITE;
/*!40000 ALTER TABLE `bak_sampah` DISABLE KEYS */;
INSERT INTO `bak_sampah` VALUES (1,'Smart Bin A1',1,'RcQIczv1rVRlmSisZqLeNZYQmX7ZrxzbmBTflNRQ3xE4Yno1EsbRCZunQorFqkzS','aktif',50.00,'2026-03-18 19:50:11','2026-03-18 19:50:11'),(2,'Smart Bin B2',2,'9AHb9ENzurqlySrCf4t8Nwx19IdtQDYnRMuSjTyKogl42FNtM5KAdbszb5uuoZOZ','aktif',50.00,'2026-03-18 19:50:11','2026-03-18 19:50:11'),(3,'Smart Bin Kantin',3,'OqP7jFUWqwRCPPADdPn70kt1S8naCndq55Qp2KYpbYeZHpE2cNUYRGomPf6ZsdkD','aktif',75.00,'2026-03-18 19:50:11','2026-03-18 19:50:11');
/*!40000 ALTER TABLE `bak_sampah` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jenis_sampah`
--

DROP TABLE IF EXISTS `jenis_sampah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_sampah` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `poin_per_kg` int NOT NULL DEFAULT '0',
  `satuan` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kg',
  `is_active` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jenis_sampah`
--

LOCK TABLES `jenis_sampah` WRITE;
/*!40000 ALTER TABLE `jenis_sampah` DISABLE KEYS */;
INSERT INTO `jenis_sampah` VALUES (1,'Plastik','Botol plastik, kantong plastik, kemasan plastik',100,'kg',1,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(2,'Kertas','Kertas HVS, kardus, koran, majalah',80,'kg',1,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(3,'Logam','Kaleng aluminium, besi, tembaga',150,'kg',1,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(4,'Kaca','Botol kaca, pecahan kaca',70,'kg',1,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(5,'Elektronik','Komponen elektronik, kabel, baterai',200,'kg',1,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(6,'Organik','Sisa makanan, daun, ranting',50,'kg',1,'2026-03-18 19:37:00','2026-03-18 19:37:00');
/*!40000 ALTER TABLE `jenis_sampah` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leaderboard`
--

DROP TABLE IF EXISTS `leaderboard`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leaderboard` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `ranking_harian` int DEFAULT NULL,
  `ranking_mingguan` int DEFAULT NULL,
  `ranking_bulanan` int DEFAULT NULL,
  `ranking_alltime` int DEFAULT NULL,
  `total_berat_kg` decimal(10,2) NOT NULL DEFAULT '0.00',
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leaderboard_mahasiswa_id_foreign` (`mahasiswa_id`),
  CONSTRAINT `leaderboard_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leaderboard`
--

LOCK TABLES `leaderboard` WRITE;
/*!40000 ALTER TABLE `leaderboard` DISABLE KEYS */;
INSERT INTO `leaderboard` VALUES (1,3,NULL,NULL,NULL,1,10.00,'2026-03-21 21:46:47');
/*!40000 ALTER TABLE `leaderboard` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `level`
--

DROP TABLE IF EXISTS `level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `level` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_level` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_poin` int NOT NULL DEFAULT '0',
  `max_poin` int NOT NULL DEFAULT '0',
  `urutan` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `level_urutan_unique` (`urutan`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `level`
--

LOCK TABLES `level` WRITE;
/*!40000 ALTER TABLE `level` DISABLE KEYS */;
INSERT INTO `level` VALUES (1,'Eco Starter',0,999,1,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(2,'Green Warrior',1000,2999,2,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(3,'Recycler',3000,5999,3,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(4,'Eco Champion',6000,9999,4,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(5,'Planet Guardian',10000,19999,5,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(6,'Eco Legend',20000,999999,6,'2026-03-18 19:37:00','2026-03-18 19:37:00');
/*!40000 ALTER TABLE `level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_aktivitas`
--

DROP TABLE IF EXISTS `log_aktivitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_aktivitas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_type` enum('admin','pengelola','mahasiswa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `aktivitas` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tabel_target` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_aktivitas`
--

LOCK TABLES `log_aktivitas` WRITE;
/*!40000 ALTER TABLE `log_aktivitas` DISABLE KEYS */;
INSERT INTO `log_aktivitas` VALUES (1,'admin',1,'Login ke sistem',NULL,'127.0.0.1','2026-03-21 22:22:04'),(2,'admin',1,'Login ke sistem',NULL,'127.0.0.1','2026-03-23 21:34:16'),(3,'admin',1,'Login ke sistem',NULL,'127.0.0.1','2026-03-23 21:35:03'),(4,'admin',1,'Login ke sistem',NULL,'127.0.0.1','2026-03-24 19:14:49');
/*!40000 ALTER TABLE `log_aktivitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lokasi`
--

DROP TABLE IF EXISTS `lokasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lokasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_lokasi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `koordinat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lokasi`
--

LOCK TABLES `lokasi` WRITE;
/*!40000 ALTER TABLE `lokasi` DISABLE KEYS */;
INSERT INTO `lokasi` VALUES (1,'Gedung A - Lantai 1','Kampus Politeknik Negeri Banjarmasin','-3.3191,114.5903','2026-03-18 19:50:09','2026-03-18 19:50:09'),(2,'Gedung B - Lantai 2','Kampus Politeknik Negeri Banjarmasin','-3.3192,114.5904','2026-03-18 19:50:09','2026-03-18 19:50:09'),(3,'Kantin Utama','Kampus Politeknik Negeri Banjarmasin','-3.3193,114.5905','2026-03-18 19:50:09','2026-03-18 19:50:09');
/*!40000 ALTER TABLE `lokasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mahasiswa`
--

DROP TABLE IF EXISTS `mahasiswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mahasiswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `google_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prodi` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nim` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rfid_uid` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_poin` int NOT NULL DEFAULT '0',
  `level_id` bigint unsigned NOT NULL,
  `fcm_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mahasiswa_google_id_unique` (`google_id`),
  UNIQUE KEY `mahasiswa_email_unique` (`email`),
  UNIQUE KEY `mahasiswa_rfid_uid_unique` (`rfid_uid`),
  KEY `mahasiswa_level_id_foreign` (`level_id`),
  CONSTRAINT `mahasiswa_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `level` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mahasiswa`
--

LOCK TABLES `mahasiswa` WRITE;
/*!40000 ALTER TABLE `mahasiswa` DISABLE KEYS */;
INSERT INTO `mahasiswa` VALUES (1,'manual-google-001','budi@example.com','Budi',NULL,'Informatika','220001','RFID001',150,1,NULL,'2026-03-18 20:12:55','2026-03-18 20:15:20'),(2,'test-google-123','test@mahasiswa.com','Mahasiswa Test','https://via.placeholder.com/150','Teknik Informatika','220002','RFID002',500,2,NULL,'2026-03-18 20:33:51','2026-03-18 20:33:51'),(3,'test-gamif-001','gamif@test.com','Test Gamifikasi',NULL,'Informatika','220003','RFID_GAMIF',1150,2,NULL,'2026-03-21 21:11:54','2026-03-21 21:46:47');
/*!40000 ALTER TABLE `mahasiswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mahasiswa_achievement`
--

DROP TABLE IF EXISTS `mahasiswa_achievement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mahasiswa_achievement` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `achievement_id` bigint unsigned NOT NULL,
  `unlocked_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mahasiswa_achievement_mahasiswa_id_achievement_id_unique` (`mahasiswa_id`,`achievement_id`),
  KEY `mahasiswa_achievement_achievement_id_foreign` (`achievement_id`),
  CONSTRAINT `mahasiswa_achievement_achievement_id_foreign` FOREIGN KEY (`achievement_id`) REFERENCES `achievement` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mahasiswa_achievement_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mahasiswa_achievement`
--

LOCK TABLES `mahasiswa_achievement` WRITE;
/*!40000 ALTER TABLE `mahasiswa_achievement` DISABLE KEYS */;
INSERT INTO `mahasiswa_achievement` VALUES (2,3,1,'2026-03-21 21:22:34','2026-03-21 21:22:34','2026-03-21 21:22:34');
/*!40000 ALTER TABLE `mahasiswa_achievement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026_03_19_032237_create_personal_access_tokens_table',1),(2,'2026_03_19_032302_create_cache_table',1),(3,'2026_03_19_032303_create_jobs_table',1),(4,'2026_03_19_032303_create_level_table',1),(5,'2026_03_19_032303_create_sessions_table',1),(6,'2026_03_19_032304_create_lokasi_table',1),(7,'2026_03_19_032304_create_mahasiswa_table',1),(8,'2026_03_19_032304_create_users_table',1),(9,'2026_03_19_032305_create_achievement_table',1),(10,'2026_03_19_032305_create_bak_sampah_table',1),(11,'2026_03_19_032305_create_jenis_sampah_table',1),(12,'2026_03_19_032306_create_leaderboard_table',1),(13,'2026_03_19_032306_create_setting_poin_table',1),(14,'2026_03_19_032306_create_transaksi_sampah_table',1),(15,'2026_03_19_032307_create_mahasiswa_achievement_table',1),(16,'2026_03_19_032307_create_notifikasi_table',1),(18,'2026_03_19_032309_create_log_aktivitas_table',2),(19,'2026_03_22_051905_add_timestamps_to_mahasiswa_achievement_table',3),(20,'2026_03_22_052140_add_updated_at_to_notifikasi_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifikasi`
--

DROP TABLE IF EXISTS `notifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifikasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `judul` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` enum('level_up','achievement','ranking','info') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `is_read` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifikasi_mahasiswa_id_foreign` (`mahasiswa_id`),
  CONSTRAINT `notifikasi_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifikasi`
--

LOCK TABLES `notifikasi` WRITE;
/*!40000 ALTER TABLE `notifikasi` DISABLE KEYS */;
INSERT INTO `notifikasi` VALUES (1,3,'Achievement Unlocked! 🏆','Kamu mendapatkan achievement \'First Timer\'! Bonus +50 poin!','achievement',0,'2026-03-21 21:22:34','2026-03-21 21:22:34'),(2,3,'Level Up! 🎉','Selamat! Kamu naik ke level Green Warrior! Bonus +100 poin!','level_up',0,'2026-03-21 21:46:47','2026-03-21 21:46:47');
/*!40000 ALTER TABLE `notifikasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\Mahasiswa',2,'test-token','5b8c06fd292b85a1fc1212bf471ef9492e9c77b2fd7b721b0e16700dae2a1fae','[\"*\"]','2026-03-18 20:41:13',NULL,'2026-03-18 20:33:56','2026-03-18 20:41:13'),(2,'App\\Models\\Mahasiswa',1,'mobile-app','bdd85c5d300a3654d7368f7268fba28bf9e1bc739908b96ff11196aa72c66e1a','[\"*\"]',NULL,NULL,'2026-03-26 19:43:23','2026-03-26 19:43:23'),(3,'App\\Models\\Mahasiswa',1,'mobile-app','de05dc4438fe5cc885acd7f5c49e830d93c2a06837a6a19d6185a99002b6a15c','[\"*\"]','2026-03-26 19:59:33',NULL,'2026-03-26 19:53:22','2026-03-26 19:59:33'),(4,'App\\Models\\Mahasiswa',1,'mobile-app','3038de384eeb1275a1ed4f36d4e52bc77778327a71d46046cdbec7d7920901b0','[\"*\"]','2026-03-26 20:02:13',NULL,'2026-03-26 20:01:47','2026-03-26 20:02:13'),(5,'App\\Models\\Mahasiswa',1,'mobile-app','b488d647e57c091ec7fae624367a32e9f5def1c7f5669b8f617bbc961769fccf','[\"*\"]','2026-03-26 20:07:57',NULL,'2026-03-26 20:06:15','2026-03-26 20:07:57'),(6,'App\\Models\\Mahasiswa',1,'mobile-app','97a65ab203734b1179fe6e90af8a6c2a88f32edf5216a07ce24c56abf4d77756','[\"*\"]','2026-03-26 20:14:31',NULL,'2026-03-26 20:11:28','2026-03-26 20:14:31');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('9G1RrCAzaytBjykWPdO3Ne3DhhC3bs6NqIPjoL6m',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko','eyJfdG9rZW4iOiJaRU9yaGtoMHFZZFRoQ0hmTFlpRFhJS2pXOENRNkFvUndmWWNaRWh1IiwiZXJyb3IiOiJTaWxha2FuIGxvZ2luIHRlcmxlYmloIGRhaHVsdS4iLCJfZmxhc2giOnsibmV3IjpbXSwib2xkIjpbImVycm9yIl19LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9sb2ctYWt0aXZpdGFzIiwicm91dGUiOiJhZG1pbi5sb2ctYWt0aXZpdGFzLmluZGV4In19',1774408685),('g7jCv2rncLcR9N5akqkjmThbmK0pd5PDaCFqEXyg',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko','eyJfdG9rZW4iOiJ5MlYwdlhXY1lKOG45QW1iSklESnJWbWttdU4zVVhYZHB5OEI0MzBTIiwiZXJyb3IiOiJTaWxha2FuIGxvZ2luIHRlcmxlYmloIGRhaHVsdS4iLCJfZmxhc2giOnsibmV3IjpbXSwib2xkIjpbImVycm9yIl19LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9sYXBvcmFuXC9tYWhhc2lzd2EiLCJyb3V0ZSI6ImFkbWluLmxhcG9yYW4ubWFoYXNpc3dhIn19',1774408686),('m7UJnCMMxXr9dyoLqL2L5YijqQDr93fqR828F8T9',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0','eyJfdG9rZW4iOiJpcjNxTTlIRGhoQXpwdDEybXU2T3o3TlY1cnA5R2E0Y3FBWkxjbmVoIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvbGV2ZWwiLCJyb3V0ZSI6ImFkbWluLmxldmVsLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9',1774408930),('oUhwfwi7jiDaBbPFr83fL7UhgOTiNngUJdbq0XJq',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko','eyJfdG9rZW4iOiJrd3JNZzZHNWJOeTg1UkViaW85MlZSYUVNVjlKM25hZkx3NktaVTZFIiwiZXJyb3IiOiJTaWxha2FuIGxvZ2luIHRlcmxlYmloIGRhaHVsdS4iLCJfZmxhc2giOnsibmV3IjpbXSwib2xkIjpbImVycm9yIl19LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9sYXBvcmFuXC9tYWhhc2lzd2EiLCJyb3V0ZSI6ImFkbWluLmxhcG9yYW4ubWFoYXNpc3dhIn19',1774408707),('qfNI6Fx22TFTCDzeeQKFCglvoRsMFxrtXVyx4hDw',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko','eyJfdG9rZW4iOiJ3Zjc5RG1Tb2NEbGxKTUNmUFZaZGt5N2RINkM1dmlURlFRdm90Wnp6IiwiZXJyb3IiOiJTaWxha2FuIGxvZ2luIHRlcmxlYmloIGRhaHVsdS4iLCJfZmxhc2giOnsibmV3IjpbXSwib2xkIjpbImVycm9yIl19LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9sb2ctYWt0aXZpdGFzIiwicm91dGUiOiJhZG1pbi5sb2ctYWt0aXZpdGFzLmluZGV4In19',1774408707);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setting_poin`
--

DROP TABLE IF EXISTS `setting_poin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `setting_poin` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_setting` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` int NOT NULL DEFAULT '0',
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_poin_nama_setting_unique` (`nama_setting`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setting_poin`
--

LOCK TABLES `setting_poin` WRITE;
/*!40000 ALTER TABLE `setting_poin` DISABLE KEYS */;
INSERT INTO `setting_poin` VALUES (1,'bonus_level_up',100,'Bonus poin yang didapat saat naik level','2026-03-18 19:37:00','2026-03-18 19:37:00'),(2,'minimum_berat',10,'Berat minimum (gram) untuk transaksi valid','2026-03-18 19:37:00','2026-03-18 19:37:00'),(3,'maksimum_berat_harian',50000,'Berat maksimum (gram) per hari per mahasiswa','2026-03-18 19:37:00','2026-03-18 19:37:00');
/*!40000 ALTER TABLE `setting_poin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi_sampah`
--

DROP TABLE IF EXISTS `transaksi_sampah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaksi_sampah` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `bak_sampah_id` bigint unsigned NOT NULL,
  `jenis_sampah_id` bigint unsigned NOT NULL,
  `berat` decimal(10,2) NOT NULL,
  `poin_didapat` int NOT NULL DEFAULT '0',
  `tanggal_transaksi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `transaksi_sampah_mahasiswa_id_foreign` (`mahasiswa_id`),
  KEY `transaksi_sampah_bak_sampah_id_foreign` (`bak_sampah_id`),
  KEY `transaksi_sampah_jenis_sampah_id_foreign` (`jenis_sampah_id`),
  CONSTRAINT `transaksi_sampah_bak_sampah_id_foreign` FOREIGN KEY (`bak_sampah_id`) REFERENCES `bak_sampah` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_sampah_jenis_sampah_id_foreign` FOREIGN KEY (`jenis_sampah_id`) REFERENCES `jenis_sampah` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_sampah_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi_sampah`
--

LOCK TABLES `transaksi_sampah` WRITE;
/*!40000 ALTER TABLE `transaksi_sampah` DISABLE KEYS */;
INSERT INTO `transaksi_sampah` VALUES (1,1,1,1,1.50,150,'2026-03-18 20:15:20'),(6,3,1,1,2.50,250,'2026-03-21 21:22:34'),(7,3,1,1,7.50,750,'2026-03-21 21:46:47');
/*!40000 ALTER TABLE `transaksi_sampah` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','pengelola') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pengelola',
  `is_active` tinyint NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin@smarttrash.com','$2y$12$HIfxXKghTcv3rBbz6B1h9u4SeAhbsSizzSJI.GIA9cK2jlOGwyzq2','081234567890','admin',1,NULL,'2026-03-18 19:37:00','2026-03-18 19:37:00'),(2,'Pengelola 1','pengelola@smarttrash.com','$2y$12$KffCl2ZaavOzVy9pSFuSyOPYWmOYBJ57nKhZTx17GwzuVeLmCGYC2','081234567891','pengelola',1,NULL,'2026-03-18 19:37:00','2026-03-18 19:37:00');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-02 11:35:57
