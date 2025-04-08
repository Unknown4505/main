<!-- header.php -->
<?php
session_start();  // Bắt đầu phiên làm việc để kiểm tra trạng thái đăng nhập

// Kiểm tra xem người dùng đã đăng nhập chưa
$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giao diện Admin</title>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/fav.png">
    <!-- Author Meta -->
    <meta name="author" content="CodePixar">
    <!-- Meta Description -->
    <meta name="description" content="">
    <!-- Meta Keyword -->
    <meta name="keywords" content="">
    <!-- meta character set -->
    <meta charset="UTF-8">
    <!-- Site Title -->
    <title>Karma Shop</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        h1, h6, p, a, button, .single-features, .product-details {
            font-family: 'Roboto', sans-serif;
        }
        body, html {
            margin: 0;
            padding: 0;
        }

        /* Admin Container */
        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Individual Card */
        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            text-align: center;
            border: 2px solid transparent;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            border-color: #4a90e2;
        }

        /* Card Content */
        .card h2 {
            font-size: 20px;
            margin-bottom: 12px;
            font-weight: bold;
            color: #222;
        }

        .card p {
            font-size: 15px;
            color: #555;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .card .btn {
            display: inline-block;
            padding: 10px 25px;
            background-color: #4a90e2;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .card .btn:hover {
            background-color: #357abd;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* Cột nhỏ hơn */
            }
        }

        @media (max-width: 576px) {
            .dashboard-container {
                grid-template-columns: 1fr; /* 1 cột trên màn hình nhỏ */
            }
        }


        /* Đặt dropdown là relative để menu con hiển thị đúng vị trí */
        .dropdown {
            position: relative;
        }

        /* Menu con bị ẩn mặc định */
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #34495e;
            list-style: none;
            margin: 0;
            padding: 0;
            border-radius: 5px;
            z-index: 10;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        /* Các mục trong menu con */
        .dropdown-menu li {
            margin: 0;
        }

        .dropdown-menu li a {
            display: block;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-bottom: 1px solid #2c3e50;
            transition: background 0.3s;
        }

        .dropdown-menu li a:hover {
            background-color: #2c3e50;
        }

        /* Hiển thị menu con khi di chuột vào dropdown */
        .dropdown:hover .dropdown-menu {
            display: block;
        }

    </style>
</head>
<body>
<header style="background-color: rgb(255,255,255); color: #0b0b0b; padding: 20px 40px; display: flex; align-items: center; justify-content: space-between; font-size: 28px; border-bottom: 5px solid #0b0b0b;">
    <!-- Logo bên trái -->
    <div style="flex: 0; display: flex; align-items: center;">
        <img src="img/fav.png" alt="Karma Logo" style="width: 60px; height: 60px; border-radius: 50%; margin-right: 20px;">
        <h1 style="margin: 0; font-size: 20px; font-weight: bold; text-align: center;">Karma Shop</h1>
    </div>

    <!-- Phần menu bên phải -->
    <nav style="flex: 1;text-align: center">
        <ul style="list-style: none; display: flex; justify-content: right; margin: 0; padding: 0;">
            <li style="margin: 0 20px;"><a href="admin.html" style="color: #0b0b0b; text-decoration: none; font-size: 22px;">Admin</a></li>
            <li style="margin: 0 20px;"><a href="dangxuatadmin.html" style="color: #0b0b0b; text-decoration: none; font-size: 22px;">Đăng xuất</a></li>
        </ul>
    </nav>
</header>
