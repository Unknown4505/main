
<!DOCTYPE html>
<html lang="zxx" class="no-js">
<head>
  <!-- Mobile Specific Meta -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="shortcut icon" href="img/fav.png">
  <meta name="author" content="CodePixar">
  <meta charset="UTF-8">
  <title>Karma Shop</title>
  <link rel="stylesheet" href="css/linearicons.css">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/themify-icons.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/owl.carousel.css">
  <link rel="stylesheet" href="css/nice-select.css">
  <link rel="stylesheet" href="css/nouislider.min.css">
  <link rel="stylesheet" href="css/ion.rangeSlider.css" />
  <link rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css" />
  <link rel="stylesheet" href="css/magnific-popup.css">
  <link rel="stylesheet" href="css/search.css"><link rel="stylesheet" href="getp.php">
  <style>
    .admin-container { display: flex; min-height: 100vh; }
    .sidebar { width: 250px; background-color: #2c3e50; color: white; padding: 20px 15px; }
    .sidebar ul { list-style: none; padding: 0; }
    .sidebar ul li a { color: white; text-decoration: none; font-size: 18px; padding: 10px; display: block; border-radius: 5px; }
    .sidebar ul li a:hover { background-color: #34495e; }
    .dropdown-menu { display: none; position: absolute; top: 100%; left: 0; background-color: #34495e; }
    .dropdown:hover .dropdown-menu { display: block; }
    .modal { display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); }
    .modal-content { background-color: #fefefe; padding: 20px; width: 300px; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; }
    .modal-buttons { margin-top: 20px; }
    .modal-buttons .btn { padding: 10px 20px; margin: 10px; cursor: pointer; background-color: #000000; border: none; font-size: 16px; }
    .modal-buttons .btn:hover { background-color: #000000; }
    .dropdown { position: relative; }
    /* Admin Container */
    .admin-container {
      display: flex;
      min-height: 100vh;
    }
    .dropdown { position: relative; }
    /* Admin Container */
    .admin-container {
      display: flex;
      min-height: 100vh;
    }

    .dropdown { position: relative; }
    /* Admin Container */
    .admin-container {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      width: 15%;
      background-color: #252525;
      color: white;
      padding: 20px 15px;
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
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #efafee, #afefc1);
      margin: 0;
      padding: 0;
      color: #333;
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
    .pagination {
      display: flex;
      justify-content: center;
      padding: 20px 0;
    }

    .pagination a {
      color: #2c3e50;
      text-decoration: none;
      margin: 0 5px;
      padding: 8px 16px;
      border: 1px solid #ddd;
      border-radius: 4px;
      transition: background-color 0.3s, color 0.3s;
    }

    .pagination a:hover {
      background-color: #34495e;
      color: white;
    }

    .pagination a.active {
      background-color: #2c3e50;
      color: white;
      border: 1px solid #2c3e50;
    }
    /* Bộ lọc */
    .filter-container {
      margin: 20px 0;
      padding: 10px;
      background-color: #f9f9f9;
      border: 1px solid #ddd;
      border-radius: 5px;
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center; /* Căn giữa theo chiều ngang */
      align-items: center; /* Căn giữa theo chiều dọc */
    }

    .filter-container form {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center; /* Căn giữa form */
    }

    .filter-category,
    .filter-name {
      display: flex;
      flex-direction: column;
      align-items: center; /* Căn giữa nội dung theo chiều ngang */

    }
    .filter-category input,
    .filter-name input {
      width: 450px; /* Tăng chiều rộng */
      height: 40px; /* Tăng chiều cao */
      padding: 10px; /* Thêm khoảng cách bên trong */
      font-size: 16px; /* Tăng kích thước chữ */
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .filter-category select,
    .filter-name select {
      width: 300px;
      height: 40px;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }


    .filter-category label,
    .filter-name label {
      margin-bottom: 5px;
      font-weight: bold;
    }

    .filter-actions {
      display: flex;
      align-items: center;
      justify-content: center; /* Căn giữa nội dung của filter-actions */
      position: relative;
      width: 100%;
    }


    button {
      padding: 8px 16px;
      background-color: #2c3e50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
      position: absolute;
      bottom: 10px; /* Cách đáy 10px */
      right: 10px; /* Cách phải 10px */
    }

    button:hover {
      background-color: #34495e;
    }

    .filter-btn {
      display: inline-block;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      text-align: center;
      text-decoration: none;
      border-radius: 5px;
      font-size: 16px;
    }

    .filter-btn:hover {
      background-color: #45a049;
    }
    .product-table {
      margin-top: 20px;
      margin-left:auto;
      margin-right: auto;
    }


    .product-img {
      width: 100px;
      height: auto;
      display: block;
      margin: 0 auto;
    }

    .action-btn {
      margin-right: 10px;
      padding: 5px 10px;
      color: white;
      background-color: #85a8d3;
      text-decoration: none;
      border-radius: 5px;
    }

    .action-btn:hover {
      background-color: #0b0b0b;
    }
    .table-container {
      margin: 30px;
      text-align: center;
      width: 70%; /* Điều chỉnh theo ý muốn */
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      background-color: #fff;
      border-radius: 8px;
      overflow: hidden;
    }

    table, th, td {
      border: 1px solid #ddd;
    }

    th, td {
      padding: 15px;
      text-align: center;
      width: 200px;

    }
    table tr{
      height: 100px;
    }
    th {
      background-color: #2e2e2e;
      color: #fff;
      text-transform: uppercase;
      height: 50px;
    }

    tr:nth-child(even) td {
      background-color: rgb(255, 255, 255);
    }

    tr:nth-child(odd) td {
      background-color: rgb(255, 254, 254);
    }

    tr:hover td {
      background-color: #ffffff;
    }

    /* Kiểu dáng cơ bản cho nút */
    .btn-link {
      display: inline-block;
      padding: 10px 20px;
      background-color: #84cc45; /* Màu xanh dương */
      color: white; /* Màu chữ */
      text-decoration: none; /* Bỏ gạch chân */
      border-radius: 5px; /* Bo góc */
      font-size: 16px; /* Kích thước chữ */
      font-family: Arial, sans-serif;
      text-align: center;
      transition: background-color 0.3s ease, transform 0.2s ease; /* Hiệu ứng */
    }

    /* Hiệu ứng khi di chuột */
    .btn-link:hover {
      background-color: #0056b3; /* Màu xanh đậm hơn */
      transform: scale(1.05); /* Phóng to nhẹ */
    }

    /* Thêm hiệu ứng khi nhấn */
    .btn-link:active {
      background-color: #004080; /* Màu xanh tối */
      transform: scale(0.95); /* Nhỏ lại nhẹ */
    }





  </style>
</head>
<body>
<?php include 'header-admin.php'?>
<div class="admin-container">
<?php include 'sidebar.php' ?>

  <section class="product-table section_gap">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h1>Quản lý sản phẩm</h1>
        </div>
      </div>
      <!-- Bộ lọc -->
        <div class="filter-container">
            <form id="filter-form" method="GET" action="">
                <div class="filter-category">
                    <label for="tenloai">Phân loại:</label>
                    <select id="tenloai" name="tenloai">
                        <option value="all">Tất cả</option>
                        <option value="adidas">Adidas</option>
                        <option value="nike">Nike</option>
                        <option value="vans">Vans</option>
                    </select>
                </div>

                <div class="filter-name">
                    <label for="product-name">Tên sản phẩm:</label>
                    <input type="text" id="product-name" name="product-name" placeholder="Nhập tên sản phẩm...">
                </div>

                <div class="filter-price">
                    <label for="min-price">Giá từ:</label>
                    <input type="number" id="min-price" name="min-price" placeholder="Min">
                    <label for="max-price">đến:</label>
                    <input type="number" id="max-price" name="max-price" placeholder="Max">
                </div>

                <div class="filter-actions">
                    <button type="submit" class="filter-btn">Lọc</button>
                </div>
            </form>
        </div>

      <a href="add-product.php" class="btn-link">Thêm</a>
      <h2>Danh sách sản phẩm:</h2>
        <div id="product-list">
        <?php include 'getp.php'; ?>
      <div class="pagination">
        <a href="?page=1">&laquo;</a> <!-- Quay lại trang 1 -->
        <a href="?page=1">1</a> <!-- Trang 1 -->
        <a href="?page=2">2</a> <!-- Trang 2 -->
        <a href="?page=3">3</a> <!-- Trang 3 -->
        <a href="?page=4">4</a> <!-- Trang 4 -->
        <a href="?page=5">5</a> <!-- Trang 5 -->
        <a href="?page=5">&raquo;</a> <!-- Chuyển tới trang 5 -->
      </div>
    </div>
  </section>
  <div id="confirmModal1" class="modal">
    <div class="modal-content">
      <p>Bạn có chắc chắn muốn xóa sản phẩm này không?</p>
      <div class="modal-buttons">
        <button class="btn" onclick="confirmDelete()">Xác nhận</button>
        <button class="btn" onclick="closeModal('confirmModal1')">Hủy</button>
      </div>
    </div>
  </div>


  <script>
    function openModal(modalId) {
      document.getElementById(modalId).style.display = 'block';
    }

    function closeModal(modalId) {
      document.getElementById(modalId).style.display = 'none';
    }

    function confirmDelete() {
      closeModal('confirmModal1');
      alert('Sản phẩm đã bị xóa.');
    }
  </script>
</div>
</body>
<script>
    document.getElementById("filter-form").addEventListener("submit", function(event) {
        event.preventDefault(); // Ngăn chặn tải lại trang

        let formData = new FormData(this); // Lấy dữ liệu từ form
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "locsp.php?" + new URLSearchParams(formData).toString(), true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById("product-list").innerHTML = xhr.responseText; // Cập nhật danh sách sản phẩm
            }
        };

        xhr.send();
    });
</script>

</html>
