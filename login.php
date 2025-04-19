<?php
session_start(); // Bắt đầu session

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";  // Thay bằng tên người dùng của bạn
$password = "";      // Thay bằng mật khẩu của bạn
$dbname = "test";  // Thay bằng tên cơ sở dữ liệu của bạn

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý khi người dùng gửi biểu mẫu đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        // Truy vấn người dùng theo email, không kiểm tra status ở đây
        $query = $conn->prepare("SELECT idKH, email, password, tenkh, status FROM kh WHERE email = ?");
        $query->bind_param('s', $email);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Kiểm tra status
            if ($user['status'] != 1) {
                echo "<script>alert('Tài khoản đã bị khóa!');</script>";
            } else {
                // Kiểm tra mật khẩu
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['idKH'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['username'] = $user['tenkh'];
                    header("Location: index.php");
                    exit();
                } else {
                    echo "<script>alert('Sai email hoặc mật khẩu!');</script>";
                }
            }
        } else {
            echo "<script>alert('Sai email hoặc mật khẩu!');</script>";
        }
    } else {
        echo "<script>alert('Vui lòng nhập đầy đủ email và mật khẩu!');</script>";
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

    <!-- CSS -->
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/nouislider.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/search.css">
    <!--start header-->
    <?php include 'header.php'; ?>
    <!-- end header -->
</head>

<body>


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
                <h1>Đăng Nhập/Đăng Ký</h1>
                <nav class="d-flex align-items-center">
                    <a href="index.html">Trang chủ<span class="lnr lnr-arrow-right"></span></a>
                    <a href="category.html">Đăng Nhập/Đăng Ký</a>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- End Banner Area -->

<!--================Login Box Area =================-->
<section class="login_box_area section_gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="login_box_img">
                    <img class="img-fluid" src="img/login.jpg" alt="">
                    <div class="hover">
                        <h4>Bạn mới đến với trang web của chúng tôi?</h4>
                        <p>Có những tiến bộ đang được thực hiện trong khoa học và công nghệ mỗi ngày, và một ví dụ điển hình cho điều này là</p>
                        <a class="primary-btn" href="register.php">Đăng Kí Tài Khoản</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="login_form_inner">
                    <h3>Đăng Nhập Mua Hàng</h3>
                    <form class="row login_form" action="login.php" method="POST" id="contactForm" novalidate="novalidate">
                        <div class="col-md-12 form-group">
                            <input type="text" class="form-control" id="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="col-md-12 form-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Mật Khẩu" required>
                        </div>
                        <div class="col-md-12 form-group">
                            <button type="submit" class="primary-btn">Đăng Nhập</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Login Box Area =================-->

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

</html>
