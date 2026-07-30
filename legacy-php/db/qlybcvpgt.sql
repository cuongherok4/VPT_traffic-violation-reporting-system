-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 15, 2025 at 07:11 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qlybcvpgt`
--

-- --------------------------------------------------------

--
-- Table structure for table `bao_cao`
--

DROP TABLE IF EXISTS `bao_cao`;
CREATE TABLE IF NOT EXISTS `bao_cao` (
  `ma_bao_cao` int NOT NULL AUTO_INCREMENT,
  `bien_so_xe` varchar(255) NOT NULL,
  `dia_diem` varchar(255) NOT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `loai_vi_pham` varchar(255) NOT NULL,
  `mo_ta` varchar(500) NOT NULL,
  `ngay_gio_vi_pham` varchar(255) NOT NULL,
  `trang_thai` int DEFAULT '0',
  `tien_phat` decimal(65,0) NOT NULL,
  PRIMARY KEY (`ma_bao_cao`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bao_cao`
--

INSERT INTO `bao_cao` (`ma_bao_cao`, `bien_so_xe`, `dia_diem`, `hinh_anh`, `loai_vi_pham`, `mo_ta`, `ngay_gio_vi_pham`, `trang_thai`, `tien_phat`) VALUES
(53, '15D2-022.06', 'Hoan kiem , ha noi', 'admin.JPG', 'Vượt đèn đỏ', 'dsfsdf', '2025-03-16 16:51', 1, 30000),
(52, '15D2-022.06', 'Hoan kiem , ha noi', '276fc348-478c-4dc8-8727-213a00461aee.webp', 'Vượt đèn đỏ', 'dsfsdf', '2025-03-16 19:47', 1, 0),
(49, '16D1-792.54', 'Hoan kiem , ha noi', '0.1.jpeg', 'Vượt đèn đỏ', 'dsf', '2025-03-16 16:38', 1, 0),
(57, '16D1-792.54', 'Hoan kiem , ha noi', 'honda-wave-125i-1-17046125495541115765800.webp', 'Không đội mũ bảo hiểm', 'Óc chó ', '2025-03-16 12:05', 1, 0),
(55, '29D1-792.54', 'Hoan kiem , ha noi', 'photo-7-1675034960174792488453.webp', 'Vấn đề khác', 'Ngã Xe', '2025-03-16 20:19', 1, 0),
(59, '16D1-792.54', 'Hoan kiem , ha noi', 'important_devices_18dp_E8EAED_FILL0_wght400_GRAD0_opsz20.png', 'Đi ngược chiều', 'huynhdz', '2025-03-13 00:27', 2, 0),
(60, '18D1-792.54', 'Hoan kiem , ha noi', '0.1.jpeg', 'Không đội mũ bảo hiểm', 'dfsd', '2025-03-16 03:21', 1, 30000),
(61, '16D1-792.54', 'Hoan kiem , ha noi', '276fc348-478c-4dc8-8727-213a00461aee.webp', 'Vượt đèn đỏ', 'dsfsdf', '2025-03-12 00:43', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_don_hang`
--

DROP TABLE IF EXISTS `chi_tiet_don_hang`;
CREATE TABLE IF NOT EXISTS `chi_tiet_don_hang` (
  `ma_chi_tiet` int NOT NULL AUTO_INCREMENT,
  `ma_san_pham` int NOT NULL,
  `gia` decimal(65,0) NOT NULL,
  `so_luong` int NOT NULL,
  `ma_hoa_don` int NOT NULL,
  PRIMARY KEY (`ma_chi_tiet`),
  KEY `chi_tiet_don_hang_ibfk_1` (`ma_san_pham`),
  KEY `chi_tiet_don_hang_ibfk_2` (`ma_hoa_don`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chi_tiet_don_hang`
--

INSERT INTO `chi_tiet_don_hang` (`ma_chi_tiet`, `ma_san_pham`, `gia`, `so_luong`, `ma_hoa_don`) VALUES
(45, 27, 12000, 3, 17),
(46, 25, 12, 1, 17);

-- --------------------------------------------------------

--
-- Table structure for table `danh_muc_san_pham`
--

DROP TABLE IF EXISTS `danh_muc_san_pham`;
CREATE TABLE IF NOT EXISTS `danh_muc_san_pham` (
  `ma_danh_muc` int NOT NULL AUTO_INCREMENT,
  `ten_danh_muc` varchar(50) NOT NULL,
  PRIMARY KEY (`ma_danh_muc`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `danh_muc_san_pham`
--

INSERT INTO `danh_muc_san_pham` (`ma_danh_muc`, `ten_danh_muc`) VALUES
(1, 'mu'),
(2, 'ao'),
(3, 'baoHiem');

-- --------------------------------------------------------

--
-- Table structure for table `hoa_don`
--

DROP TABLE IF EXISTS `hoa_don`;
CREATE TABLE IF NOT EXISTS `hoa_don` (
  `ma_hoa_don` int NOT NULL AUTO_INCREMENT,
  `tong_tien` decimal(50,0) NOT NULL,
  `ngay_dat` datetime(6) NOT NULL,
  `ma_nguoi_dung` int NOT NULL,
  `phuong_thuc_thanh_toan` varchar(100) NOT NULL,
  PRIMARY KEY (`ma_hoa_don`),
  KEY `hoa_don_ibfk_1` (`ma_nguoi_dung`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hoa_don`
--

INSERT INTO `hoa_don` (`ma_hoa_don`, `tong_tien`, `ngay_dat`, `ma_nguoi_dung`, `phuong_thuc_thanh_toan`) VALUES
(9, 17335, '2025-03-14 23:16:03.000000', 2, 'chuyen_khoan'),
(17, 36012, '2025-03-15 12:26:53.000000', 3, 'chuyen_khoan');

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--

DROP TABLE IF EXISTS `nguoi_dung`;
CREATE TABLE IF NOT EXISTS `nguoi_dung` (
  `ma_nguoi_dung` int NOT NULL AUTO_INCREMENT,
  `mat_khau` varchar(50) NOT NULL,
  `tai_khoan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ma_quyen` int NOT NULL,
  `dia_chi` varchar(100) NOT NULL,
  PRIMARY KEY (`ma_nguoi_dung`),
  KEY `ma_quyen` (`ma_quyen`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`ma_nguoi_dung`, `mat_khau`, `tai_khoan`, `ma_quyen`, `dia_chi`) VALUES
(1, '12', 'huynhtv', 1, ''),
(2, '1', 'hungnv', 2, ''),
(3, '1', 'trinhvtv', 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `phan_quyen`
--

DROP TABLE IF EXISTS `phan_quyen`;
CREATE TABLE IF NOT EXISTS `phan_quyen` (
  `ma_quyen` int NOT NULL AUTO_INCREMENT,
  `ten_quyen` varchar(100) NOT NULL,
  PRIMARY KEY (`ma_quyen`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `phan_quyen`
--

INSERT INTO `phan_quyen` (`ma_quyen`, `ten_quyen`) VALUES
(1, 'Quản Trị Viên'),
(2, 'Người Dùng ');

-- --------------------------------------------------------

--
-- Table structure for table `san_pham`
--

DROP TABLE IF EXISTS `san_pham`;
CREATE TABLE IF NOT EXISTS `san_pham` (
  `ma_san_pham` int NOT NULL AUTO_INCREMENT,
  `ten_sp` varchar(50) NOT NULL,
  `so_luong` int NOT NULL,
  `mo_ta` varchar(100) NOT NULL,
  `gia_ban` double NOT NULL,
  `ma_danh_muc` int NOT NULL,
  `hinh_anh` text NOT NULL,
  PRIMARY KEY (`ma_san_pham`),
  KEY `san_pham_ibfk_1` (`ma_danh_muc`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `san_pham`
--

INSERT INTO `san_pham` (`ma_san_pham`, `ten_sp`, `so_luong`, `mo_ta`, `gia_ban`, `ma_danh_muc`, `hinh_anh`) VALUES
(25, 'ao', 5, 'hahaha', 12, 2, '1741975219_454-48-sh125i-160i.png'),
(27, 'Mũ', 56, 'hahaha', 12000, 2, '1742013858_Huynh.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tin_tuc`
--

DROP TABLE IF EXISTS `tin_tuc`;
CREATE TABLE IF NOT EXISTS `tin_tuc` (
  `ma_tin_tuc` int NOT NULL AUTO_INCREMENT,
  `ten_tin_tuc` varchar(100) NOT NULL,
  `noi_dung` varchar(100) NOT NULL,
  `hinh_anh` varchar(100) NOT NULL,
  `ngay_dang` datetime(6) NOT NULL,
  PRIMARY KEY (`ma_tin_tuc`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tin_tuc`
--

INSERT INTO `tin_tuc` (`ma_tin_tuc`, `ten_tin_tuc`, `noi_dung`, `hinh_anh`, `ngay_dang`) VALUES
(1, 'sdf', 'sdfsd', 'sdfsd', '0000-00-00 00:00:00.000000'),
(2, 'Tin sốt', 'dsfsdf', 'admin.JPG', '2025-03-16 17:42:00.000000'),
(3, 'Tin sốt', 'sadasd', 'pngtree-cartoon-paper-airplane-png-download-image_1196802.jpg', '2025-03-16 20:51:00.000000');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_1` FOREIGN KEY (`ma_san_pham`) REFERENCES `san_pham` (`ma_san_pham`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_2` FOREIGN KEY (`ma_hoa_don`) REFERENCES `hoa_don` (`ma_hoa_don`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD CONSTRAINT `hoa_don_ibfk_1` FOREIGN KEY (`ma_nguoi_dung`) REFERENCES `nguoi_dung` (`ma_nguoi_dung`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD CONSTRAINT `nguoi_dung_ibfk_1` FOREIGN KEY (`ma_quyen`) REFERENCES `phan_quyen` (`ma_quyen`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `san_pham_ibfk_1` FOREIGN KEY (`ma_danh_muc`) REFERENCES `danh_muc_san_pham` (`ma_danh_muc`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
