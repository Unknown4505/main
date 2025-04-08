<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/add-product.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thêm sản phẩm</title>
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
    /* Admin Container */
    .admin-container {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      width: 15%;
      background-color: #232323;
      color: white;
      padding: 20px 15px;
    }
    body, html {
      margin: 0;
      padding: 0;
    }

    .sidebar .logo-header {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }

    .sidebar .logo-header img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 10px;
    }

    .sidebar .logo-header h2 {
      color: white;
      font-size: 22px;
      margin: 0;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
    }

    .sidebar ul li {
      margin: 15px 0;
    }

    .sidebar ul li a {
      color: white;
      text-decoration: none;
      font-size: 18px;
      display: block;
      padding: 10px;
      border-radius: 5px;
      transition: background 0.3s;
    }

    .sidebar ul li a:hover {
      background-color: #34495e;
    }

    /* Main Content */
    .main-content {
      flex: 1;
      padding: 20px;
      background-color: #ecf0f1;
    }

    .main-content h1 {
      font-size: 28px;
      margin-bottom: 20px;
    }

    /* Dashboard Cards */
    .dashboard {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .card {
      flex: 1 1 calc(20% - 20px);
      background-color: white;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 15px;
      box-shadow: 0 2px 4px
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
<?php include 'header-admin.php'?>

<div class="admin-container">
  <!-- Sidebar -->
<?php include 'sidebar.php'?>

  <!-- Main Content -->
  <div class="main-content">
    <h1>Thêm người dùng</h1>
    <form id="addProductForm" onsubmit="return handleAddProduct(event)">
      <!-- Tên sản phẩm -->
      <label for="productName">Tên người dùng:</label>
      <input type="text" id="productName" name="productName" required><br><br>

      <!-- Mô tả sản phẩm -->
      <label for="productName">Địa chỉ email:</label>
      <input type="text" id="productName" name="productName" required><br><br>

      <label for="productName">Ngày sinh:</label>
      <input type="text" id="productName" name="productName" required><br><br>

      <label for="productName">Số điện thoại:</label>
      <input type="text" id="productName" name="productName" required><br><br>

      <label for="productName">Địa chỉ:</label>
      <input type="text" id="productName" name="productName" required><br><br>

      <!-- Nút thêm sản phẩm -->
      <button type="button" onclick="handleAddProduct()">Thêm</button>
    </form>

    <!-- Modal hiện thông báo thêm thành công -->
    <div id="successModal" style="text-align: center;">
      <p>Đã thêm thành công!</p>
      <button onclick="closeModal()">OK</button>
    </div>

    <!-- Overlay mờ khi hiển thị modal -->
    <div id="overlay"></div>
  </div>
</div>

<script>

  function handleAddProduct() {
    // Hiển thị modal thông báo thành công
    document.getElementById("successModal").style.display = "block";
    document.getElementById("overlay").style.display = "block";
  }
  // Hiển thị thông báo thành công

  function closeModal() {
    // Ẩn modal thông báo và overlay
    document.getElementById("successModal").style.display = "none";
    document.getElementById("overlay").style.display = "none";

    // Reset form sau khi thêm thành công
    document.getElementById("addProductForm").reset();
  }
</script>


</body>
</html>
