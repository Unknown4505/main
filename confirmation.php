<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test"; // Thay đổi tên cơ sở dữ liệu theo tên của bạn

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy dữ liệu từ bảng donhang, ctdonhang, kh, sp
$sql = "SELECT dh.iddonhang, dh.ngaymua, dh.trangthai, dh.diachi, dh.sdt, kh.tenkh, ctdh.idsp, ctdh.soluong, ctdh.giathanh, sp.tensp
        FROM donhang dh
        JOIN ctdonhang ctdh ON dh.iddonhang = ctdh.iddonhang
        JOIN kh ON dh.idKH = kh.idKH
        JOIN sp ON ctdh.idsp = sp.idsp
        ORDER BY dh.ngaymua DESC";
$result = $conn->query($sql);
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
<section class="banner-area">
    <div class="container">
        <h1>Lịch Sử Giao Dịch</h1>
    </div>
</section>

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
<footer class="footer-area section_gap">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="single-footer-widget">
					<h6>Về Chúng Tôi</h6>
					<p>
						“Kể từ lúc thành lập vào năm 2012, Karma luôn được khách hàng đánh giá là một trong những cửa hang giày chất lượng cao tại Việt Nam. Hiện tại, Karma vẫn tiếp tục duy trì chất lượng dịch vụ và sản phẩm tốt để gìn giữ sự hài lòng của khách hàng.”
					</p>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-6">
				<div class="single-footer-widget">
					<h6>Bảng tin</h6>
					<p>Luôn cập nhật thông tin mới nhất của chúng tôi
					</p>
					<div class="">
						<form target="_blank" novalidate="true" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01"
							  method="get" class="form-inline">
							<div class="form-group lbel-inline">
								<input type="email" class="form-control" name="EMAIL" placeholder="Nhập email" required>
							</div>
							<button class="btn btn-default">
								<span class="lnr lnr-arrow-right"></span>
							</button>
							<div style="position: absolute; left: -5000px;">
								<input type="text" name="b_1462626880ade1ac87bd9c93a_92a4423d01" tabindex="-1" value="">
							</div>
						</form>
					</div>
					<div class="info"></div>
				</div>
			</div>
			<div class="col-lg-2 col-md-6 col-sm-6">
				<div class="single-footer-widget">
					<h6>Instagram</h6>
					<div class="instagram-row">
						<a href="#"><img src="img/i1.jpg" alt=""></a>
						<a href="#"><img src="img/i2.jpg" alt=""></a>
						<a href="#"><img src="img/i3.jpg" alt=""></a>
						<a href="#"><img src="img/i4.jpg" alt=""></a>
						<a href="#"><img src="img/i5.jpg" alt=""></a>
						<a href="#"><img src="img/i6.jpg" alt=""></a>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="single-footer-widget">
					<h6>Liên Hệ Với Chúng Tôi</h6>
					<p>ĐH Sài Gòn , <br>TP.HCM, VietNam</p>
					<p>
						<span class="lnr lnr-phone"></span> + 01 234 567 89<br>
						<span class="lnr lnr-envelope"></span> support@HKTC.com
					</p>
				</div>
			</div>
		</div>
		<div class="row footer-bottom d-flex justify-content-between align-items-center">
			<p class="footer-text m-0 col-lg-6 col-md-6">
				2024 © Mọi quyền được bảo lưu | Mẫu này được tạo với<i class="fa fa-heart" aria-hidden="true"></i> by
				<a href="https://colorlib.com" target="_blank">HKTC</a>
			</p>
			<div class="col-lg-6 col-md-6 footer-social">
				<a href="#"><i class="fa fa-facebook"></i></a>
				<a href="#"><i class="fa fa-twitter"></i></a>
				<a href="#"><i class="fa fa-dribbble"></i></a>
				<a href="#"><i class="fa fa-behance"></i></a>
			</div>
		</div>
	</div>
</footer>
<?php
// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
<!--================Kết Thúc Khu Vực Chi Tiết Đơn Hàng =================-->


<!-- jQuery, Popper.js, Bootstrap JS, (id="search_input_box" và các liên kết có liên quan đến search)data-toggle, role, aria-haspopup, và aria-expanded đã được xóa khỏi các phần tử <a> và <button>-->
</header>
</body>
</html>
