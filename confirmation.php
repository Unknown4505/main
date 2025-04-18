
<?php
// Kết nối cơ sở dữ liệu
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test"; // Thay đổi tên cơ sở dữ liệu theo tên của bạn

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Vui lòng đăng nhập để xem lịch sử giao dịch!'); window.location.href='login.php';</script>";
    exit();
}

// Lấy user_id từ session
$user_id = $_SESSION['user_id'];

// Lấy dữ liệu từ bảng donhang, ctdonhang, kh, sp với điều kiện idKH
$sql = "SELECT dh.iddonhang, dh.ngaymua, dh.trangthai, dh.diachi, dh.sdt, kh.tenkh, ctdh.idsp, ctdh.soluong, ctdh.giathanh, sp.tensp
        FROM donhang dh
        JOIN ctdonhang ctdh ON dh.iddonhang = ctdh.iddonhang
        JOIN kh ON dh.idKH = kh.idKH
        JOIN sp ON ctdh.idsp = sp.idsp
        WHERE dh.idKH = ?
        ORDER BY dh.ngaymua DESC";

// Sử dụng prepared statement để tránh SQL injection
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // "i" là kiểu integer
$stmt->execute();
$result = $stmt->get_result();

include 'header.php';
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
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

	<!--
		CSS
		============================================= -->
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/search.css">
	<style>
		body {
        font-family: 'Roboto', sans-serif;
    }

    h1, h6, p, a, button, .single-features, .product-details {
        font-family: 'Roboto', sans-serif;
    }
		th,tr{
			text-align: center;
		}
		h2{
			text-align: center;
		}
	</style>
</head>


<body>

<!-- Start Header Area -->
<!-- Bootstrap and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
    <!-- end header -->

<!-- Bắt Đầu Khu Vực Banner -->
<section class="banner-area organic-breadcrumb">
	<div class="container">
		<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
			<div class="col-first">
				<h1>Lịch sử giao dịch</h1>
				<nav class="d-flex align-items-center">
					<a href="index.html">Trang Chủ<span class="lnr lnr-arrow-right"></span></a>
					<a href="category.php">Lịch sử giao dịch</a>
				</nav>
			</div>
		</div>
	</div>
</section>
<!-- Kết Thúc Khu Vực Banner -->


<!--================Khu Vực Chi Tiết Đơn Hàng =================-->

<!-- Lịch sử giao dịch -->
<section class="order_details section_gap">
    <div class="order_details_table">
        <h2>Chi Tiết Đơn Hàng</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Mã Đơn Hàng</th>
                    <th scope="col">Tên Khách Hàng</th>
                    <th scope="col">Địa Chỉ</th>
                    <th scope="col">Số Điện Thoại</th>
                    <th scope="col">Xem Thêm</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["iddonhang"] . "</td>";
                        echo "<td>" . $row["tenkh"] . "</td>";
                        echo "<td>" . $row["diachi"] . "</td>";
                        echo "<td>" . $row["sdt"] . "</td>";
                        echo "<td><button class='view-more' onclick='location.href=\"historydetail.php?iddonhang=" . $row["iddonhang"] . "\"'>Xem Thêm</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>Không có giao dịch nào.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
    <!--Start footer -->
    <?php include 'footer.php'; ?>
    <!--End footer -->
<?php
// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
<!--================Kết Thúc Khu Vực Chi Tiết Đơn Hàng =================-->


<!-- jQuery, Popper.js, Bootstrap JS, (id="search_input_box" và các liên kết có liên quan đến search)data-toggle, role, aria-haspopup, và aria-expanded đã được xóa khỏi các phần tử <a> và <button>-->
</header>
</body>
</html>
