<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login-admin.php");
    exit;
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// Kiểm tra kết nối
if ($conn->connect_error) {
die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn tổng doanh thu từ đơn hàng có trạng thái "Hoàn thành"
$sql_tongthu = "SELECT SUM(tongtien) AS total_revenue FROM donhang WHERE trangthai = 'Hoàn thành'";
$result_tongthu = $conn->query($sql_tongthu);
$row_tongthu = $result_tongthu->fetch_assoc();
$total_revenue = $row_tongthu['total_revenue'] ?? 0;

// Truy vấn số lượng người dùng
$sql_users = "SELECT COUNT(*) AS total_users FROM kh";
$result_users = $conn->query($sql_users);
$row_users = $result_users->fetch_assoc();
$total_users = $row_users['total_users'] ?? 0;

// Truy vấn số lượng sản phẩm
$sql_products = "SELECT COUNT(*) AS total_products FROM sp";
$result_products = $conn->query($sql_products);
$row_products = $result_products->fetch_assoc();
$total_products = $row_products['total_products'] ?? 0;

// Truy vấn số lượng đơn hàng
$sql_orders = "SELECT COUNT(*) AS total_orders FROM donhang";
$result_orders = $conn->query($sql_orders);
$row_orders = $result_orders->fetch_assoc();
$total_orders = $row_orders['total_orders'] ?? 0;

// Kiểm tra tình trạng shop
$status = ($total_orders > 0) ? "Hoạt động" : "Chưa có giao dịch";

// Đóng kết nối
$conn->close();
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
  .main-content {
    flex: 1;
    padding: 25px;
    background: linear-gradient(135deg, #a8b4ff, #fcffb0);
    border-radius: 16px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
  }

  /* Dashboard Container (Dùng CSS Grid) */
  .dashboard-container {
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 6px 30px rgba(0, 0, 0, 0.2);
    display: grid; /* Chuyển sang sử dụng grid */
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Cột linh hoạt */
    gap: 24px; /* Khoảng cách giữa các mục */
    justify-items: center; /* Căn giữa nội dung trong mỗi mục */
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

<?php include 'header-admin.php' ?>
<div class="admin-container">
  <!-- Sidebar -->
<?php include 'sidebar.php' ?>

  <!-- Main Content -->
  <div class="main-content">
    <h1>Thông tin quản trị</h1>
    <div class="dashboard">
      <div class="card">
        <h3>Tổng thu</h3>
        <p><?php echo number_format($total_revenue, 0, ',', '.'); ?> VND</p>
      </div>
      <div class="card">
        <h3>Số lượng người dùng</h3>
        <p><?php echo number_format($total_users); ?></p>
      </div>
      <div class="card">
        <h3>Số lượng sản phẩm</h3>
        <p><?php echo number_format($total_products); ?></p>
      </div>
      <div class="card">
        <h3>Số lượng đơn hàng</h3>
        <p><?php echo number_format($total_orders); ?></p>
      </div>
      <div class="card">
        <h3>Tình trạng shop</h3>
        <p><?php echo $status; ?></p>
      </div>
    </div>
  </div>
</div>
</body>
</html>
