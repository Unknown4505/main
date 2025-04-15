<?php
session_start();

// Kết nối database
$servername = "localhost";
$username = "root";
$password = "";
$database = "test";

$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy `idloai` từ URL
$idloai = isset($_GET['idloai']) ? intval($_GET['idloai']) : 0;

// Kiểm tra xem có lọc không
$hasFilter = !empty($_GET['query']) || !empty($_GET['priceRange']);
$searchMessage = "";

// Xây dựng câu truy vấn
$sql = "SELECT sp.*, loaisp.tenloai FROM sp 
        JOIN loaisp ON sp.idloai = loaisp.idloai 
        WHERE sp.idloai = ? AND sp.ansp = 0";

// Danh sách tham số
$params = [];
$types = "i"; // idloai là số nguyên
$params[] = &$idloai;

// Nếu có từ khóa tìm kiếm
if (!empty($_GET['query'])) {
    $query = "%" . trim($_GET['query']) . "%";
    $sql .= " AND sp.tensp LIKE ?";
    $types .= "s"; // String
    $params[] = &$query;
}

// Nếu có khoảng giá
if (!empty($_GET['priceRange'])) {
    if ($_GET['priceRange'] === "10000000-") {
        $minPrice = 10000000;
        $sql .= " AND sp.giathanh >= ?";
        $types .= "i";
        $params[] = &$minPrice;
    } else {
        [$minPrice, $maxPrice] = explode("-", $_GET['priceRange']);
        $minPrice = intval($minPrice);
        $maxPrice = intval($maxPrice);

        $sql .= " AND sp.giathanh BETWEEN ? AND ?";
        $types .= "ii";
        $params[] = &$minPrice;
        $params[] = &$maxPrice;
    }
}


// Chuẩn bị truy vấn
$stmt = $conn->prepare($sql);

// Nếu có tham số thì bind_param
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

// Thực thi truy vấn
$stmt->execute();
$result = $stmt->get_result();

// Đếm số sản phẩm tìm thấy
$totalProducts = $result->num_rows;
if ($hasFilter) {
    $searchMessage = "Tìm thấy <strong>$totalProducts</strong> sản phẩm";
}
// Lấy tên loại từ idloai
$tenloai = "";
if ($idloai > 0) {
    $stmt_tenloai = $conn->prepare("SELECT tenloai FROM loaisp WHERE idloai = ?");
    $stmt_tenloai->bind_param("i", $idloai);
    $stmt_tenloai->execute();
    $stmt_tenloai->bind_result($tenloai);
    $stmt_tenloai->fetch();
    $stmt_tenloai->close();
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

    <!-- CSS -->
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/nouislider.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/category.css">

    <style>
        /* Ẩn thông báo kết quả tìm kiếm theo mặc định */
        .results-header {
            display: none; /* Mặc định ẩn */
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0; /* Thêm khoảng cách phía trên và dưới */
            color: #333;
        }

        /* Hiển thị khi có từ khóa tìm kiếm (tùy chỉnh thủ công) */
        .show {
            display: block; /* Hiển thị khi cần */
        }
        /* Đổi màu nền khung sản phẩm */
        .horizontal-categories {
            background-color: #ffffff; /* Màu xanh nước biển */
            padding: 10px;
            border-radius: 5px;
        }

        /* Đổi màu chữ và kiểu chữ của các sản phẩm */
        .horizontal-categories .main-categories .main-nav-list a {
            color: #0b0b0b; /* Màu chữ trắng */
            font-weight: bold;
            text-decoration: none;
        }

        /* Tô sáng sản phẩm khi di chuột qua */
        .horizontal-categories .main-categories .main-nav-list a:hover {
            color: #0b0b0b; /* Màu chữ khi di chuột qua */
        }
        .filter-form-container {
            display: none;
            margin-top: 20px;
            text-align: center;
        }

        #toggleFilter:checked ~ .filter-form-container {
            display: block;
        }

        .filter-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        .filter-button:hover {
            background-color: #0056b3;
        }

        .filter-form {
            display: inline-block;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .filter-form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .filter-form input,
        .filter-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .apply-filter-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .apply-filter-button:hover {
            background-color: #0056b3;
        }
        /* Đảm bảo container sản phẩm hiển thị theo dạng lưới */
        .lattest-product-area .row {
            display: flex !important; /* Sử dụng flexbox */
            flex-wrap: wrap !important; /* Đảm bảo các sản phẩm xuống hàng khi không đủ chỗ */
            justify-content: space-between !important; /* Căn đều khoảng cách giữa các sản phẩm */
        }

        /* Chỉnh lại mỗi sản phẩm chiếm 30% chiều rộng để có 3 sản phẩm 1 hàng */
        .lattest-product-area .single-product {
            flex: 0 0 30% !important; /* Mỗi sản phẩm chiếm 30% chiều rộng */
            box-sizing: border-box !important; /* Đảm bảo padding và border không ảnh hưởng kích thước */
            margin-bottom: 20px !important; /* Khoảng cách giữa các hàng */
        }

        /* Đảm bảo hình ảnh trong sản phẩm tự động điều chỉnh kích thước */
        .lattest-product-area .single-product img {
            width: 80% !important; /* Ảnh vừa với khung sản phẩm */
            height: auto !important; /* Tự động giữ tỷ lệ ảnh */
        }

        /* Responsive cho màn hình nhỏ, hiển thị 2 sản phẩm 1 hàng */
        @media (max-width: 768px) {
            .lattest-product-area .row {
                grid-template-columns: repeat(2, 1fr) !important; /* Hiển thị 2 sản phẩm 1 hàng */
            }
        }

        /* Responsive cho điện thoại, hiển thị 1 sản phẩm 1 hàng */
        @media (max-width: 576px) {
            .lattest-product-area .row {
                grid-template-columns: 1fr !important; /* Hiển thị 1 sản phẩm 1 hàng */
            }
        }




    </style>
</head>

<body id="category">

<!-- Start Header Area -->
<!-- Bootstrap and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<!-- end header -->

<!-- Bắt đầu Khu Vực Banner -->
<section class="banner-area organic-breadcrumb">
    <div class="container">
        <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
            <div class="col-first">
                <h1><?= htmlspecialchars($tenloai) ?></h1>
                <nav class="d-flex align-items-center">
                    <a href="index.html">Trang chủ<span class="lnr lnr-arrow-right"></span></a>
                    <a href="category.php">Cửa hàng<span class="lnr lnr-arrow-right"></span></a>
                    <a href="category.php">Sản phẩm</a>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- Kết thúc Khu Vực Banner -->

<!-- Hiển thị dòng kết quả tìm kiếm -->


<!-- Start Category Section -->

<!-- Bộ lọc -->
<div class="filter-button-container">
    <label for="toggleFilter" class="filter-button">Lọc</label>
    <input type="checkbox" id="toggleFilter" style="display: none;">

    <div class="filter-form-container">
        <form action="category.php" method="GET" class="filter-form">
            <input type="hidden" name="idloai" value="<?php echo $idloai; ?>">

            <label for="productName">Sản phẩm:</label>
            <input type="text" id="productName" name="query" placeholder="Nhập tên sản phẩm"
                   value="<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>">

            <label for="priceRange">Mức giá (đ):</label>
            <select id="priceRange" name="priceRange">
                <option value="" selected>Chọn mức giá</option>
                <option value="0-2500000" <?php if (isset($_GET['priceRange']) && $_GET['priceRange'] == "0-2500000") echo "selected"; ?>>0đ - 2.500.000đ</option>
                <option value="2500000-5000000" <?php if (isset($_GET['priceRange']) && $_GET['priceRange'] == "2500000-5000000") echo "selected"; ?>>2.500.000đ - 5.000.000đ</option>
                <option value="5000000-10000000" <?php if (isset($_GET['priceRange']) && $_GET['priceRange'] == "5000000-10000000") echo "selected"; ?>>5.000.000đ - 10.000.000đ</option>
                <option value="10000000-" <?php if (isset($_GET['priceRange']) && $_GET['priceRange'] == "10000000-") echo "selected"; ?>>Trên 10.000.000đ</option>
            </select>

            <button type="submit" class="apply-filter-button">Áp dụng</button>
        </form>
    </div>
</div>

<!-- Hiển thị thông báo tìm kiếm nếu đã bấm lọc -->
<?php if ($hasFilter) { ?>
    <div class="search-message">
        <p><?php echo $searchMessage; ?></p>
    </div>
<?php } ?>

<!-- Hiển thị sản phẩm -->
<section class="lattest-product-area pb-40 category-list">
    <div class="row">
        <?php if ($totalProducts > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
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
            <?php } ?>
        <?php } else { ?>
            <div class="col-12">
                <p class="text-center">Không có sản phẩm nào thuộc loại này.</p>
            </div>
        <?php } ?>
    </div>
</section>

<?php $conn->close(); ?>
<!-- End Best Seller -->

<!-- Start Filter Bar -->
<div class="filter-bar d-flex flex-wrap align-items-center">

    <div class="pagination">
        <a href="category.php" class="prev-arrow"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
        <a href="category.php" class="active">1</a>
        <a href="category.php">2</a>
        <a href="category.php">3</a>
        <a href="category.php" class="dot-dot"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
        <a href="category.php">6</a>
        <a href="category.php" class="next-arrow"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
    </div>
</div>
<!-- End Filter Bar -->

<!-- Start footer Area -->
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
<!-- End footer Area -->
</body>
<!-- Modal Quick Product View -->
</html>
