<?php
session_start();
// Kết nối database
$servername = "localhost";
$username = "root";
$password = "";
$database = "test";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh sách loại sản phẩm
$categories = [];
$category_sql = "SELECT idloai, tenloai FROM loaisp";
$category_result = $conn->query($category_sql);
if ($category_result->num_rows > 0) {
    while ($row = $category_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Xử lý tìm kiếm với prepared statements
$searchResults = [];
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$min_price = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (int)$_GET['min_price'] : null;
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (int)$_GET['max_price'] : null;

$perPage = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;
$totalProducts = 0;
$totalPages = 0;

if (!empty($_GET)) {
    // Base SQL
    $baseSql = "FROM sp JOIN loaisp ON sp.idloai = loaisp.idloai WHERE 1=1";
    $params = [];
    $types = '';

    // Filter conditions
    if ($query !== '') {
        $baseSql .= " AND sp.tensp LIKE ?";
        $params[] = "%$query%";
        $types .= 's';
    }

    if ($category !== '') {
        $baseSql .= " AND sp.idloai = ?";
        $params[] = $category;
        $types .= 'i';
    }

    if ($min_price !== null) {
        $baseSql .= " AND sp.giathanh >= ?";
        $params[] = $min_price;
        $types .= 'i';
    }

    if ($max_price !== null) {
        $baseSql .= " AND sp.giathanh <= ?";
        $params[] = $max_price;
        $types .= 'i';
    }

    // Get total products
    $countSql = "SELECT COUNT(*) " . $baseSql;
    $countStmt = $conn->prepare($countSql);
    if (!empty($params)) {
        $countStmt->bind_param($types, ...$params);
    }
    $countStmt->execute();
    $countStmt->bind_result($totalProducts);
    $countStmt->fetch();
    $countStmt->close();

    $totalPages = ceil($totalProducts / $perPage);

    // Main query with LIMIT
    $sql = "SELECT sp.idsp, sp.tensp, sp.idloai, sp.giathanh, sp.images, loaisp.tenloai 
            " . $baseSql . " LIMIT ?, ?";
    $params[] = $start;
    $params[] = $perPage;
    $types .= 'ii';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $searchResults[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="vi" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="CodePixar">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Karma Shop - Tìm kiếm sản phẩm</title>
    <link rel="shortcut icon" href="img/fav.png">
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/nouislider.min.css">
    <link rel="stylesheet" href="css/ion.rangeSlider.css">
    <link rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/Z_banner.scss">
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/category.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
<?php include 'header.php'; ?>

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

<section class="features-area section_gap">
    <div class="container">
        <div class="row features-inner">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon">
                        <img src="img/features/f-icon1.png" alt="">
                    </div>
                    <h6>Miễn Phí Vận Chuyển</h6>
                    <p>Miễn phí vận chuyển cho tất cả đơn hàng</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon">
                        <img src="img/features/f-icon2.png" alt="">
                    </div>
                    <h6>Chính Sách Đổi Hàng</h6>
                    <p>Miễn phí vận chuyển cho tất cả đơn hàng</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon">
                        <img src="img/features/f-icon3.png" alt="">
                    </div>
                    <h6>Hỗ Trợ 24/7</h6>
                    <p>Miễn phí vận chuyển cho tất cả đơn hàng</p>
                </div>
            </div>
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
<div class="container-fluid mt-4">
    <h2 class="mb-4">Tìm kiếm sản phẩm</h2>
    <form method="GET" action="search.php" class="row g-3">
        <div class="col-md-4 col-sm-6">
            <input type="text" class="form-control" name="query" placeholder="Tên sản phẩm"
                   value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
        </div>
        <div class="col-md-3 col-sm-6">
            <select class="form-select" name="category">
                <option value="">-- Chọn loại sản phẩm --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['idloai']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['idloai']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['tenloai']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 col-sm-6">
            <input type="number" class="form-control" name="min_price" placeholder="Giá từ"
                   value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>">
        </div>
        <div class="col-md-2 col-sm-6">
            <input type="number" class="form-control" name="max_price" placeholder="Giá đến"
                   value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>">
        </div>
        <div class="col-md-1 col-sm-6 d-grid">
            <button type="submit" class="btn btn-primary">Tìm</button>
        </div>
    </form>

    <?php if ((isset($_GET['query']) || isset($_GET['category']) || isset($_GET['min_price']) || isset($_GET['max_price']))): ?>
        <hr class="my-4">
        <?php if (!empty($searchResults)): ?>
            <p class="mt-3">Tìm thấy <strong><?php echo $totalProducts; ?></strong> sản phẩm phù hợp.</p>
        <?php endif; ?>
        <section class="lattest-product-area pb-40 category-list">
            <div class="row">
                <?php if (!empty($searchResults)): ?>
                    <?php foreach ($searchResults as $row): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="single-product">
                                <img class="img-fluid" src="<?php echo htmlspecialchars($row['images']); ?>" alt="<?php echo htmlspecialchars($row['tensp']); ?>">
                                <div class="product-details">
                                    <h6><?php echo htmlspecialchars($row['tensp']); ?></h6>
                                    <div class="product-category">
                                        <span>Loại: <?php echo htmlspecialchars($row['tenloai']); ?></span>
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
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <p class="text-center">Không có sản phẩm nào.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php if ($totalPages > 1): ?>
    <div class="filter-bar d-flex flex-wrap align-items-center">
            <div class="pagination mt-4">
                <?php if ($page > 1): ?>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" class="prev-arrow"><i class="fa fa-long-arrow-left"></i></a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" class="next-arrow"><i class="fa fa-long-arrow-right"></i></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>


    <?php endif; ?>
</div>
</div>

<footer class="footer-area section_gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-footer-widget">
                    <h6>Về Chúng Tôi</h6>
                    <p>
                        “Kể từ lúc thành lập vào năm 2012, Karma luôn được khách hàng đánh giá là một trong những cửa hàng giày chất lượng cao tại Việt Nam. Hiện tại, Karma vẫn tiếp tục duy trì chất lượng dịch vụ và sản phẩm tốt để gìn giữ sự hài lòng của khách hàng.”
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="single-footer-widget">
                    <h6>Bảng tin</h6>
                    <p>Luôn cập nhật thông tin mới nhất của chúng tôi</p>
                    <div class="">
                        <form target="_blank" novalidate="true" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&id=92a4423d01"
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
                    <h6>Liên Hệ Với Chúng Tôi
                        <p>ĐH Sài Gòn, TP.HCM, VietNam</p>
                        <p>
                            <span class="lnr lnr-phone"></span> +01 234 567 89<br>
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

<script src="js/vendor/jquery-2.2.4.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/jquery.ajaxchimp.min.js"></script>
<script src="js/jquery.nice-select.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>

<?php
$conn->close();
?>
