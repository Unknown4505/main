<?php
session_start();

$servername = "localhost";
$username = "root"; // Tên tài khoản MySQL
$password = ""; // Mật khẩu MySQL
$dbname = "test"; // Tên cơ sở dữ liệu

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Lấy thông tin người dùng từ cơ sở dữ liệu
$stmt = $conn->prepare("SELECT * FROM kh WHERE idKH = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Kiểm tra nếu form được gửi đi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nhận dữ liệu từ form
    $tenkh = $_POST['tenkh'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $sdt = $_POST['sdt'];
    $diachi = $_POST['diachi'];

    // Cập nhật thông tin người dùng
    $update_stmt = $conn->prepare("UPDATE kh SET tenkh = ?, email = ?, dob = ?, sdt = ?, diachi = ? WHERE idKH = ?");
    $update_stmt->bind_param("sssssi", $tenkh, $email, $dob, $sdt, $diachi, $user_id);

    if ($update_stmt->execute()) {
        // Cập nhật thành công, lấy lại dữ liệu người dùng mới nhất
        $stmt = $conn->prepare("SELECT * FROM kh WHERE idKH = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Thông báo thành công
        $success_message = "Cập nhật thông tin thành công!";
    } else {
        // Thông báo lỗi
        $error_message = "Lỗi cập nhật: " . $update_stmt->error;
    }
}

// Đóng kết nối
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="img/fav.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Người dùng</title>
    <link rel="stylesheet" href="css/User.css">
    <!-- Meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
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
    <link rel="stylesheet" href="css/main.css">
    <?php include 'header.php'; ?>
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
        /* Đảm bảo toàn bộ nội dung trang đủ chiều cao */
        body, html {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Nội dung chính của trang */
        .content {
            flex: 1;  /* Nội dung sẽ chiếm toàn bộ không gian trước khi đến footer */
        }

        /* Footer */
        .footer-area {
            background: #222222; /* Màu nền của footer */
            color: #fff;
            padding: 20px 0;
            text-align: center;
            position: relative; /* Đảm bảo footer không bị cố định */
            padding-top: 100px;
        }

        /* Thiết lập khoảng cách với nội dung bên trên */
        .footer-bottom {
            margin-top: auto;
        }
        /* Đặt footer chiếm toàn bộ chiều rộng */
        .footer-area {
            width: 100%;           /* Đảm bảo footer chiếm 100% chiều rộng trang */
            background-color: #333; /* Đặt màu nền cho dễ nhận diện */
            color: grey;           /* Màu chữ */
            padding: 20px 0;        /* Khoảng cách giữa các phần tử trong footer */
            text-align: center;     /* Căn giữa nội dung */
            box-sizing: border-box; /* Đảm bảo padding không làm tăng chiều rộng tổng */
            margin-top: 30px;
        }
        .footer-area {
            position: relative;         /* Đảm bảo vị trí của footer tương đối bình thường */
        }
        .footer-area {
            margin: 0;                /* Loại bỏ margin để tránh khoảng cách thừa */
        }
        .container {
            width: 100%;               /* Đảm bảo container bao quanh footer có chiều rộng đầy đủ */
            margin: 0 auto;            /* Đảm bảo nó căn giữa và không có khoảng cách bên ngoài */
            padding: 0;                /* Loại bỏ padding hoặc margin thừa */
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
        h1 {
            text-align: center;
        }
    </style>
</head>

<body>


<!-- Bootstrap and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<!-- end header -->

<!-- Start User Profile Section -->
<div class="profile-container">
    <div class="profile-header">
        <h1 class="profile-name">Thông tin người dùng</h1>
    </div>
    <div class="profile-details">

        <!-- Hiển thị thông báo thành công hoặc lỗi -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <strong>Thành công!</strong> <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <strong>Lỗi!</strong> <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <!-- Success message -->
        <div id="success-alert" class="alert alert-success" style="display: none;">
            <strong>Thành công!</strong> Đã lưu thay đổi của bạn.
        </div>

        <!-- Start form for editing information -->
        <form action="" method="POST" id="edit-profile-form">
            <div class="profile-info">
                <label for="fullname">Tên đầy đủ</label>
                <input type="text" id="fullname" name="tenkh" value="<?php echo htmlspecialchars($user['tenkh']); ?>">

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">

                <label for="dob">Ngày sinh</label>
                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>">

                <label for="sdt">Số điện thoại</label>
                <input type="text" id="sdt" name="sdt" value="<?php echo htmlspecialchars($user['sdt']); ?>">

                <label for="diachi">Địa chỉ</label>
                <input type="text" id="diachi" name="diachi" value="<?php echo htmlspecialchars($user['diachi']); ?>">

                <button type="submit">Lưu chỉnh sửa</button>
            </div>
        </form>
    </div>
</div>
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
<!-- JavaScript to handle success message -->
