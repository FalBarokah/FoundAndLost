-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Nov 2026 pada 02.03
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `found_and_lost`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admins`
--

CREATE TABLE `admins` (
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'Admin',
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `admins`
--

INSERT INTO `admins` (`admin_id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$12$o0BZTmG3hHQHeIxsHZzjtu2UfzLVR3/G3QFpFJ0EVyEbuecsp8bRS', NULL, '2026-11-08 17:03:33', '2026-11-08 17:59:29'),
(2, 'Satpam', 'satpam@gmail.com', 'Hallo321', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `claims`
--

CREATE TABLE `claims` (
  `claim_id` bigint(20) UNSIGNED NOT NULL,
  `claim_code` varchar(30) NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `claimant_name` varchar(100) NOT NULL,
  `claimant_phone` varchar(20) NOT NULL,
  `proof_description` text NOT NULL,
  `proof_photo_url` varchar(255) DEFAULT NULL,
  `status` enum('menunggu','ditindaklanjuti','dibatalkan') NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `claims`
--

INSERT INTO `claims` (`claim_id`, `claim_code`, `report_id`, `claimant_name`, `claimant_phone`, `proof_description`, `proof_photo_url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'KLM-2026-0001', 5, 'Rina Aulia', '081234567899', 'Kunci motor Vario 160 hitam plat B 4123 KZA, ada gantungan boneka beruang warna coklat dan ada bekas goresan dekat pangkal anak kunci.', NULL, 'ditindaklanjuti', '2026-11-08 17:03:33', '2026-11-08 17:03:33'),
(2, 'KLM-2026-0002', 6, 'Dewi Sartika', '081234567891', 'Kartu pelajar atas nama saya sendiri Dewi Sartika, NIS 22231012, jurusan Rekayasa Perangkat Lunak.', NULL, 'ditindaklanjuti', '2026-11-08 17:03:33', '2026-11-08 17:03:33'),
(3, 'KLM-2026-0003', 7, 'Andi Pratama', '081234567892', 'Jam tangan G-Shock DW-5600 hitam original, ada stiker kecil hologram di balik caseback.', NULL, 'menunggu', '2026-11-08 17:03:33', '2026-11-08 17:03:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `handovers`
--

CREATE TABLE `handovers` (
  `handover_id` bigint(20) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `claim_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `recipient_name` varchar(100) NOT NULL,
  `recipient_contact` varchar(100) NOT NULL,
  `proof_photo_url` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `handover_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `handovers`
--

INSERT INTO `handovers` (`handover_id`, `report_id`, `claim_id`, `admin_id`, `recipient_name`, `recipient_contact`, `proof_photo_url`, `notes`, `handover_date`, `created_at`, `updated_at`) VALUES
(1, 9, NULL, 1, 'Andi P.', '081234567892', NULL, 'Barang diserahkan setelah pemilik berhasil membuka password laptop dan menunjukkan kartu identitas siswa.', '2026-10-28 14:30:00', '2026-11-08 17:03:33', '2026-11-08 17:03:33'),
(2, 10, NULL, 1, 'Siti N.', '081234567893', NULL, 'Dompet diserahkan langsung oleh penemu di pos satpam dan diterima oleh pemilik sah.', '2026-10-25 10:15:00', '2026-11-08 17:03:33', '2026-11-08 17:03:33'),
(3, 11, NULL, 1, 'Budi S.', '081234567897', NULL, 'Kunci diverifikasi dapat membuka loker 42 milik siswa Budi S.', '2026-10-22 13:00:00', '2026-11-08 17:03:33', '2026-11-08 17:03:33'),
(4, 12, NULL, 1, 'Maya R.', '081234567898', NULL, 'Tas ransel diambil oleh pemilik setelah diverifikasi isi tasnya.', '2026-10-20 16:00:00', '2026-11-08 17:03:33', '2026-11-08 17:03:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_11_08_091029_create_admins_table', 1),
(5, '2026_11_08_091030_create_reports_table', 1),
(6, '2026_11_08_091032_create_claims_table', 1),
(7, '2026_11_08_091033_create_handovers_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `reports`
--

CREATE TABLE `reports` (
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `edit_code` varchar(20) NOT NULL,
  `type` enum('hilang','ditemukan') NOT NULL,
  `item_name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `special_features` text DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `incident_date` date NOT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `reporter_name` varchar(100) NOT NULL,
  `reporter_phone` varchar(20) NOT NULL,
  `status` enum('proses_pencarian','siap_diambil','selesai') NOT NULL DEFAULT 'proses_pencarian',
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `reports`
--

INSERT INTO `reports` (`report_id`, `code`, `edit_code`, `type`, `item_name`, `description`, `special_features`, `location`, `incident_date`, `photo_url`, `reporter_name`, `reporter_phone`, `status`, `is_published`, `created_at`, `updated_at`) VALUES
(1, 'FL-2026-0042', 'ED-2026-0042', 'hilang', 'Dompet Kulit Coklat', 'Dompet kulit warna coklat tua merek Levi\'s berisi KTP, Kartu Pelajar, dan sedikit uang tunai. Terakhir terlihat saat makan siang di kantin.', 'Ada stiker kecil kucing di bagian belakang kartu pelajar dan resleting bagian koin agak macet.', 'Kantin Utama', '2026-11-02', NULL, 'Rina A.', '081234567890', 'proses_pencarian', 1, '2026-11-08 17:03:33', '2026-11-08 17:03:33'),
(2, 'FL-2026-0044', 'ED-2026-0044', 'hilang', 'Botol Minum Biru', 'Tumbler Tupperware warna biru tosca kapasitas 750ml tertinggal di bawah meja nomor 12.', 'Ada inisial nama DS di bagian bawah botol.', 'Lab Komputer 2', '2026-11-01', NULL, 'Dewi S.', '081234567891', 'proses_pencarian', 1, '2026-11-08 17:03:33', '2026-11-08 17:03:33'),
(3, 'FL-2026-0046', 'ED-2026-0046', 'hilang', 'Tas Ransel Hitam', 'Tas ransel hitam merek Eiger berisi buku pelajaran dan tempat pensil.', 'Gantungan kunci karakter anime Luffy di resleting depan.', 'Perpustakaan', '2026-10-30', NULL, 'Andi P.', '081234567892', 'siap_diambil', 1, '2026-11-08 17:03:33', '2026-11-08 17:03:33'),
(4, 'FL-2026-0050', 'ED-2026-0050', 'hilang', 'Helm Putih', 'Helm bogo warna putih polos ditaruh di spion motor Beat hitam.', 'Kaca helm agak tergores tipis di sebelah kanan.', 'Parkiran Motor', '2026-11-05', NULL, 'Siti N.', '081234567893', 'proses_pencarian', 1, '2026-11-08 17:03:33', '2026-11-08 17:03:33'),
(5, 'FL-2026-0043', 'ED-2026-0043', 'ditemukan', 'Kunci Motor Honda', 'Ditemukan di area parkir dan diamankan di Pos Satpam. Pemilik dapat mengajukan klaim dengan bukti kepemilikan.', 'Gantungan boneka beruang warna coklat dengan remote alarm kecil.', 'Parkiran Barat', '2026-11-03', NULL, 'Pak Budi (Satpam)', '081298765432', 'siap_diambil', 0, '2026-11-08 17:03:33', '2026-11-08 17:03:33'),
(6, 'FL-2026-0045', 'ED-2026-0045', 'ditemukan', 'Kartu Pelajar', 'Kartu Pelajar SMK Negeri 1 ditemukan di pinggir lapangan basket.', 'Atas nama siswi kelas XI RPL dengan foto latar biru.', 'Lapangan Basket', '2026-11-04', NULL, 'Satpam Pos 1', '081298765433', 'siap_diambil', 0, '2026-11-08 17:03:33', '2026-11-08 17:03:33'),
(7, 'FL-2026-0047', 'ED-2026-0047', 'ditemukan', 'Jam Tangan Hitam', 'Jam tangan digital sport warna hitam tali karet ditemukan di bangku koridor.', 'Merek Casio G-Shock, display waktu format 24 jam dengan baret halus di bezel.', 'Koridor Lantai 2', '2026-11-05', NULL, 'Siti N.', '081234567894', 'siap_diambil', 1, '2026-11-08 17:03:33', '2026-11-08 17:03:33'),
(8, 'FL-2026-0048', 'ED-2026-0048', 'ditemukan', 'Payung Biru', 'Payung lipat warna biru dongker basah tertinggal di tempat payung lobi.', 'Gagang kayu motif ukiran kecil.', 'Lobi Depan', '2026-11-04', NULL, 'Andi P.', '081234567895', 'siap_diambil', 1, '2026-11-08 17:03:33', '2026-11-08 17:03:33'),
(9, 'FL-2026-0031', 'ED-2026-0031', 'ditemukan', 'Laptop ASUS Hitam', 'Laptop ASUS VivoBook hitam tertinggal di ruang laboratorium.', 'Stiker logo GitHub dan Laravel di cover depan.', 'Lab RPL 1', '2026-10-27', NULL, 'Pak Budi', '081298765432', 'selesai', 0, '2026-11-08 17:03:33', '2026-11-08 17:03:33'),
(10, 'FL-2026-0027', 'ED-2026-0027', 'hilang', 'Dompet Hitam', 'Dompet lipat warna hitam terjatuh di koridor.', 'Ada foto keluarga di selipan mika.', 'Koridor Barat', '2026-10-24', NULL, 'Siti N.', '081234567893', 'selesai', 0, '2026-11-08 17:03:33', '2026-11-08 17:03:33'),
(11, 'FL-2026-0022', 'ED-2026-0022', 'ditemukan', 'Kunci Loker', 'Anak kunci nomor 42 ditemukan di depan loker kelas X.', 'Gantungan akrilik angka 42 warna merah.', 'Area Loker', '2026-10-21', NULL, 'Satpam Pos 2', '081298765434', 'selesai', 0, '2026-11-08 17:03:33', '2026-11-08 17:03:33'),
(12, 'FL-2026-0019', 'ED-2026-0019', 'hilang', 'Tas Ransel Biru', 'Tas ransel warna biru muda tertinggal di musholla.', 'Ada pin badge OSIS di saku samping.', 'Musholla Sekolah', '2026-10-19', NULL, 'Maya R.', '081234567898', 'selesai', 0, '2026-11-08 17:03:33', '2026-11-08 17:03:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('v1OViO2hqP2uQkehgbG9OkORHDa6oAf49ygo1GOJ', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJwbUFSbzFNV091SmU1MGlmenlTWEJvSWFld2xjN2ZZU05oNWNibUt5IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvc2VyYWgtdGVyaW1hIiwicm91dGUiOiJhZG1pbi5oYW5kb3Zlci5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsInBhc3N3b3JkX2hhc2hfYWRtaW4iOiJmZTA3MjZkMmI3MWQ4OTFkZjE3NmM4OTllYWU2ZmQ3OTQxZmE5Yjg3OTRjNDJlMGMxNTc1ZTlhMTM4YmYzNjI4In0=', 1794186023);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `claims`
--
ALTER TABLE `claims`
  ADD PRIMARY KEY (`claim_id`),
  ADD UNIQUE KEY `claims_claim_code_unique` (`claim_code`),
  ADD KEY `claims_report_id_foreign` (`report_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indeks untuk tabel `handovers`
--
ALTER TABLE `handovers`
  ADD PRIMARY KEY (`handover_id`),
  ADD KEY `handovers_report_id_foreign` (`report_id`),
  ADD KEY `handovers_claim_id_foreign` (`claim_id`),
  ADD KEY `handovers_admin_id_foreign` (`admin_id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD UNIQUE KEY `reports_code_unique` (`code`),
  ADD UNIQUE KEY `reports_edit_code_unique` (`edit_code`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `claims`
--
ALTER TABLE `claims`
  MODIFY `claim_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `handovers`
--
ALTER TABLE `handovers`
  MODIFY `handover_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `claims`
--
ALTER TABLE `claims`
  ADD CONSTRAINT `claims_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`report_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `handovers`
--
ALTER TABLE `handovers`
  ADD CONSTRAINT `handovers_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `handovers_claim_id_foreign` FOREIGN KEY (`claim_id`) REFERENCES `claims` (`claim_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `handovers_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`report_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
