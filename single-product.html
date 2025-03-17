
<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
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
	<!-- CSS -->

	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/ion.rangeSlider.css" />
	<link rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css" />
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/search.css">
</head>
</head>

<body>

<!-- Start Header Area -->
<?php include 'header2.php'; ?>
<!-- Bootstrap and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
    <!-- end header -->
<!-- Bootstrap and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
    <!-- end header -->

<!-- Start Banner Area -->
<section class="banner-area organic-breadcrumb">
	<div class="container">
		<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
			<div class="col-first">
				<h1>Chi tiết sản phẩm</h1>
				<nav class="d-flex align-items-center">
					<a href="index.html">Trang chủ<span class="lnr lnr-arrow-right"></span></a>
					<a href="#">Shop<span class="lnr lnr-arrow-right"></span></a>
					<a href="single-product.php">Chi tiết sản phẩm</a>
				</nav>
			</div>
		</div>
	</div>
</section>
<!-- End Banner Area -->

<!--================Single Product Area =================-->

<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root"; // Thay bằng username của bạn
$password = ""; // Nếu có mật khẩu, hãy điền vào đây
$database = "test"; // Thay bằng tên database của bạn

$conn = new mysqli($servername, $username, $password, $database);
if (isset($_GET['id'])) {
    $idsp = $_GET['id'];
    $sql = "SELECT sp.*, loaisp.tenloai FROM sp 
            JOIN loaisp ON sp.idloai = loaisp.idloai 
            WHERE sp.idsp = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idsp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        ?>

        <div class="product_image_area">
            <div class="container">
                <div class="row s_product_inner">
                    <div class="col-lg-6">
                        <div class="s_Product_carousel">
                            <div class="single-prd-item">
                                <img src="<?php echo $row['images']; ?>"
                                     alt="<?php echo $row['tensp']; ?>"
                                     style="width: 100%; max-width: 600px; height: auto; display: block; margin: 0 auto;">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 offset-lg-1">
                        <div class="s_product_text">
                            <h3><?php echo $row['tensp']; ?></h3>
                            <h2><?php echo number_format($row['giathanh'], 0, ',', '.'); ?>đ</h2>
                            <ul class="list">
                                <li><span>Loại:</span> <?php echo $row['tenloai']; ?></li>
                                <li><span>Tình trạng:</span> <?php echo ($row['soluong'] > 0) ? 'Còn hàng' : 'Hết hàng'; ?></li>
                            </ul>
                            <p><?php echo $row['mota']; ?></p>

                            <div class="product_count">
                                <label for="qty">Số lượng:</label>
                                <input type="number" name="qty" id="sst" value="1" min="1" max="<?php echo $row['soluong']; ?>" class="input-text qty">
                            </div>

                            <div class="card_area d-flex align-items-center">
                                <a class="primary-btn" href="cart.php?add=<?php echo $row['idsp']; ?>">Thêm vào giỏ hàng</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
    } else {
        echo "<p class='text-center'>Không tìm thấy sản phẩm.</p>";
    }
    $stmt->close();
} else {
    echo "<p class='text-center'>Không có sản phẩm nào được chọn.</p>";
}
$conn->close();
?>
<!-- Hộp thông báo "Đã thêm vào giỏ hàng thành công" -->
<div id="success-alert" style="display: none; position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background-color: #28a745; color: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);">
	<p>Đã thêm vào giỏ hàng thành công!</p>
</div>

<!--================End Single Product Area =================-->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- JavaScript -->
<script>
	// Hiển thị thông báo khi thêm vào giỏ hàng
	function addToCart() {
		var alertBox = document.getElementById('success-alert');
		alertBox.style.display = 'block';

		// Ẩn thông báo sau 3 giây
		setTimeout(function() {
			alertBox.style.display = 'none';
		}, 3000);
	}
</script>

<!--================Product Description Area =================-->

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

<!--================End Product Description Area =================-->
</body>
</html>
