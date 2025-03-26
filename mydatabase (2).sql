-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 26, 2025 lúc 01:43 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `mydatabase`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ctdonhang`
--

CREATE TABLE `ctdonhang` (
  `idCTDH` int(11) NOT NULL,
  `iddonhang` int(11) DEFAULT NULL,
  `idsp` int(11) DEFAULT NULL,
  `soluong` int(11) DEFAULT NULL CHECK (`soluong` > 0),
  `giathanh` decimal(10,2) DEFAULT NULL CHECK (`giathanh` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ctdonhang`
--

INSERT INTO `ctdonhang` (`idCTDH`, `iddonhang`, `idsp`, `soluong`, `giathanh`) VALUES
(1, 1, 1, 2, 99999999.99),
(2, 1, 2, 1, 21414141.00),
(3, 2, 2, 3, 21414141.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donhang`
--

CREATE TABLE `donhang` (
  `iddonhang` int(11) NOT NULL,
  `idKH` int(11) NOT NULL,
  `ngaymua` date DEFAULT NULL,
  `tongtien` decimal(10,2) DEFAULT NULL CHECK (`tongtien` >= 0),
  `trangthai` varchar(50) DEFAULT 'Đã xác nhận',
  `diachi` text DEFAULT NULL,
  `sdt` varchar(15) DEFAULT NULL,
  `ghichu` text DEFAULT NULL,
  `phuongthucthanhtoan` varchar(50) NOT NULL DEFAULT 'Chưa xác định',
  `ngaycapnhat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `donhang`
--

INSERT INTO `donhang` (`iddonhang`, `idKH`, `ngaymua`, `tongtien`, `trangthai`, `diachi`, `sdt`, `ghichu`, `phuongthucthanhtoan`, `ngaycapnhat`) VALUES
(1, 1, '2024-03-17', 5000000.00, 'Đã xác nhận', '123 Đường ABC, Quận 1, TP.HCM', '0123456789', 'Giao trước 12h', 'Thanh toán khi nhận hàng', '2025-03-26 12:39:01'),
(2, 2, '2024-03-16', 10000000.00, 'Hoàn thành', '456 Đường XYZ, Quận 3, TP.HCM', '0987654321', '', 'Chuyển khoản', '2025-03-17 08:27:09');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `kh`
--

CREATE TABLE `kh` (
  `idKH` int(11) NOT NULL,
  `diachi` varchar(255) DEFAULT NULL,
  `sdt` varchar(15) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `tenkh` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `kh`
--

INSERT INTO `kh` (`idKH`, `diachi`, `sdt`, `email`, `dob`, `tenkh`) VALUES
(1, '123 Đường ABC, Quận 1, TP.HCM', '0123456789', 'nguyenvana@example.com', '1990-05-10', 'Nguyễn Văn A'),
(2, '456 Đường XYZ, Quận 3, TP.HCM', '0987654321', 'tranthib@example.com', '1995-07-15', 'Trần Thị B'),
(3, '789 Đường DEF, Quận 5, TP.HCM', '0345678901', 'lethic@example.com', '1992-09-20', 'Lê Thị C');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loaisp`
--

CREATE TABLE `loaisp` (
  `idloai` int(11) NOT NULL,
  `tenloai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `loaisp`
--

INSERT INTO `loaisp` (`idloai`, `tenloai`) VALUES
(1, 'Nike'),
(2, 'Adidas'),
(3, 'Vans');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sp`
--

CREATE TABLE `sp` (
  `idsp` int(11) NOT NULL,
  `idloai` int(11) DEFAULT NULL,
  `images` longblob DEFAULT NULL,
  `tensp` varchar(255) NOT NULL,
  `giathanh` int(11) NOT NULL,
  `soluong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sp`
--

INSERT INTO `sp` (`idsp`, `idloai`, `images`, `tensp`, `giathanh`, `soluong`) VALUES
(1, 1, 0x75706c6f6164732f6a6f7264616e352e706e67, 'dâd', 2147483647, 2),
(2, 3, 0x75706c6f6164732f6a6f7264616e63616d2e706e67, 'ukhyuh', 21414141, 31),
(3, 1, 0x75706c6f6164732f6578636c75736976652e6a7067, 'as', 2313, 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `ctdonhang`
--
ALTER TABLE `ctdonhang`
  ADD PRIMARY KEY (`idCTDH`),
  ADD KEY `iddonhang` (`iddonhang`),
  ADD KEY `idsp` (`idsp`);

--
-- Chỉ mục cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`iddonhang`),
  ADD KEY `idKH` (`idKH`);

--
-- Chỉ mục cho bảng `kh`
--
ALTER TABLE `kh`
  ADD PRIMARY KEY (`idKH`),
  ADD UNIQUE KEY `sdt` (`sdt`);

--
-- Chỉ mục cho bảng `loaisp`
--
ALTER TABLE `loaisp`
  ADD PRIMARY KEY (`idloai`);

--
-- Chỉ mục cho bảng `sp`
--
ALTER TABLE `sp`
  ADD PRIMARY KEY (`idsp`),
  ADD KEY `idloai` (`idloai`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `ctdonhang`
--
ALTER TABLE `ctdonhang`
  MODIFY `idCTDH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `donhang`
--
ALTER TABLE `donhang`
  MODIFY `iddonhang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `kh`
--
ALTER TABLE `kh`
  MODIFY `idKH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `loaisp`
--
ALTER TABLE `loaisp`
  MODIFY `idloai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `sp`
--
ALTER TABLE `sp`
  MODIFY `idsp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `ctdonhang`
--
ALTER TABLE `ctdonhang`
  ADD CONSTRAINT `ctdonhang_ibfk_1` FOREIGN KEY (`iddonhang`) REFERENCES `donhang` (`iddonhang`),
  ADD CONSTRAINT `ctdonhang_ibfk_2` FOREIGN KEY (`idsp`) REFERENCES `sp` (`idsp`);

--
-- Các ràng buộc cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`idKH`) REFERENCES `kh` (`idKH`);

--
-- Các ràng buộc cho bảng `sp`
--
ALTER TABLE `sp`
  ADD CONSTRAINT `sp_ibfk_1` FOREIGN KEY (`idloai`) REFERENCES `loaisp` (`idloai`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
