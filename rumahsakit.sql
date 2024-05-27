-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2022 at 06:41 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rumahsakit`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_accesstoken`
--

CREATE TABLE `tb_accesstoken` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(100) NOT NULL,
  `login_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `expiration_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_accesstoken`
--

INSERT INTO `tb_accesstoken` (`id`, `user_id`, `token`, `login_date`, `expiration_date`) VALUES
(17, 2, '6379728dadb500.866811261668903565', '2022-11-19 18:19:25', '2022-11-22 18:19:25'),
(22, 6, '637c6dd7173f75.293293161669098967', '2022-11-22 00:36:07', '2022-11-25 00:36:07');

-- --------------------------------------------------------

--
-- Table structure for table `tb_dokter`
--

CREATE TABLE `tb_dokter` (
  `id` int(11) NOT NULL,
  `nama_dokter` varchar(80) NOT NULL,
  `spesialis` varchar(50) NOT NULL,
  `alamat` text NOT NULL,
  `no_telp` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_dokter`
--

INSERT INTO `tb_dokter` (`id`, `nama_dokter`, `spesialis`, `alamat`, `no_telp`) VALUES
(49, 'Edwin Handlai berubah', 'Mata', 'Jln Podomoro Gg Sukadamai No 31', '089694636303'),
(50, 'David Gunawan berubah', 'Bedah', 'Jln Sukamulya no 10', '0812312322'),
(51, 'Mohammed Alek', 'Hewan', 'Jln Panggangrang no 63', '081231233212'),
(53, 'Agus Susanto', 'Mata', 'Jln naga sakti no 78', '0812367812'),
(54, 'Hendri Yanton', 'Gigi', 'Jln random gg sukarandom no 100', '0898787667'),
(55, 'Mawati', 'Kandungan', 'Jln sukiamad no 192 ', '0876567898'),
(56, 'Dr. Sukianto', 'THT', 'Jln mallma Komplek Agung setyo No 89', '0812367823'),
(57, 'Ahmad Salim', 'Umum', 'Jln guna sakti gg 123', '0871627382'),
(58, 'Wiko', 'Mata', 'Jln ciptosal gg umah 13', '0867584923'),
(59, 'Yorushi ka', 'Bedah', 'Jln yanian no 67', '0875643823'),
(60, 'Saprio Putra', 'Kulit', 'Jln yansi komplek agung pratama no 78', '0812343792'),
(61, 'Halim Sucipto', 'Bedah', 'Jln matahari terbenam no 7123', '0817483232');

-- --------------------------------------------------------

--
-- Table structure for table `tb_obat`
--

CREATE TABLE `tb_obat` (
  `id` int(11) NOT NULL,
  `nama_obat` varchar(200) NOT NULL,
  `ket_obat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_obat`
--

INSERT INTO `tb_obat` (`id`, `nama_obat`, `ket_obat`) VALUES
(20, 'Paracetamol', 'Obat mencegah sakit kepala'),
(21, 'Dumin', 'Obat mencegah sakit kepala dan demam'),
(22, 'Betadine', 'Obat memperbaiki dan menyembuhkan luka koyak maupun lecet pada tubuh '),
(23, 'Lycoxy', 'Suplemen Kesehatan'),
(24, 'Eflagen', 'Obat coated');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pasien`
--

CREATE TABLE `tb_pasien` (
  `id` int(11) NOT NULL,
  `nomor_identitas` varchar(30) NOT NULL,
  `nama_pasien` varchar(80) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `alamat` text NOT NULL,
  `no_telp` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_pasien`
--

INSERT INTO `tb_pasien` (`id`, `nomor_identitas`, `nama_pasien`, `jenis_kelamin`, `alamat`, `no_telp`) VALUES
(9, '129', 'Edwin Hendlai', 'L', 'Jln randomaja gg random no 123', '089694637372'),
(10, '126', 'David', 'L', 'Jln Uhmad Yani 78', '0865749298'),
(11, '897', 'Melinda ', 'P', 'Jln Yuniawa gg hales no 123', '0866378492'),
(12, '756', 'Setiawan Gunawan', 'L', 'Jln Kanjiana Sale no 89', '0876890838'),
(15, '765', 'Huga Guna', 'L', 'Jln sukadamai no 87', '08263748392'),
(16, '657', 'Reymond', 'L', 'Jln bunati no 75', '0892939232'),
(17, '653', 'Siska Tila', 'P', 'Jln pinia no 45', '08264728382');

-- --------------------------------------------------------

--
-- Table structure for table `tb_poliklinik`
--

CREATE TABLE `tb_poliklinik` (
  `id` int(11) NOT NULL,
  `nama_poliklinik` varchar(50) NOT NULL,
  `gedung` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_poliklinik`
--

INSERT INTO `tb_poliklinik` (`id`, `nama_poliklinik`, `gedung`) VALUES
(6, 'Gedung Pancaraya Jaya', 'Gedung 1'),
(7, 'Kamsiamama', 'Gedung 2'),
(8, 'Hijasku Hijau', 'Gedung 3'),
(9, 'ApelBiru Merah', 'Gedung 4');

-- --------------------------------------------------------

--
-- Table structure for table `tb_rekammedis`
--

CREATE TABLE `tb_rekammedis` (
  `id` int(10) NOT NULL,
  `id_pasien` int(10) NOT NULL,
  `keluhan` varchar(255) NOT NULL,
  `id_dokter` int(10) NOT NULL,
  `diagnosa` text NOT NULL,
  `id_poliklinik` int(10) NOT NULL,
  `tgl_periksa` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_rekammedis`
--

INSERT INTO `tb_rekammedis` (`id`, `id_pasien`, `keluhan`, `id_dokter`, `diagnosa`, `id_poliklinik`, `tgl_periksa`) VALUES
(25, 10, 'Sakit kepala', 53, 'Kebanyakan menghabiskan waktu di depan layar, kurang istirahat', 7, '2022-11-24'),
(26, 11, 'Mudah lelah', 59, 'Terlalu keras bekerja', 6, '2022-11-18'),
(27, 12, 'Sakit kepala', 49, 'Kebanyakan menghabiskan waktu di depan layar, kurang istirahat', 7, '2022-11-26'),
(28, 15, 'Sakit kepala', 53, 'Kebanyakan menghabiskan waktu di depan layar, kurang istirahat', 8, '2022-11-24'),
(29, 16, 'Mudah lelah', 58, 'Tidak tidur', 9, '2022-11-26'),
(30, 17, 'Lapar', 53, 'Tidak makan, ke rm sana.', 7, '2022-11-11'),
(31, 11, 'Sakit kepala', 49, 'Kebanyakan menghabiskan waktu di depan layar, kurang istirahat', 8, '2022-11-16');

-- --------------------------------------------------------

--
-- Table structure for table `tb_rekammedis_obat`
--

CREATE TABLE `tb_rekammedis_obat` (
  `id` int(10) NOT NULL,
  `id_rekammedis` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_rekammedis_obat`
--

INSERT INTO `tb_rekammedis_obat` (`id`, `id_rekammedis`, `id_obat`) VALUES
(79, 25, 20),
(80, 25, 21),
(81, 26, 23),
(82, 27, 20),
(83, 27, 21),
(84, 27, 23),
(85, 28, 20),
(86, 28, 21),
(87, 28, 23),
(88, 29, 22),
(89, 30, 23),
(90, 31, 20),
(91, 31, 21),
(92, 31, 23);

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int(11) NOT NULL,
  `nama` varchar(80) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(500) NOT NULL,
  `level` varchar(20) NOT NULL,
  `verification_code` int(6) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id`, `nama`, `email`, `password`, `level`, `verification_code`, `email_verified_at`) VALUES
(19, 'Edwin', 'edwinhendly17@gmail.com', '$2y$10$4kyw3A78jigbsrlt7WHUuur4h35h27t2c9bGSOAha/wyg1y4cYzDq', 'Admin', 218392, '2022-11-23 19:29:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_accesstoken`
--
ALTER TABLE `tb_accesstoken`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_dokter`
--
ALTER TABLE `tb_dokter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_obat`
--
ALTER TABLE `tb_obat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_pasien`
--
ALTER TABLE `tb_pasien`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_poliklinik`
--
ALTER TABLE `tb_poliklinik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_rekammedis`
--
ALTER TABLE `tb_rekammedis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pasien` (`id_pasien`),
  ADD KEY `id_dokter` (`id_dokter`),
  ADD KEY `id_poliklinik` (`id_poliklinik`);

--
-- Indexes for table `tb_rekammedis_obat`
--
ALTER TABLE `tb_rekammedis_obat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_rekammedis` (`id_rekammedis`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_accesstoken`
--
ALTER TABLE `tb_accesstoken`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tb_dokter`
--
ALTER TABLE `tb_dokter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `tb_obat`
--
ALTER TABLE `tb_obat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tb_pasien`
--
ALTER TABLE `tb_pasien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tb_poliklinik`
--
ALTER TABLE `tb_poliklinik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tb_rekammedis`
--
ALTER TABLE `tb_rekammedis`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `tb_rekammedis_obat`
--
ALTER TABLE `tb_rekammedis_obat`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_rekammedis`
--
ALTER TABLE `tb_rekammedis`
  ADD CONSTRAINT `tb_rekammedis_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `tb_pasien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_rekammedis_ibfk_2` FOREIGN KEY (`id_dokter`) REFERENCES `tb_dokter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_rekammedis_ibfk_3` FOREIGN KEY (`id_poliklinik`) REFERENCES `tb_poliklinik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_rekammedis_obat`
--
ALTER TABLE `tb_rekammedis_obat`
  ADD CONSTRAINT `tb_rekammedis_obat_ibfk_1` FOREIGN KEY (`id_rekammedis`) REFERENCES `tb_rekammedis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_rekammedis_obat_ibfk_2` FOREIGN KEY (`id_obat`) REFERENCES `tb_obat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
