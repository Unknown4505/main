<?php
session_start();
// Kết nối database
$servername = "localhost";
$username = "root";
$password = "";
$database = "mydatabase";

$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý tìm kiếm
$searchResults = [];
if (isset($_GET['query'])) {
    $query = trim($_GET['query']);

    // Chống SQL Injection bằng Prepared Statement
    $sql = "SELECT * FROM sp WHERE tensp LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeQuery = "%$query%";
    $stmt->bind_param("s", $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $searchResults[] = $row;
    }
    $stmt->close();
}

// Truy vấn lấy danh sách 6 sản phẩm mới nhất
$sql_sp = "SELECT sp.*, loaisp.tenloai 
           FROM sp 
           JOIN loaisp ON sp.idloai = loaisp.idloai 
           ORDER BY sp.idsp DESC 
           LIMIT 6";
$result_sp = $conn->query($sql_sp);

// Truy vấn lấy danh sách loại sản phẩm
$sql_loaisp = "SELECT idloai, tenloai FROM loaisp";
$result_loaisp = $conn->query($sql_loaisp);

// Lấy idloai từ URL
$idloai = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Truy vấn danh mục
$sql_loaisp = "SELECT idloai, tenloai FROM loaisp";
$result_loaisp = $conn->query($sql_loaisp);

// Lưu danh mục vào mảng để hiển thị trên header
$categories = [];
if ($result_loaisp->num_rows > 0) {
    while ($row = $result_loaisp->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Truy vấn sản phẩm theo idloai
$products = [];
if ($idloai > 0) {
    $sql_sp = "SELECT * FROM sp WHERE idloai = ?";
    $stmt = $conn->prepare($sql_sp);
    $stmt->bind_param("i", $idloai);
    $stmt->execute();
    $result_sp = $stmt->get_result();

    while ($row = $result_sp->fetch_assoc()) {
        $products[] = $row;
    }
    $stmt->close();
}

// Truy vấn tên danh mục để hiển thị tiêu đề
$tenloai = "Danh mục sản phẩm";
if ($idloai > 0) {
    $sql = "SELECT tenloai FROM loaisp WHERE idloai = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idloai);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $tenloai = $row['tenloai'];
    }
    $stmt->close();
}

// Truyền danh mục sang header
include 'header.php';
?>


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
	<!--
		CSS
		============================================= -->
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
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/Z_banner.scss">
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/category.css">
    <link rel="stylesheet" href="css/index.css">
<!--start header-->
  <?php include 'header.php'; ?>
<!-- end header -->

<!-- bắt đầu khu vực banner1 -->
<section class="banner-area">
	<div class="container">
		<div class="slider">
			<div class="slide active">
				<div class="row">
					<div class="col-lg-5">
						<div class="banner-content">
							<h1>Chào mừng giáo sinh với ưu đãi lên tới 50%</h1>

						</div>
					</div>
					<div class="col-lg-7">
						<div class="banner-img">
							<img class="img-fluid" src="img/banner/banner-img.png" alt="">
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Các điều khiển mũi tên -->
		<div class="arrow left">&#10094;</div>
		<div class="arrow right">&#10095;</div>
	</div>
</section>
<!-- Kết thúc khu vực banner -->

<!-- bắt đầu khu vực tính năng -->
<section class="features-area section_gap">
	<div class="container">
		<div class="row features-inner">
			<!-- tính năng đơn -->
			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="single-features">
					<div class="f-icon">
						<img src="img/features/f-icon1.png" alt="">
					</div>
					<h6>Miễn Phí Vận Chuyển</h6>
					<p>Miễn phí vận chuyển cho tất cả đơn hàng</p>
				</div>
			</div>
			<!-- tính năng đơn -->
			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="single-features">
					<div class="f-icon">
						<img src="img/features/f-icon2.png" alt="">
					</div>
					<h6>Chính Sách Đổi Hàng</h6>
					<p>Miễn phí vận chuyển cho tất cả đơn hàng</p>
				</div>
			</div>
			<!-- tính năng đơn -->
			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="single-features">
					<div class="f-icon">
						<img src="img/features/f-icon3.png" alt="">
					</div>
					<h6>Hỗ Trợ 24/7</h6>
					<p>Miễn phí vận chuyển cho tất cả đơn hàng</p>
				</div>
			</div>
			<!-- tính năng đơn -->
			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="single-features">
					<div class="f-icon">
						<img src="img/features/f-icon4.png" alt="">
					</div>
					<h6>Thanh Toán An Toàn</h6>
					<p>Miễn phí vận chuyển cho tất cả đơn hàng</p>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- kết thúc khu vực tính năng -->
    <section class="lattest-product-area pb-40 category-list">
        <div class="row">
            <?php
            if ($result_sp->num_rows > 0) {
                while ($row = $result_sp->fetch_assoc()) {
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="single-product">
                            <img class="img-fluid" src="<?php echo $row['images']; ?>" alt="<?php echo $row['tensp']; ?>">

                            <div class="product-details">
                                <h6><?php echo $row['tensp']; ?></h6>
                                <div class="product-category">
                                    <span>Loại : <?php echo $row['tenloai']; ?></span>
                                </div>
                                <div class="price">
                                    <h6><?php echo number_format($row['giathanh'], 0, ',', '.'); ?>đ</h6>
                                </div>
                                <div class="prd-bottom">
                                    <a href="cart.php?add=<?php echo $row['idsp']; ?>" class="social-info">
                                        <span class="ti-bag"></span>
                                        <p class="hover-text">Thêm vào giỏ hàng</p>
                                    </a>

                                    <a href="single-product.php?id=<?php echo $row['idsp']; ?>" class="social-info button">
                                        <span class="lnr lnr-move"></span>
                                        <span class="hover-text">Xem chi tiết</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="col-12">
                    <p class="text-center">Không có sản phẩm nào.</p>
                </div>
                <?php
            }
            $conn->close();
            ?>
        </div>
    </section>


<script>
	function addToBag() {
		alert("Bạn đã thêm vào giỏ hàng thành công!");
	}
</script>
<!-- End footer Area -->
</body>
</head>
</html>
