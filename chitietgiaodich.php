<?php
// Kết nối cơ sở dữ liệu
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy iddonhang từ URL
if (!isset($_GET['iddonhang']) || empty($_GET['iddonhang'])) {
echo "<p>Không tìm thấy mã đơn hàng.</p>";
exit();
}

$iddonhang = $_GET['iddonhang'];

// Truy vấn thông tin đơn hàng và khách hàng
$sql_order = "SELECT dh.iddonhang, dh.ngaymua, dh.diachi, dh.sdt, dh.ghichu, dh.phuongthucthanhtoan, kh.tenkh
FROM donhang dh
JOIN kh ON dh.idKH = kh.idKH
WHERE dh.iddonhang = ?";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("i", $iddonhang);
$stmt_order->execute();
$result_order = $stmt_order->get_result();

if ($result_order->num_rows == 0) {
echo "<p>Không tìm thấy đơn hàng với mã này.</p>";
exit();
}

$order = $result_order->fetch_assoc();

// Truy vấn chi tiết sản phẩm trong đơn hàng
$sql_details = "SELECT sp.tensp, ctdh.soluong, ctdh.giathanh
FROM ctdonhang ctdh
JOIN sp ON ctdh.idsp = sp.idsp
WHERE ctdh.iddonhang = ?";
$stmt_details = $conn->prepare($sql_details);
$stmt_details->bind_param("i", $iddonhang);
$stmt_details->execute();
$result_details = $stmt_details->get_result();

// Tính tổng tiền
$total = 0;
$products = [];
while ($row = $result_details->fetch_assoc()) {
$products[] = $row;
$total += $row['soluong'] * $row['giathanh'];
}
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

    /* Main Content */
    .dashboard-container {
      flex: 1;
      padding: 20px;
      background-color: #ffffff;
      border-radius: 8px;
      margin: 15px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .dashboard-container h1 {
      text-align: center;
      font-size: 28px;
      margin-bottom: 30px;
      color: #212529;
      font-weight: 600;
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
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #afefe1, #f4ddab);
      margin: 0;
      padding: 0;
      color: #333;
    }

    .admin-container {
      display: flex;
      min-height: 100vh;
    }



    h1 {
      text-align: center;
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
      justify-content: space-between;
    }

    .filter-container form {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .filter-category,
    .filter-name {
      display: flex;
      flex-direction: column;
    }

    .filter-category label,
    .filter-name label {
      margin-bottom: 5px;
      font-weight: bold;
    }

    .filter-actions {
      display: flex;
      align-items: center;
    }

    /* Pagination */
    .pagination {
      display: flex;
      justify-content: center;
      padding: 20px 0;
    }

    .pagination a {
      color: #343a40;
      text-decoration: none;
      margin: 0 5px;
      padding: 8px 16px;
      border: 1px solid #dee2e6;
      border-radius: 4px;
      transition: all 0.3s;
    }

    .pagination a:hover {
      background-color: #343a40;
      color: white;
    }

    .pagination a.active {
      background-color: #007bff;
      color: white;
      border: 1px solid #007bff;
    }
    .container {
      width: 100%;
      max-width: 800px;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 8px;
      background-color: white;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      margin-left: auto;
      margin-right: auto;
    }
    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
      font-size: 24px;
    }
    .info-group {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
    }
    .info-group label {
      font-weight: bold;
      color: #555;
      width: 30%;
    }
    .info-group .info {
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      background-color: #f8f8f8;
      color: #333;
      width: 65%;
      font-size: 14px;
    }
    .product-info {
      margin-top: 20px;
      margin-bottom: 20px;
    }
    .product-info label {
      font-weight: bold;
      color: #333;
      font-size: 16px;
      margin-bottom: 10px;
      display: block;
    }
    .product-info .info {
      display: flex;
      justify-content: space-between;
      padding: 10px 15px;
      border: 1px solid #ddd;
      border-radius: 5px;
      background-color: #f8f8f8;
      margin-bottom: 10px;
      font-weight: bold;
      font-size: 14px;
    }
    .total-info {
      display: flex;
      justify-content: space-between;
      font-weight: bold;
      margin-top: 20px;
      font-size: 16px;
    }
    .total-info span {
      color: #333;
    }
    .buttons {
      display: flex;
      justify-content: center; /* Căn giữa nút */
      margin-top: 30px;
    }
    .button {
      padding: 12px 25px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      color: white;
      background-color: #007bff;
      text-decoration: none;
      text-align: center;
      width: 48%;
    }
    .button:hover {
      background-color: #0056b3;
    }

  </style>
</head>
<body>
<?php include 'header-admin.php'?>
<div class="admin-container">
    <!-- Sidebar -->
<?php include 'sidebar.php' ?>
  <div class="container">
    <h2>Chi Tiết Hóa Đơn</h2>

    <!-- Thông tin khách hàng -->
    <div class="info-group">
      <label>Tên khách hàng:</label>
        <div class="info"><?php echo htmlspecialchars($order['tenkh']); ?></div>
    </div>
    <div class="info-group">
      <label>Số điện thoại:</label>
        <div class="info"><?php echo htmlspecialchars($order['sdt']); ?></div>
    </div>
    <div class="info-group">
      <label>Địa chỉ nhận hàng:</label>
        <div class="info"><?php echo htmlspecialchars($order['diachi']); ?></div>
    </div>
    <div class="info-group">
      <label>Phương thức thanh toán:</label>
        <div class="info"><?php echo htmlspecialchars($order['phuongthucthanhtoan']); ?></div>
    </div>
    <div class="info-group">
        <label>Ghi Chú:</label>
        <textarea disabled rows="3" class="info" style="font-family: 'Roboto', sans-serif;"><?php echo htmlspecialchars($order['ghichu']); ?></textarea>
    </div>
    <div class="info-group">
      <label>Mã giao dịch:</label>
        <div class="info"><?php echo htmlspecialchars($order['iddonhang']); ?></div>
    </div>
    <div class="info-group">
      <label>Ngày đặt hàng:</label>
        <div class="info"><?php echo htmlspecialchars($order['ngaymua']); ?></div>
    </div>

      <!-- Danh sách sản phẩm -->
      <div class="product-info">
          <label>Danh sách sản phẩm:</label>
          <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
              <tbody>
              <?php foreach ($products as $product) {
                  $thanhtien = $product['giathanh'] * $product['soluong'];
                  ?>
                  <tr>
                      <td style="padding: 8px;"><?php echo htmlspecialchars($product['tensp']); ?></td>
                      <td style="text-align: center;">x<?php echo $product['soluong']; ?></td>
                      <td style="text-align: right;"><?php echo number_format($product['giathanh'], 0, ',', '.'); ?> VND</td>
                      <td style="text-align: right;"><?php echo number_format($thanhtien, 0, ',', '.'); ?> VND</td>
                  </tr>
              <?php } ?>
              </tbody>
          </table>
      </div>

      <!-- Giá tiền thanh toán -->
      <div class="total-info">
          <span>Tổng tiền:</span>
          <span><?php echo number_format($total, 0, ',', '.') . ' VND'; ?></span>
      </div>
    <!-- Nút quay lại -->
  </div>
</div>


</body>
</html>
