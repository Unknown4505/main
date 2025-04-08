<?php
session_start(); // Bắt đầu session nếu chưa có
session_destroy(); // Hủy tất cả session

// Chuyển hướng về trang chủ (index.php)
header("Location: index.php");
exit(); // Đảm bảo script dừng ngay lập tức
?>
