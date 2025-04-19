<?php
session_start();

// Kết nối database
$servername   = "localhost";
$username = "root";
$password = "";
$database = "test";

$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý tìm kiếm
$searchResults = [];
if (isset($_GET['query'])) {
    $query = trim($_GET['query']);
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

// Số sản phẩm mỗi trang
$limit = 6; // 6 sản phẩm mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Truy vấn lấy 12 sản phẩm mới nhất
$sql_sp = "SELECT sp.*, loaisp.tenloai 
           FROM sp 
           JOIN loaisp ON sp.idloai = loaisp.idloai 
           WHERE sp.ansp = 0 
           ORDER BY sp.idsp DESC 
           LIMIT 12"; // Lấy 12 sản phẩm
$result_sp = $conn->query($sql_sp);

// Tính tổng số trang (12 sản phẩm chia cho 6 mỗi trang = 2 trang)
$total_products = 12;
$total_pages = ceil($total_products / $limit);

// Truy vấn lấy danh sách loại sản phẩm
$sql_loaisp = "SELECT idloai, tenloai FROM loaisp";
$result_loaisp = $conn->query($sql_loaisp);

// Lưu danh mục vào mảng
$categories = [];
if ($result_loaisp->num_rows > 0) {
    while ($row = $result_loaisp->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Phân trang
$sql_sp_paginated = "SELECT sp.*, loaisp.tenloai 
                     FROM sp 
                     JOIN loaisp ON sp.idloai = loaisp.idloai 
                     WHERE sp.ansp = 0 
                     ORDER BY sp.idsp DESC 
                     LIMIT $limit OFFSET $offset";
$result_sp_paginated = $conn->query($sql_sp_paginated);

// Truyền danh mục sang header
include 'header.php';
?>

<!DOCTYPE html>
<html lang="zxx" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Karma Shop</title>
    <link rel="shortcut icon" href="img/fav.png">
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
</head>

<body>
<!-- Banner -->
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
        <div class="arrow left">❮</div>
        <div class="arrow right">❯</div>
    </div>
</section>

<!-- Tính năng -->
<section class="features-area section_gap">
    <div class="container">
        <div class="row features-inner">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon"><img src="img/features/f-icon1.png" alt=""></div>
                    <h6>Miễn Phí Vận Chuyển</h6>
                    <p>Miễn phí vận chuyển cho tất cả đơn hàng</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon"><img src="img/features/f-icon2.png" alt=""></div>
                    <h6>Chính Sách Đổi Hàng</h6>
                    <p>Đổi trả miễn phí trong vòng 7 ngày</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon"><img src="img/features/f-icon3.png" alt=""></div>
                    <h6>Hỗ Trợ 24/7</h6>
                    <p>Tư vấn khách hàng bất cứ lúc nào</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon"><img src="img/features/f-icon4.png" alt=""></div>
                    <h6>Thanh Toán An Toàn</h6>
                    <p>Bảo mật thanh toán tuyệt đối</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sản phẩm -->
<section class="lattest-product-area pb-40 category-list">
    <div class="row">
        <?php if ($result_sp_paginated->num_rows > 0): ?>
            <?php while ($row = $result_sp_paginated->fetch_assoc()): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="single-product">
                        <img class="img-fluid" src="<?php echo $row['images']; ?>" alt="<?php echo $row['tensp']; ?>">
                        <div class="product-details">
                            <h6><?php echo $row['tensp']; ?></h6>
                            <div class="product-category">
                                <span>Loại: <?php echo $row['tenloai']; ?></span>
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
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center">Không có sản phẩm nào.</p>
            </div>
        <?php endif; ?>
        <?php $conn->close(); ?>
    </div>
</section>

<!-- Phân trang -->
<div class="filter-bar d-flex flex-wrap align-items-center">
    <div class="pagination mt-4">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>">«</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'class="active"'; ?>>
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>">»</a>
        <?php endif; ?>
    </div>
</div>
<!-- Footer -->
<?php include 'footer.php' ?>
</body>
</html>
