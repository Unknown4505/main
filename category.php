<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "test";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy tên loại từ key trong $_GET
$tenloai = key($_GET);
$idloai = 0;

// Tìm idloai từ tenloai
$stmt = $conn->prepare("SELECT idloai FROM loaisp WHERE tenloai = ?");
$stmt->bind_param("s", $tenloai);
$stmt->execute();
$stmt->bind_result($idloai);
$stmt->fetch();
$stmt->close();

// Phân trang
$perPage = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;

// Đếm tổng số sản phẩm
$countSql = "SELECT COUNT(*) FROM sp WHERE idloai = ? AND ansp = 0";
$countStmt = $conn->prepare($countSql);
$countStmt->bind_param("i", $idloai);
$countStmt->execute();
$countStmt->bind_result($totalProducts);
$countStmt->fetch();
$countStmt->close();

$totalPages = ceil($totalProducts / $perPage);

// Truy vấn sản phẩm giới hạn theo trang
$sql = "SELECT sp.*, loaisp.tenloai FROM sp 
        JOIN loaisp ON sp.idloai = loaisp.idloai 
        WHERE sp.idloai = ? AND sp.ansp = 0 
        LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $idloai, $start, $perPage);
$stmt->execute();
$result = $stmt->get_result();

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

<!-- Hiển thị thông báo tìm kiếm nếu đã bấm lọc -->

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
    <div class="pagination mt-4">
        <?php if ($page > 1): ?>
            <a href="?<?= $tenloai ?>&page=<?= $page - 1 ?>" class="prev-arrow"><i class="fa fa-long-arrow-left"></i></a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?<?= $tenloai ?>&page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?<?= $tenloai ?>&page=<?= $page + 1 ?>" class="next-arrow"><i class="fa fa-long-arrow-right"></i></a>
        <?php endif; ?>
    </div>
</div>
<!-- End Filter Bar -->

<!-- Start footer Area -->
<?php include 'footer.php'?>
<!-- End footer Area -->
</body>
<!-- Modal Quick Product View -->
</html>
