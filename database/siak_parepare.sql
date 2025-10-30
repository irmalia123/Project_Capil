-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2025 at 05:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siak_parepare`
--

-- --------------------------------------------------------

--
-- Table structure for table `akta_kelahiran`
--

CREATE TABLE `akta_kelahiran` (
  `id` int(11) NOT NULL,
  `nik_anak` varchar(20) NOT NULL,
  `nama_anak` varchar(100) NOT NULL,
  `tempat_lahir_anak` varchar(100) NOT NULL,
  `tanggal_lahir_anak` date NOT NULL,
  `jenis_kelamin_anak` enum('L','P') NOT NULL,
  `nik_ayah` varchar(20) NOT NULL,
  `nama_ayah` varchar(100) NOT NULL,
  `nik_ibu` varchar(20) NOT NULL,
  `nama_ibu` varchar(100) NOT NULL,
  `tanggal_lapor` date NOT NULL,
  `no_akta_kelahiran` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `akta_kelahiran`
--

INSERT INTO `akta_kelahiran` (`id`, `nik_anak`, `nama_anak`, `tempat_lahir_anak`, `tanggal_lahir_anak`, `jenis_kelamin_anak`, `nik_ayah`, `nama_ayah`, `nik_ibu`, `nama_ibu`, `tanggal_lapor`, `no_akta_kelahiran`, `created_at`) VALUES
(1, '1234', 'jsbcjsc', 'scbshc', '2024-12-20', 'L', '7227667253', 'sjcusg', '2737', 'sjds', '2024-12-26', '71325', '2024-11-30 18:09:42');

-- --------------------------------------------------------

--
-- Table structure for table `data_penduduk`
--

CREATE TABLE `data_penduduk` (
  `nik` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `agama` varchar(50) NOT NULL,
  `pendidikan` varchar(50) NOT NULL,
  `golongan_darah` varchar(3) DEFAULT NULL,
  `status_warga` enum('Aktif','Tidak Aktif') NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_penduduk`
--

INSERT INTO `data_penduduk` (`nik`, `nama`, `alamat`, `tempat_lahir`, `tanggal_lahir`, `agama`, `pendidikan`, `golongan_darah`, `status_warga`, `jenis_kelamin`) VALUES
('1234567890123457', 'Jane Smith', 'Jl. Contoh No. 2', 'Bandung', '1992-02-02', 'Kristen', 'SMA', 'B', 'Aktif', 'P'),
('1234567890123458', 'Ali Ahmad', 'Jl. Contoh No. 3', 'Surabaya', '1988-03-03', 'Islam', 'D3', 'O', 'Aktif', 'L'),
('1234567890123459', 'Siti Fatimah', 'Jl. Contoh No. 4', 'Medan', '1995-04-04', 'Islam', 'S1', 'AB', 'Aktif', 'P');

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text DEFAULT NULL,
  `untuk_admin` tinyint(1) DEFAULT 0,
  `dibaca` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pencatatan_perceraian`
--

CREATE TABLE `pencatatan_perceraian` (
  `id` int(11) NOT NULL,
  `nik_suami` varchar(20) NOT NULL,
  `kk_suami` varchar(20) NOT NULL,
  `paspor_suami` varchar(20) DEFAULT NULL,
  `nama_suami` varchar(100) NOT NULL,
  `tempat_lahir_suami` varchar(100) NOT NULL,
  `tanggal_lahir_suami` date NOT NULL,
  `alamat_suami` text NOT NULL,
  `pendidikan_terakhir_suami` varchar(50) NOT NULL,
  `agama_suami` varchar(50) NOT NULL,
  `pekerjaan_suami` varchar(50) NOT NULL,
  `perceraian_ke_suami` int(11) NOT NULL,
  `kewarganegaraan_suami` varchar(50) NOT NULL,
  `nik_istri` varchar(20) NOT NULL,
  `kk_istri` varchar(20) NOT NULL,
  `paspor_istri` varchar(20) DEFAULT NULL,
  `nama_istri` varchar(100) NOT NULL,
  `tempat_lahir_istri` varchar(100) NOT NULL,
  `tanggal_lahir_istri` date NOT NULL,
  `alamat_istri` text NOT NULL,
  `pendidikan_terakhir_istri` varchar(50) NOT NULL,
  `agama_istri` varchar(50) NOT NULL,
  `pekerjaan_istri` varchar(50) NOT NULL,
  `kewarganegaraan_istri` varchar(50) NOT NULL,
  `pengaju_perceraian` enum('suami','istri') NOT NULL,
  `nomor_akta_perkawinan` varchar(50) NOT NULL,
  `tempat_pencatatan_perkawinan` varchar(100) NOT NULL,
  `nomor_putusan_pengadilan` varchar(50) NOT NULL,
  `tanggal_keputusan_pengadilan` date NOT NULL,
  `nama_peradilan` varchar(100) NOT NULL,
  `nama_lembaga_putusan` varchar(100) NOT NULL,
  `sebab_perceraian` text NOT NULL,
  `tanggal_lapor` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pencatatan_perceraian`
--

INSERT INTO `pencatatan_perceraian` (`id`, `nik_suami`, `kk_suami`, `paspor_suami`, `nama_suami`, `tempat_lahir_suami`, `tanggal_lahir_suami`, `alamat_suami`, `pendidikan_terakhir_suami`, `agama_suami`, `pekerjaan_suami`, `perceraian_ke_suami`, `kewarganegaraan_suami`, `nik_istri`, `kk_istri`, `paspor_istri`, `nama_istri`, `tempat_lahir_istri`, `tanggal_lahir_istri`, `alamat_istri`, `pendidikan_terakhir_istri`, `agama_istri`, `pekerjaan_istri`, `kewarganegaraan_istri`, `pengaju_perceraian`, `nomor_akta_perkawinan`, `tempat_pencatatan_perkawinan`, `nomor_putusan_pengadilan`, `tanggal_keputusan_pengadilan`, `nama_peradilan`, `nama_lembaga_putusan`, `sebab_perceraian`, `tanggal_lapor`, `created_at`) VALUES
(1, '1234567890123456', '123456789012345', 'A123456789', 'John Doe', 'Jakarta', '1990-01-01', 'Jl. Contoh No. 1', 'S1', 'Islam', 'Karyawan', 1, 'WNI', '1234567890123457', '123456789012346', 'B987654321', 'Jane Smith', 'Bandung', '1992-02-02', 'Jl. Contoh No. 2', 'SMA', 'Kristen', 'Ibu Rumah Tangga', 'WNI', 'suami', 'AK123456', 'Kota Jakarta', 'PUT123456', '2024-01-01', 'Pengadilan Negeri Jakarta', 'Lembaga Pengadilan', 'Alasan perceraian', '2024-01-02', '2024-11-30 17:50:16');

-- --------------------------------------------------------

--
-- Table structure for table `pencatatan_perkawinan`
--

CREATE TABLE `pencatatan_perkawinan` (
  `id` int(11) NOT NULL,
  `nik_suami` varchar(20) NOT NULL,
  `kk_suami` varchar(20) NOT NULL,
  `paspor_suami` varchar(20) DEFAULT NULL,
  `nama_suami` varchar(100) NOT NULL,
  `tempat_lahir_suami` varchar(100) NOT NULL,
  `tanggal_lahir_suami` date NOT NULL,
  `alamat_suami` text NOT NULL,
  `pendidikan_terakhir_suami` varchar(50) NOT NULL,
  `agama_suami` varchar(50) NOT NULL,
  `pekerjaan_suami` varchar(50) NOT NULL,
  `anak_ke_suami` int(11) NOT NULL,
  `status_perkawinan_suami` varchar(50) NOT NULL,
  `perkawinan_ke_suami` int(11) NOT NULL,
  `istri_ke_suami` int(11) DEFAULT NULL,
  `kewarganegaraan_suami` varchar(50) NOT NULL,
  `kebangsaan_suami` varchar(50) DEFAULT NULL,
  `nik_istri` varchar(20) NOT NULL,
  `kk_istri` varchar(20) NOT NULL,
  `paspor_istri` varchar(20) DEFAULT NULL,
  `nama_istri` varchar(100) NOT NULL,
  `tempat_lahir_istri` varchar(100) NOT NULL,
  `tanggal_lahir_istri` date NOT NULL,
  `alamat_istri` text NOT NULL,
  `pendidikan_terakhir_istri` varchar(50) NOT NULL,
  `agama_istri` varchar(50) NOT NULL,
  `pekerjaan_istri` varchar(50) NOT NULL,
  `anak_ke_istri` int(11) NOT NULL,
  `status_perkawinan_istri` varchar(50) NOT NULL,
  `perkawinan_ke_istri` int(11) NOT NULL,
  `kewarganegaraan_istri` varchar(50) NOT NULL,
  `kebangsaan_istri` varchar(50) DEFAULT NULL,
  `nik_ayah_istri` varchar(20) NOT NULL,
  `nama_ayah_istri` varchar(100) NOT NULL,
  `agama_ayah_istri` varchar(50) NOT NULL,
  `tempat_lahir_ayah_istri` varchar(100) NOT NULL,
  `tanggal_lahir_ayah_istri` date NOT NULL,
  `alamat_ayah_istri` text NOT NULL,
  `pekerjaan_ayah_istri` varchar(50) NOT NULL,
  `nik_ibu_istri` varchar(20) NOT NULL,
  `nama_ibu_istri` varchar(100) NOT NULL,
  `agama_ibu_istri` varchar(50) NOT NULL,
  `tempat_lahir_ibu_istri` varchar(100) NOT NULL,
  `tanggal_lahir_ibu_istri` date NOT NULL,
  `alamat_ibu_istri` text NOT NULL,
  `pekerjaan_ibu_istri` varchar(50) NOT NULL,
  `nik_saksi1` varchar(20) NOT NULL,
  `nama_saksi1` varchar(100) NOT NULL,
  `agama_saksi1` varchar(50) NOT NULL,
  `tempat_lahir_saksi1` varchar(100) NOT NULL,
  `alamat_saksi1` text NOT NULL,
  `pekerjaan_saksi1` varchar(50) NOT NULL,
  `nik_saksi2` varchar(20) NOT NULL,
  `nama_saksi2` varchar(100) NOT NULL,
  `agama_saksi2` varchar(50) NOT NULL,
  `tempat_lahir_saksi2` varchar(100) NOT NULL,
  `alamat_saksi2` text NOT NULL,
  `pekerjaan_saksi2` varchar(50) NOT NULL,
  `tanggal_pemberkatan` date NOT NULL,
  `tanggal_lapor` date NOT NULL,
  `pukul` time NOT NULL,
  `agama_perkawinan` varchar(50) NOT NULL,
  `nama_badan_peradilan` varchar(100) NOT NULL,
  `nomor_putusan` varchar(50) NOT NULL,
  `tanggal_putusan` date NOT NULL,
  `nama_pemuka_agama` varchar(100) NOT NULL,
  `ijin_perwakilan_wna` varchar(50) DEFAULT NULL,
  `jumlah_anak` int(11) NOT NULL,
  `nama_anak` varchar(100) NOT NULL,
  `no_akta_kelahiran` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pencatatan_perkawinan`
--

INSERT INTO `pencatatan_perkawinan` (`id`, `nik_suami`, `kk_suami`, `paspor_suami`, `nama_suami`, `tempat_lahir_suami`, `tanggal_lahir_suami`, `alamat_suami`, `pendidikan_terakhir_suami`, `agama_suami`, `pekerjaan_suami`, `anak_ke_suami`, `status_perkawinan_suami`, `perkawinan_ke_suami`, `istri_ke_suami`, `kewarganegaraan_suami`, `kebangsaan_suami`, `nik_istri`, `kk_istri`, `paspor_istri`, `nama_istri`, `tempat_lahir_istri`, `tanggal_lahir_istri`, `alamat_istri`, `pendidikan_terakhir_istri`, `agama_istri`, `pekerjaan_istri`, `anak_ke_istri`, `status_perkawinan_istri`, `perkawinan_ke_istri`, `kewarganegaraan_istri`, `kebangsaan_istri`, `nik_ayah_istri`, `nama_ayah_istri`, `agama_ayah_istri`, `tempat_lahir_ayah_istri`, `tanggal_lahir_ayah_istri`, `alamat_ayah_istri`, `pekerjaan_ayah_istri`, `nik_ibu_istri`, `nama_ibu_istri`, `agama_ibu_istri`, `tempat_lahir_ibu_istri`, `tanggal_lahir_ibu_istri`, `alamat_ibu_istri`, `pekerjaan_ibu_istri`, `nik_saksi1`, `nama_saksi1`, `agama_saksi1`, `tempat_lahir_saksi1`, `alamat_saksi1`, `pekerjaan_saksi1`, `nik_saksi2`, `nama_saksi2`, `agama_saksi2`, `tempat_lahir_saksi2`, `alamat_saksi2`, `pekerjaan_saksi2`, `tanggal_pemberkatan`, `tanggal_lapor`, `pukul`, `agama_perkawinan`, `nama_badan_peradilan`, `nomor_putusan`, `tanggal_putusan`, `nama_pemuka_agama`, `ijin_perwakilan_wna`, `jumlah_anak`, `nama_anak`, `no_akta_kelahiran`, `created_at`) VALUES
(1, '22', '33', '432', 'havdhd', 'skhdus', '2024-12-25', 'jdsd', 'gy', 'sd', 'wd', 1, 'dfd', 1, 1, 'df', 'dfd', '11', '1142', '232', 'dfd', 'df', '2024-12-26', 'fvf', 'fvf', 'fbf', 'fvf', 3, 'fv', 1, 'r', 'r', '44', 'f', 'f', 'f', '2024-12-12', 'yy', 'j', '555', 'i', 'uyu', 'gf', '2022-10-17', 'td', 'fd', '6454', 'gfg', 'h', 'ttre', 'pinrang', 'tft', '45', 'hgft', 'tft', 'ggtft', 'gfyfy', 'gf', '2024-12-24', '2024-12-10', '02:40:00', 'ryyy', 'tr', 'rdse', '2024-12-18', 'eawe', 'dd', 2, 'ft', '2', '2024-12-01 02:37:00');

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_kk`
--

CREATE TABLE `penerbitan_kk` (
  `id` int(11) NOT NULL,
  `nomor_kk` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `rt` varchar(10) NOT NULL,
  `rw` varchar(10) NOT NULL,
  `kelurahan` varchar(100) NOT NULL,
  `kecamatan` varchar(100) NOT NULL,
  `kota` varchar(100) NOT NULL,
  `provinsi` varchar(100) NOT NULL,
  `tanggal_penerbitan` date NOT NULL,
  `nik_kepala_keluarga` varchar(20) NOT NULL,
  `nama_kepala_keluarga` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penerbitan_kk`
--

INSERT INTO `penerbitan_kk` (`id`, `nomor_kk`, `alamat`, `rt`, `rw`, `kelurahan`, `kecamatan`, `kota`, `provinsi`, `tanggal_penerbitan`, `nik_kepala_keluarga`, `nama_kepala_keluarga`, `created_at`) VALUES
(1, '2321', 'pinrang', '01', '02', 'lampa', 'Belawa', 'parepare', 'Sulawesi Selatan', '2024-12-17', '6545', 'gdfs', '2024-12-01 02:37:40');

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_ktp`
--

CREATE TABLE `penerbitan_ktp` (
  `id` int(11) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `alamat` text NOT NULL,
  `agama` varchar(50) NOT NULL,
  `pekerjaan` varchar(50) NOT NULL,
  `kewarganegaraan` varchar(50) NOT NULL,
  `tanggal_penerbitan` date NOT NULL,
  `nomor_ktp` varchar(50) NOT NULL,
  `foto_3x4` varchar(255) NOT NULL,
  `foto_tanda_tangan` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penerbitan_ktp`
--

INSERT INTO `penerbitan_ktp` (`id`, `nik`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `alamat`, `agama`, `pekerjaan`, `kewarganegaraan`, `tanggal_penerbitan`, `nomor_ktp`, `foto_3x4`, `foto_tanda_tangan`, `created_at`) VALUES
(1, '7313725492642348', 'Rezky', 'Belawa', '2024-12-25', 'L', 'pinrang', 'islam', 'Mahasiswa', 'Indonesia', '2024-12-26', '122', 'uploads/WhatsApp Image 2024-05-05 at 22.54.54_f4bb4436.jpg', 'uploads/WhatsApp Image 2024-11-07 at 15.32.31_8f27880c.jpg', '2024-12-01 02:15:08');

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan`
--

CREATE TABLE `pengajuan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama_pemohon` varchar(100) NOT NULL,
  `jenis_pengajuan` varchar(100) NOT NULL,
  `status` enum('menunggu','diproses','disetujui','ditolak') DEFAULT 'menunggu',
  `tanggal_pengajuan` timestamp NOT NULL DEFAULT current_timestamp(),
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `persyaratan`
--

CREATE TABLE `persyaratan` (
  `id` int(11) NOT NULL,
  `nama_persyaratan` varchar(255) NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `tombol_teks` varchar(100) NOT NULL,
  `tombol_link` varchar(255) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `urutan` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `created_at`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', '', '2024-11-30 17:40:15'),
(2, 'arif', '827ccb0eea8a706c4c34a16891f84e7b', '', '2024-11-30 18:08:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akta_kelahiran`
--
ALTER TABLE `akta_kelahiran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_penduduk`
--
ALTER TABLE `data_penduduk`
  ADD PRIMARY KEY (`nik`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pencatatan_perceraian`
--
ALTER TABLE `pencatatan_perceraian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pencatatan_perkawinan`
--
ALTER TABLE `pencatatan_perkawinan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penerbitan_kk`
--
ALTER TABLE `penerbitan_kk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penerbitan_ktp`
--
ALTER TABLE `penerbitan_ktp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `persyaratan`
--
ALTER TABLE `persyaratan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akta_kelahiran`
--
ALTER TABLE `akta_kelahiran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pencatatan_perceraian`
--
ALTER TABLE `pencatatan_perceraian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pencatatan_perkawinan`
--
ALTER TABLE `pencatatan_perkawinan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `penerbitan_kk`
--
ALTER TABLE `penerbitan_kk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `penerbitan_ktp`
--
ALTER TABLE `penerbitan_ktp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengajuan`
--
ALTER TABLE `pengajuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `persyaratan`
--
ALTER TABLE `persyaratan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD CONSTRAINT `pengajuan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
