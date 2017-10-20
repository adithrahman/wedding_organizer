-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 20, 2017 at 11:18 PM
-- Server version: 10.0.32-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sibershi_wo`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` mediumint(9) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(64) NOT NULL,
  `pass` varchar(128) NOT NULL,
  `salt` varchar(10) NOT NULL,
  `created` date NOT NULL,
  `role` enum('SUPER USER','ADMINISTRATOR') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `pass`, `salt`, `created`, `role`) VALUES
(1, 'admin', 'admin@admin.com', 'MDM5ODgyZjEzNGJmY2NjN2YyMTQ1YTQyOTMxOTcwYTc3ZDcwODE1NmM0YzRmZmM0M2RlM2QxMjIzYjgwYmU2ZDZiZjk0ZDE0M2U=', '6bf94d143e', '2017-03-20', 'SUPER USER');

-- --------------------------------------------------------

--
-- Table structure for table `fasilitas`
--

CREATE TABLE `fasilitas` (
  `paket_id` varchar(8) NOT NULL,
  `type` enum('CATERING','RIAS','HIBURAN','DEKORASI','HOTEL','DOKUMENTASI','TRANSPORTASI','GEDUNG','UNDANGAN','SOUVENIR','JASA-LAIN') NOT NULL,
  `item` varchar(255) NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `fasilitas`
--

INSERT INTO `fasilitas` (`paket_id`, `type`, `item`, `total`) VALUES
('58059a69', 'CATERING', 'Ayam', 500),
('58059a69', 'CATERING', 'Sate Ayam Lontong', 500),
('58059a69', 'DEKORASI', 'Pelaminan', 1),
('feeda4e2', 'RIAS', 'Makeup pager betis', 3),
('58059a69', 'HIBURAN', 'Jazz band', 1),
('de019091', 'HOTEL', '1', 5000),
('de019091', 'CATERING', 'pisang', 2),
('618b3281', 'CATERING', 'Nasi Ayam', 100),
('618b3281', 'CATERING', 'Sayur Oyong', 200),
('0addd92c', 'CATERING', 'Nasi', 250),
('0addd92c', 'CATERING', 'Sayur', 250),
('0addd92c', 'CATERING', 'Daging', 250),
('0addd92c', 'CATERING', 'Ayam', 250),
('8f48ed22', 'CATERING', 'Ayam', 300),
('8f48ed22', 'CATERING', 'Nasi', 300),
('8f48ed22', 'CATERING', 'Daging', 300),
('0addd92c', 'RIAS', 'Pengantin dan Keluarga', 10),
('0addd92c', 'HIBURAN', 'Rebana atau Nasyid (Group)', 1),
('0addd92c', 'DEKORASI', 'Panggung, Tenda, Kamar Pengantin (1 paket)', 3),
('0addd92c', 'HOTEL', 'Ramayana, Natama, (PerMalam)', 2),
('0addd92c', 'DOKUMENTASI', 'Album Poto (Buku)', 2),
('0addd92c', 'TRANSPORTASI', 'Mobil Pengantin dan Rombongan', 4),
('0addd92c', 'GEDUNG', 'Al - Azhar', 1),
('0addd92c', 'UNDANGAN', 'Pria dan Wanita', 2000),
('0addd92c', 'SOUVENIR', 'Buku Zikir Pagi&amp;Petang/Gelas/Bingkai Poto', 2500);

-- --------------------------------------------------------

--
-- Table structure for table `klien`
--

CREATE TABLE `klien` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `gender` enum('PRIA','WANITA') NOT NULL,
  `email` varchar(64) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `address` varchar(255) NOT NULL,
  `pass` varchar(128) NOT NULL,
  `salt` varchar(10) NOT NULL,
  `created` date NOT NULL,
  `confirmed` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `klien`
--

INSERT INTO `klien` (`id`, `name`, `gender`, `email`, `phone`, `address`, `pass`, `salt`, `created`, `confirmed`) VALUES
(7, 'Adith Aulia Rahmax', 'PRIA', 'adith@mail.com', '734543', 'Jakartaxxx', 'MDM5ODgyZjEzNGJmY2NjN2YyMTQ1YTQyOTMxOTcwYTc3ZDcwODE1NmM0YzRmZmM0M2RlM2QxMjIzYjgwYmU2ZDZiZjk0ZDE0M2U=', '6bf94d143e', '2017-03-20', 0),
(8, 'sasa', 'WANITA', 'sasa@mail.com', '554252', 'dfgr', 'YjdjMTE2ZDM1ZDVmNTI4MTBlNTI3MTU2ZTJmMzViN2VhZjAwYjRmMmRmM2Y1ZDNlODE0ODYzM2Q1ZWVmMWMyNjg5YzhiNThlYmY=', '89c8b58ebf', '2017-04-09', 0),
(27, 'Adith Aulia Rahman', 'PRIA', 'aural4bs@gmail.com', '076788733', 'Jakarte', 'OWQzNjcwMDBhYjViZGI4NDk1ZDY3YzMzZmMxZGFmZjkzNzE2MTE1MTAzY2E0YTVhNjA2MWJhMjUxZGRjYjc5YjFkYjg5YmFlN2Q=', '1db89bae7d', '2017-04-16', 1),
(31, 'Adith Rahman', 'PRIA', 'adithrahman@gmail.com', '02342423', 'Serang', 'MjQyMmUyMjZmMzEwODAxOTY4M2E5MTllNTQzNzBhZDRmNDIyYmI3YWMwYmRjZTQyYWJiZmRhOGQ0NmEyZGMyMGI5ZDBkNmNkMDg=', 'b9d0d6cd08', '2017-05-21', 1),
(32, 'Asal', 'PRIA', 'asal@mail.com', '76879798', 'Jakarta', 'MzFkNDkyYmY0NDJjNjQyYWJmOTBjYWFlMjQ0NzRlMmUxODRkZmNmMjVhOWY3ODMzOGQyOTE4ZTM3MTFlODg3NTM2MTA4ZjYzZDI=', '36108f63d2', '2017-07-18', 0),
(33, 'oggi', 'PRIA', 'oggipermana107lt@gmail.com', '081212094421', 'jl pondok randu', 'MTc1ZTU0ZWI3M2U0MjhlZDE3OGMwOWIxY2Q4MGNjYmZjZjQ3ZTRjM2ExYTFlNTA3NzRkMmM2NWQ5YTViNWY1ZWZkYjgxODkzNTM=', 'fdb8189353', '2017-07-18', 0),
(34, 'oggi permana', 'PRIA', 'oggi1131118@sttpln.ac.id', '081212094421', 'jln. Pondok Randu', 'ZGI1ODVhOGU5NDQ1MWE0YjUzZjRiMTgzNjQ3MTA5N2FhOTg4MjA3NzRkZjAzNzYwN2YyOTllM2Q1N2NkODA0YzI1NDE3ZWFlNjQ=', '25417eae64', '2017-07-27', 0),
(35, 'burik', 'PRIA', 'burik@mail.com', '03942938', 'asasda', 'YTUyYjA3YTliOGFhYWZmMzliMGQ0YjE3Nzg4NTM3M2NmZWMxZThkODkzM2U2M2M1YmQyMTc3ZTI4MWZjMTI4ZDhjZTU3ZGI2MTM=', '8ce57db613', '2017-07-30', 0),
(36, 'Akbar', 'PRIA', 'mansyurriadi3@gmail.com', '085299254233', 'cengkareng', 'MDEwZTViMGU2YzMzOTAyMWIxMDI2NDVmMzFhYTU4ODY5NzYyMTQ5MDQ4NTk1YzljZmQyNDhiYzQ5YWRkZDAxMTM5YjQ0MjBlMTU=', '39b4420e15', '2017-08-05', 0),
(37, 'Anjar', 'PRIA', 'anjarjundullah@gmail.com', '08999282500', 'kotabumi, tangerang', 'Nzk1ZGJlMTQ4MTM0MWI4ODZkMTdmOWEyNmM2M2Y2YTRiMDU4NjM0ODg5ZGUzNzUzZGM0MmRlYzI2NzU1NzYzYzg3NjNjYzU4NDI=', '8763cc5842', '2017-08-08', 0),
(38, 'Rifqi Fildzahdri', 'PRIA', '1volt1ampere@gmail.com', '08999189268', 'Jl. Manggis No.2 Pekalongan', 'YjQ1Yjk4YmUzNTY2YTJhZmY2YjM0ZDU3ZThmZWJmNWUxNWNlYzNkZWMzZjVkMzQwNjU0YTM5MmJkZTE2MmU0MjE1MWFiZTA1MGI=', '151abe050b', '2017-08-08', 0),
(39, 'Wahyu Suldiansyah', 'PRIA', 'wahyu.suldiansyah@gmail.com', '081343448240', 'BTN Timurama Blok A8 No 16', 'ZTVhYTE3ZTNhYjBiZWRlNDUyYjU0NTQ1ZWY0NTZkY2QwY2I4MDRlMWFkYmY4NmIyNjY3ZGFjNDFhZTUzMzJjNmI1ZTYyZTU2MWM=', 'b5e62e561c', '2017-08-08', 0);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_from` varchar(64) NOT NULL,
  `name_from` varchar(64) NOT NULL,
  `user_to` varchar(64) NOT NULL,
  `name_to` varchar(64) NOT NULL,
  `tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_from`, `name_from`, `user_to`, `name_to`, `tstamp`, `message`) VALUES
(3, 'adith@mail.com', 'Adith Aulia Rahman', 'adith@mail.com', 'AURA', '2017-04-06 16:41:45', 'Say hai dari User[Adith] untuk WO[AURA]'),
(4, 'adith@mail.com', 'AURA', 'adith@mail.com', 'Adith Aulia Rahman', '2017-04-06 16:55:03', 'Say hallo, dari WO[AURA] untuk User[Adith]'),
(5, 'adith@mail.com', 'Adith Aulia Rahman', 'adith@mail.com', 'AURA', '2017-04-06 17:16:06', 'lol test'),
(6, 'adith@mail.com', 'Adith Aulia Rahman', 'adith@mail.com', 'AURA', '2017-04-06 17:17:58', 'holaxxx'),
(7, 'adith@mail.com', 'AURA', 'adith@mail.com', 'Adith Aulia Rahman', '2017-04-06 20:53:19', 'blah blah'),
(8, 'adith@mail.com', 'Adith Aulia Rahmax', 'adith@mail.com', 'AURA', '2017-04-08 07:43:30', 'bla bla'),
(9, 'admin', 'Administrator', 'adith@mail.com', 'AURA', '2017-04-09 20:40:05', 'test chat dari Admin ke AURA'),
(14, 'admin', 'Administrator', 'adith@mail.com', 'AURA', '2017-04-09 20:54:37', 'test chat ahh..'),
(15, 'admin', 'Administrator', 'adith@mail.com', 'AURA', '2017-04-09 20:55:11', 'coba lagi ahh'),
(16, 'admin', 'Administrator', 'adith@mail.com', 'AURA', '2017-04-09 21:00:43', 'coba terus..'),
(17, 'admin', 'Administrator', 'adith@mail.com', 'AURA', '2017-04-09 21:02:46', 'berhasilkah ?'),
(18, 'admin', 'Administrator', 'adith@mail.com', 'AURA', '2017-04-09 21:04:44', 'mungkinkah ?'),
(19, 'admin', 'Administrator', 'adith@mail.com', 'AURA', '2017-04-09 21:05:13', 'apakah bisa ?'),
(20, 'admin', 'Administrator', 'adith@mail.com', 'AURA', '2017-04-09 21:17:50', 'apa ajah...'),
(21, 'admin', 'Administrator', 'adith@mail.com', 'AURA', '2017-04-09 21:20:32', 'tau dah.'),
(22, 'admin', 'Administrator', 'adith@mail.com', 'AURA', '2017-04-09 21:23:12', 'bolo'),
(24, '127.0.0.1', 'Guest [127.0.0.1]', 'admin', 'Administrator', '2017-04-10 14:58:28', 'halo ke admin dari guest localhost'),
(25, 'adith@mail.com', 'Adith Aulia Rahmax', 'admin', 'Administrator', '2017-04-10 15:08:48', 'Halo admin dari klien adith aulia rahman'),
(26, '112.215.170.215', 'Guest [112.215.170.215]', 'admin', 'Administrator', '2017-05-04 08:58:55', 'bisa cara login gimna'),
(27, '112.215.152.190', 'Guest [112.215.152.190]', 'admin', 'Administrator', '2017-05-23 18:02:27', 'tes'),
(28, 'oggi1131118@sttpln.ac.id', 'oggi permana', 'adith@mail.com', 'AURA', '2017-07-28 06:53:58', 'assalamualaykum'),
(29, '115.124.74.94', 'Guest [115.124.74.94]', 'admin', 'Administrator', '2017-07-30 08:28:24', 'lol'),
(30, 'adith@mail.com', 'Adith Aulia Rahmax', 'adith@mail.com', 'AURA', '2017-07-30 09:44:21', 'test pesan lagi'),
(31, 'adith@mail.com', 'Adith Aulia Rahmax', 'adith@mail.com', 'AURA', '2017-07-30 09:46:15', 'test pesan lagi'),
(32, 'adith@mail.com', 'Adith Aulia Rahmax', 'adith@mail.com', 'AURA', '2017-07-30 09:46:35', 'ini pesan saya lho..'),
(33, '223.255.230.21', 'Guest [223.255.230.21]', 'admin', 'Administrator', '2017-08-05 14:11:24', 'cariin jodoh dong'),
(34, 'oggi1131118@sttpln.ac.id', '107 Organizer', 'oggi1131118@sttpln.ac.id', 'oggi permana', '2017-08-05 14:15:55', 'thanks you'),
(35, 'mansyurriadi3@gmail.com', 'Akbar', 'oggi1131118@sttpln.ac.id', '107 Organizer', '2017-08-05 14:30:17', 'bebas lg'),
(36, 'mansyurriadi3@gmail.com', 'Akbar', 'oggi1131118@sttpln.ac.id', '107 Organizer', '2017-08-05 14:30:17', 'bebas lg'),
(37, 'oggi1131118@sttpln.ac.id', '107 Organizer', 'mansyurriadi3@gmail.com', 'Akbar', '2017-08-10 03:52:45', 'haloo guys'),
(38, 'oggi1131118@sttpln.ac.id', '107 Organizer', 'oggi1131118@sttpln.ac.id', 'oggi permana', '2017-08-10 03:54:09', 'gimana perkembanganya'),
(39, 'oggi1131118@sttpln.ac.id', 'oggi permana', 'oggi1131118@sttpln.ac.id', '107 Organizer', '2017-08-10 03:54:44', 'alhamdulillah udah sudah mulai berjalan'),
(40, 'oggi1131118@sttpln.ac.id', 'oggi permana', 'admin', 'Administrator', '2017-08-10 03:57:56', 'halo'),
(41, 'oggi1131118@sttpln.ac.id', '107 Organizer', 'admin', 'Administrator', '2017-08-10 03:58:05', 'haloo'),
(42, 'oggi1131118@sttpln.ac.id', '107 Organizer', 'oggi1131118@sttpln.ac.id', 'oggi permana', '2017-08-14 14:38:36', 'bismillah'),
(43, 'oggi1131118@sttpln.ac.id', 'oggi permana', 'admin', 'Administrator', '2017-08-14 14:49:11', 'bismillah');

-- --------------------------------------------------------

--
-- Table structure for table `paket`
--

CREATE TABLE `paket` (
  `id` varchar(8) NOT NULL,
  `wo_id` mediumint(9) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `capacity` mediumint(9) NOT NULL,
  `created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `paket`
--

INSERT INTO `paket` (`id`, `wo_id`, `name`, `price`, `capacity`, `created`) VALUES
('0addd92c', 34, 'Paket A', 2500000, 250, '2017-07-28'),
('130dd865', 14, 'waser', 123, 321, '2017-04-03'),
('58059a69', 14, 'Satu', 1000000000, 1000, '0000-00-00'),
('618b3281', 33, 'Murah A', 13500, 100, '2017-07-18'),
('8f48ed22', 34, 'Paket B', 3000000, 300, '2017-07-28'),
('de019091', 14, 'blah', 765, 567, '2017-04-08'),
('feeda4e2', 14, 'qwert', 123, 123, '2017-04-03');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` varchar(8) NOT NULL,
  `klien_id` int(11) NOT NULL,
  `wo_id` mediumint(9) NOT NULL,
  `paket_id` varchar(8) NOT NULL,
  `status` enum('APPROVE','NO ACTION','DENIED','PENDING','COMPLETE') NOT NULL,
  `user_payment` enum('LUNAS','BELUM BAYAR') NOT NULL,
  `wo_payment` enum('LUNAS','SEPARUH','PENDING') NOT NULL,
  `pay_name` varchar(255) DEFAULT NULL,
  `date_order` date NOT NULL,
  `date_complete` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `klien_id`, `wo_id`, `paket_id`, `status`, `user_payment`, `wo_payment`, `pay_name`, `date_order`, `date_complete`) VALUES
('1bbc8f21', 34, 34, '0addd92c', 'DENIED', 'BELUM BAYAR', 'PENDING', '1bbc8f21.jpg', '2017-08-14', '0000-00-00'),
('5b4f7aa3', 32, 14, '130dd865', 'NO ACTION', 'BELUM BAYAR', 'PENDING', '5b4f7aa3.jpg', '2017-08-12', '0000-00-00'),
('70ae17d6', 36, 34, '0addd92c', 'COMPLETE', 'BELUM BAYAR', 'PENDING', '70ae17d6.jpg', '2017-08-05', '0000-00-00'),
('76ce60f9', 7, 14, '130dd865', 'NO ACTION', 'BELUM BAYAR', 'PENDING', '76ce60f9.jpg', '2017-07-30', '0000-00-00'),
('ada8bf67', 32, 14, 'de019091', 'NO ACTION', 'BELUM BAYAR', 'PENDING', '', '2017-08-12', '0000-00-00'),
('e53de666', 32, 14, 'feeda4e2', 'NO ACTION', 'BELUM BAYAR', 'PENDING', '', '2017-08-12', '0000-00-00'),
('ebc12c89', 32, 14, '58059a69', 'NO ACTION', 'BELUM BAYAR', 'PENDING', '', '2017-08-12', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `wo`
--

CREATE TABLE `wo` (
  `id` mediumint(9) NOT NULL,
  `name` varchar(64) NOT NULL,
  `owner` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `address` varchar(255) NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `pass` varchar(128) NOT NULL,
  `salt` varchar(10) NOT NULL,
  `created` date NOT NULL,
  `approved` tinyint(1) DEFAULT NULL,
  `approved_by` mediumint(9) DEFAULT NULL,
  `approved_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wo`
--

INSERT INTO `wo` (`id`, `name`, `owner`, `email`, `phone`, `address`, `deskripsi`, `pass`, `salt`, `created`, `approved`, `approved_by`, `approved_date`) VALUES
(14, 'AURA', 'Adith Aulia Rahman', 'adith@mail.com', '000000', 'Jakarta', 'AURA Wedding Organizer asdasdasd', 'MDM5ODgyZjEzNGJmY2NjN2YyMTQ1YTQyOTMxOTcwYTc3ZDcwODE1NmM0YzRmZmM0M2RlM2QxMjIzYjgwYmU2ZDZiZjk0ZDE0M2U=', '6bf94d143e', '2017-03-20', 0, 1, '0000-00-00'),
(32, 'Merah Putih', 'Soekarno', 'merdeka@mail.com', '3453434', 'Jakarta', 'Wedding Organizer anak bangsa', 'N2FkNGUwNzJlMTI4MGRjZmIwODkxNDViMjZhODY0MTc0ZDFkNDhmZWZjMjRmZTg2ZmM4Yzk2YTNmM2UwNDg0ZDYzZjJhNTVkOGM=', '63f2a55d8c', '2017-03-29', 0, 1, '0000-00-00'),
(33, 'najwa', 'oggi', 'oggipermana107lt@gmail.com', '081212094421', 'jln pondok randu', '', 'OTBlZjE3YTRhZDU3NDNmMDI3MjZmYzNjYTk5ZTFmMmMyNjgxOTc3MDYyOGIxMDJiNmMyZWQwNmZmNDgzMTFjYmNmYTdkZjNlZWI=', 'cfa7df3eeb', '2017-07-18', 0, 1, NULL),
(34, '107 Organizer', 'oggi permana', 'oggi1131118@sttpln.ac.id', '081212094421', 'jln. Pondok Randu', '', 'YmE5NzFjZGUxZDUwOWY0ODA3N2EwMDc2YzczMzA3NzA0NWZlYWU3OTJiYmQ1YWIwN2YxYjE1ZGRiOWU0MGU0YzAwMzkzNDJlNWY=', '0039342e5f', '2017-07-28', 0, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fasilitas`
--
ALTER TABLE `fasilitas`
  ADD KEY `pckg_index` (`paket_id`);

--
-- Indexes for table `klien`
--
ALTER TABLE `klien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mail` (`email`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paket_wo_id_index` (`wo_id`) USING BTREE;

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paket_index` (`klien_id`,`wo_id`,`paket_id`) USING BTREE,
  ADD KEY `wo_id` (`wo_id`),
  ADD KEY `package_id` (`paket_id`);

--
-- Indexes for table `wo`
--
ALTER TABLE `wo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wo_index` (`approved_by`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `klien`
--
ALTER TABLE `klien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `wo`
--
ALTER TABLE `wo`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `fasilitas`
--
ALTER TABLE `fasilitas`
  ADD CONSTRAINT `fasilitas_ibfk_1` FOREIGN KEY (`paket_id`) REFERENCES `paket` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `paket`
--
ALTER TABLE `paket`
  ADD CONSTRAINT `paket_ibfk_1` FOREIGN KEY (`wo_id`) REFERENCES `wo` (`id`);

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`klien_id`) REFERENCES `klien` (`id`),
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`wo_id`) REFERENCES `wo` (`id`),
  ADD CONSTRAINT `pesanan_ibfk_3` FOREIGN KEY (`paket_id`) REFERENCES `paket` (`id`);

--
-- Constraints for table `wo`
--
ALTER TABLE `wo`
  ADD CONSTRAINT `wo_ibfk_1` FOREIGN KEY (`approved_by`) REFERENCES `admin` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
