
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
<?php include 'header.php'; ?>
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
    <!--Start footer -->
    <?php include 'footer.php'; ?>
    <!--End footer -->

<!--================End Product Description Area =================-->
</body>
</html>
