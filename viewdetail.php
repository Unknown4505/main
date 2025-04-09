<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "test";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login-admin.php");
    exit;
}
$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem có `id` đơn hàng không
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Không tìm thấy đơn hàng.");
}

$order_id = intval($_GET['id']); // Ép kiểu để tránh lỗi SQL Injection

// Lấy thông tin đơn hàng
$sql = "SELECT donhang.*, kh.tenkh, kh.sdt FROM donhang 
        JOIN kh ON donhang.idKH = kh.idKH 
        WHERE donhang.iddonhang = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Không tìm thấy đơn hàng.");
}

$order = $result->fetch_assoc();

// Lấy danh sách sản phẩm trong đơn hàng
$sql_products = "SELECT sp.tensp, ctdonhang.soluong FROM ctdonhang
                 JOIN sp ON ctdonhang.idsp = sp.idsp 
                 WHERE ctdonhang.iddonhang = ?";
$stmt = $conn->prepare($sql_products);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$products_result = $stmt->get_result();

$products = [];
while ($row = $products_result->fetch_assoc()) {
    $products[] = $row;
}

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
    <link rel="stylesheet" href="css/viewdetail.css">
</head>
<body>
<?php include 'header-admin.php'?>

<div class="admin-container">
    <?php include 'sidebar.php'; ?>

    <div class="container">
        <h2>Chi Tiết Đơn Hàng</h2>

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
            <div class="info"><?php echo "DH" . htmlspecialchars($order['iddonhang']); ?></div>
        </div>
        <div class="info-group">
            <label>Ngày đặt hàng:</label>
            <div class="info"><?php echo htmlspecialchars($order['ngaymua']); ?></div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="product-info">
            <label>Danh sách sản phẩm:</label>
            <?php foreach ($products as $product) { ?>
                <div class="info">
                    <span><?php echo htmlspecialchars($product['tensp']); ?></span>
                    <span>x<?php echo $product['soluong']; ?></span>
                </div>
            <?php } ?>
        </div>

        <!-- Tổng tiền -->
        <div class="total-info">
            <span>Tổng tiền:</span>
            <span><?php echo number_format($order['tongtien'], 0, ',', '.'); ?> VND</span>
        </div>

    </div>
</div>

</body>
</html>
