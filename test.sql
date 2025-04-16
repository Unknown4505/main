-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2025 at 02:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `idsp` int(11) NOT NULL,
  `idKH` int(11) NOT NULL,
  `idcart` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ctdonhang`
--

CREATE TABLE `ctdonhang` (
  `idCTDH` int(11) NOT NULL,
  `iddonhang` int(11) DEFAULT NULL,
  `idsp` int(11) DEFAULT NULL,
  `soluong` int(11) DEFAULT NULL CHECK (`soluong` > 0),
  `giathanh` decimal(10,2) DEFAULT NULL CHECK (`giathanh` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ctdonhang`
--

INSERT INTO `ctdonhang` (`idCTDH`, `iddonhang`, `idsp`, `soluong`, `giathanh`) VALUES
(4, 3, 18, 2, 3000000.00),
(5, 3, 14, 1, 3000000.00),
(6, 3, 13, 1, 3000000.00),
(7, 3, 10, 1, 3000000.00),
(8, 3, 12, 1, 3000000.00),
(9, 4, 16, 1, 3000000.00),
(10, 4, 2, 1, 3000000.00),
(11, 5, 15, 1, 3000000.00),
(12, 6, 3, 1, 3000000.00),
(13, 6, 2, 1, 3000000.00),
(14, 6, 6, 1, 3000000.00),
(15, 6, 5, 1, 3000000.00),
(16, 6, 4, 1, 3000000.00),
(17, 7, 6, 1, 3000000.00),
(18, 7, 16, 1, 3000000.00),
(19, 8, 17, 1, 3000000.00),
(20, 9, 17, 1, 3000000.00),
(21, 9, 11, 1, 3000000.00),
(22, 9, 9, 1, 3000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `donhang`
--

CREATE TABLE `donhang` (
  `iddonhang` int(11) NOT NULL,
  `idKH` int(11) NOT NULL,
  `ngaymua` date DEFAULT NULL,
  `tongtien` decimal(10,2) DEFAULT NULL CHECK (`tongtien` >= 0),
  `trangthai` varchar(50) DEFAULT 'Chưa xác nhận',
  `diachi` text DEFAULT NULL,
  `sdt` varchar(15) DEFAULT NULL,
  `ghichu` text DEFAULT NULL,
  `phuongthucthanhtoan` varchar(50) NOT NULL DEFAULT 'Chưa xác định',
  `ngaycapnhat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donhang`
--

INSERT INTO `donhang` (`iddonhang`, `idKH`, `ngaymua`, `tongtien`, `trangthai`, `diachi`, `sdt`, `ghichu`, `phuongthucthanhtoan`, `ngaycapnhat`) VALUES
(3, 10, '2025-04-10', 18000000.00, 'Hoàn thành', '46 to ki', '0567432789', '', 'Tiền mặt', '2025-04-10 07:34:16'),
(4, 11, '2025-04-10', 6000000.00, 'Hoàn thành', '47 to ki', '0765893648', '', 'Tiền mặt', '2025-04-10 07:34:18'),
(5, 13, '2025-04-10', 3000000.00, 'Hoàn thành', '48 to ki', '0367859590', '', 'Tiền mặt', '2025-04-10 07:34:20'),
(6, 14, '2025-04-10', 15000000.00, 'Hoàn thành', '49 to ki', '039878672', '', 'Tiền mặt', '2025-04-10 07:34:22'),
(7, 15, '2025-04-10', 6000000.00, 'Hoàn thành', '50 to ki', '036677898', '', 'Tiền mặt', '2025-04-10 07:34:24'),
(8, 18, '2025-04-10', 3000000.00, 'Hoàn thành', '53 to ki', '0834528378', '', 'Tiền mặt', '2025-04-10 07:34:25'),
(9, 18, '2025-04-10', 9000000.00, 'Hoàn thành', '53 to ki', '0834528378', '', 'Tiền mặt', '2025-04-10 07:34:27');

-- --------------------------------------------------------

--
-- Table structure for table `kh`
--

CREATE TABLE `kh` (
  `idKH` int(11) NOT NULL,
  `diachi` varchar(255) DEFAULT NULL,
  `sdt` varchar(15) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `tenkh` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ngaydangky` date DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kh`
--

INSERT INTO `kh` (`idKH`, `diachi`, `sdt`, `email`, `dob`, `tenkh`, `password`, `ngaydangky`, `status`) VALUES
(1, '123 Đường ABC, Quận 1, TP.HCM', '0123456789', 'nguyenvana@example.com', '1990-05-10', 'Nguyễn Văn A', '', '2025-04-04', 0),
(2, '456 Đường XYZ, Quận 3, TP.HCM', '0987654321', 'tranthib@example.com', '1995-07-15', 'Trần Thị B', '', '2025-04-04', 1),
(3, '789 Đường DEF, Quận 5, TP.HCM', '0345678901', 'lethic@example.com', '1992-09-20', 'Lê Thị C', '', '2025-04-04', 1),
(9, '46 to ki', '0898246134', 'lehuukhang147@gmail.com', '2005-12-12', 'Lê Hữu Chí', '$2y$10$.XRmbf8.zoDmtulL3cRSoOSUDQJcui68eZUkDMJBEInNGSVMeH6iG', '2025-04-04', 1),
(10, '46 to ki', '0567432789', 'Chi1@gmail.com', '2011-11-11', 'LMC1', '$2y$10$HyCYYhnYlh7UvyNcKSIEpeIYecSO0ote0QIQBaEfp9qT7xHbF8gj.', '2025-04-04', 1),
(11, '47 to ki', '0765893648', 'Chi2@gmail.com', '1111-11-11', 'LCM2', '$2y$10$85BS2wzDlSBuBDyMzvpdvuzXuroQo3si0RRRiDc0.G.Sha0WBPp7W', '2025-04-04', 1),
(13, '48 to ki', '0367859590', 'Chi3@gmail.com', '1111-11-11', 'LCM3', '$2y$10$QF3G7aDnUu.LhkLZ9IejNuLtwgBs7PRdb1Nsu296yn3aD8aYd4sf.', '2025-04-04', 1),
(14, '49 to ki', '039878672', 'Chi4@gmail.com', '1990-10-10', 'LCM4', '$2y$10$YkJrz3zzK2sCMQqW9ObbdulQ7Ao5Rvpd9GhFi/hwFumPp7DK8OWGG', '2025-04-04', 1),
(15, '50 to ki', '036677898', 'Chi5@gmail.com', '2003-09-09', 'LCM5', '$2y$10$DKZafSRyJufhZ/uLI30NAeElNTa.SXMOjQa4VP8oixSJpoiGsz9Ai', '2025-04-04', 1),
(16, '51 to ki', '0982734246', 'Chi6@gmail.com', '2004-11-12', 'LCM6', '$2y$10$U62Qx233N4fHFLKsxia3L.fKzafyyduVbOZMkxPx1Boy0ia8xPxJq', '2025-04-04', 1),
(17, '52 to ki ', '091231231', 'Chi7@gmail.com', '2006-06-06', 'LCM7', '$2y$10$VXu43Oq5f8UdAiJUJGa/zuoIMGsam2y0apAbTJ2yprAyK36IMXZN.', '2025-04-04', 1),
(18, '53 to ki', '0834528378', 'Chi8@gmail.com', '2020-11-09', 'LMC8', '$2y$10$R35x1gg4z2Ee9dsKqjMGXuXPo4YfwBYPpv8UnUVV/kvvIKRP9e0lO', '2025-04-04', 1),
(19, '54 to ki', '0923823233', 'Chi9@gmail.com', '2002-11-11', 'LMC9', '$2y$10$svsKpBB9/wHmUOqLyN6JjOHyOIkzfj7P7TLUTazd9hqQQWRs7Wj92', '2025-04-10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `loaisp`
--

CREATE TABLE `loaisp` (
  `idloai` int(11) NOT NULL,
  `tenloai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loaisp`
--

INSERT INTO `loaisp` (`idloai`, `tenloai`) VALUES
(1, 'Nike'),
(2, 'Adidas'),
(3, 'Vans');

-- --------------------------------------------------------

--
-- Table structure for table `sp`
--

CREATE TABLE `sp` (
  `idsp` int(11) NOT NULL,
  `idloai` int(11) DEFAULT NULL,
  `images` longblob DEFAULT NULL,
  `tensp` varchar(255) NOT NULL,
  `giathanh` int(11) NOT NULL,
  `soluong` int(11) NOT NULL,
  `mota` varchar(1000) NOT NULL,
  `ansp` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sp`
--

INSERT INTO `sp` (`idsp`, `idloai`, `images`, `tensp`, `giathanh`, `soluong`, `mota`, `ansp`) VALUES
(1, 2, 0x75706c6f6164732f676961792d6164696461732d73616d62612d6f672d373574682d616e6e69766572736172792d7061636b2d626c61636b2d6c696b652d617574682e6a7067, 'Adidas Sampa 75th Anniversary', 3000000, 98, 'Nâng niu bàn chân Việt', 0),
(2, 2, 0x75706c6f6164732f476961792d4164696461732d5375706572737461722d4c6162656c2d436f6c6c6167652d426573742d5175616c6974792e6a7067, 'Adidas SuperStar Label', 3000000, 92, 'Nâng niu bàn chân Việt', 0),
(3, 2, 0x75706c6f6164732f4164696461732d466f72756d2d38342d4c6f772d57686974652d426c61636b2d383030783635302e6a7067, 'Adidas Forum', 3000000, 94, 'Nâng niu bàn chân Việt', 0),
(4, 2, 0x75706c6f6164732f676961792d6164696461732d616c7068616d61676d612d77686974652d383030783635302e6a7067, 'Adidas Alphamagma', 3000000, 98, 'Nâng niu bàn chân Việt', 0),
(5, 2, 0x75706c6f6164732f676961792d756c747261626f6f73742d32322d636f72652d626c61636b2d383030783635302e6a7067, 'Adidas Ultraboots', 3000000, 98, 'Nâng niu bàn chân Việt', 0),
(6, 2, 0x75706c6f6164732f676961792d756c747261626f6f73742d32322d77686974652d383030783635302e6a7067, 'Adidas Ultraboots White', 3000000, 96, 'Nâng niu bàn chân Việt', 0),
(7, 1, 0x75706c6f6164732f4149522b4d41582b444e38202831292e706e67, 'Nike Air Max DN8', 3000000, 96, 'Bên nhau đến trọn đường', 0),
(8, 1, 0x75706c6f6164732f4149522b4d41582b444e382e706e67, 'Nike Air Blue DN8', 3000000, 98, 'Bên nhau đến trọn đường', 0),
(9, 3, 0x75706c6f6164732f657a6769662d37633261646232656330653961622e706e67, 'Van Classic Blue', 3000000, 98, 'Bên nhau đến trọn đường', 0),
(10, 3, 0x75706c6f6164732f657a6769662d37636632343836613932366330362e706e67, 'Classic Vans Black 1990', 3000000, 92, 'Chọn Vans chọn cuộc sống của bạn', 0),
(11, 1, 0x75706c6f6164732f4e494b452b53484f582b544c2e706e67, 'Nike Air Black', 3000000, 98, 'Bên nhau đến trọn đường', 0),
(12, 3, 0x75706c6f6164732f657a6769662d37653364646531383264623137342e706e67, 'Classic All White', 3000000, 98, 'Chọn Vans chọn cuộc sống của bạn', 0),
(13, 1, 0x75706c6f6164732f574d4e532b4149522b4a4f5244414e2b312b4d49442e706e67, 'Nike Air BlueGem', 3000000, 96, 'Bên nhau đến trọn đường', 0),
(14, 3, 0x75706c6f6164732f657a6769662d37323962363138353438616234362e706e67, 'Sampa', 3000000, 92, 'Chọn Vans chọn cuộc đời của bạn', 0),
(15, 1, 0x75706c6f6164732f4149522b4a4f5244414e2b312b4d49442b53452e706e67, 'Air Jordan MID SE', 3000000, 98, 'Bên nhau đến trọn đường', 0),
(16, 3, 0x75706c6f6164732f657a6769662d37313837303038663162316331662e706e67, 'Vans White Blue Classic', 3000000, 92, 'Chọn Vans chọn cuộc sống của bạn', 0),
(17, 1, 0x75706c6f6164732f574d4e532b4149522b4a4f5244414e2b312b4c4f572b53452e706e67, 'WWSM Nike Mid', 3000000, 92, 'Bên nhau đến trọn đường', 0),
(18, 3, 0x75706c6f6164732f657a6769662d37313965333634313932626333362e706e67, 'Low Black Classic Sampa', 3000000, 94, 'Chọn Vans chọn cuộc sống của bạn', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`idcart`),
  ADD KEY `fk_cart_sp` (`idsp`),
  ADD KEY `fk_cart_kh` (`idKH`);

--
-- Indexes for table `ctdonhang`
--
ALTER TABLE `ctdonhang`
  ADD PRIMARY KEY (`idCTDH`),
  ADD KEY `iddonhang` (`iddonhang`),
  ADD KEY `idsp` (`idsp`);

--
-- Indexes for table `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`iddonhang`),
  ADD KEY `idKH` (`idKH`);

--
-- Indexes for table `kh`
--
ALTER TABLE `kh`
  ADD PRIMARY KEY (`idKH`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD UNIQUE KEY `sdt` (`sdt`);

--
-- Indexes for table `loaisp`
--
ALTER TABLE `loaisp`
  ADD PRIMARY KEY (`idloai`);

--
-- Indexes for table `sp`
--
ALTER TABLE `sp`
  ADD PRIMARY KEY (`idsp`),
  ADD KEY `idloai` (`idloai`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `idcart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `ctdonhang`
--
ALTER TABLE `ctdonhang`
  MODIFY `idCTDH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `donhang`
--
ALTER TABLE `donhang`
  MODIFY `iddonhang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `kh`
--
ALTER TABLE `kh`
  MODIFY `idKH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `loaisp`
--
ALTER TABLE `loaisp`
  MODIFY `idloai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sp`
--
ALTER TABLE `sp`
  MODIFY `idsp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ctdonhang`
--
ALTER TABLE `ctdonhang`
  ADD CONSTRAINT `ctdonhang_ibfk_1` FOREIGN KEY (`iddonhang`) REFERENCES `donhang` (`iddonhang`),
  ADD CONSTRAINT `ctdonhang_ibfk_2` FOREIGN KEY (`idsp`) REFERENCES `sp` (`idsp`);

--
-- Constraints for table `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`idKH`) REFERENCES `kh` (`idKH`);

--
-- Constraints for table `sp`
--
ALTER TABLE `sp`
  ADD CONSTRAINT `sp_ibfk_1` FOREIGN KEY (`idloai`) REFERENCES `loaisp` (`idloai`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
