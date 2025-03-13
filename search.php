<?php
// Kết nối database
$servername = "localhost";
$username = "root"; // Thay bằng username của bạn
$password = ""; // Nếu có mật khẩu, hãy điền vào đây
$database = "mydatabase"; // Thay bằng tên database của bạn

$conn = new mysqli($servername, $username, $password, $database);

// Xử lý tìm kiếm
$searchResults = [];
if (isset($_GET['query'])) {
    $query = trim($_GET['query']); // Lấy từ khóa tìm kiếm
    $query = $conn->real_escape_string($query); // Chống SQL Injection

    // Truy vấn tìm kiếm sản phẩm
    $sql = "SELECT * FROM sp WHERE tensp LIKE '%$query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }
}

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

    <?php
    // Nếu có kết quả tìm kiếm, hiển thị danh sách sản phẩm
    if (!empty($searchResults)) {
        echo '<section class="search-results">';
        echo '<h2>Kết quả tìm kiếm</h2>';
        echo '<p class="text-center">Tìm thấy <b>' . count($searchResults) . '</b> sản phẩm</p>';
        echo '<div class="row">';
        foreach ($searchResults as $row) {
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="single-product">
                    <img class="img-fluid" src="<?php echo $row['images']; ?>" alt="<?php echo $row['tensp']; ?>">
                    <div class="product-details">
                        <h6><?php echo $row['tensp']; ?></h6>
                        <div class="product-category">
                            <span>Loại: <?php echo $row['idloai']; ?></span>
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
        echo '</div>';
        echo '</section>';
    } else if (isset($_GET['query'])) {
        echo '<p class="text-center">Không tìm thấy sản phẩm nào phù hợp.</p>';
    }
    ?>


    <!-- Kết thúc Khu vực Sản Phẩm Bán Chạy Nhất -->
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
    <script>
        function addToBag() {
            alert("Bạn đã thêm vào giỏ hàng thành công!");
        }
    </script>
    <!-- End footer Area -->
    </body>
</head>
</html>
