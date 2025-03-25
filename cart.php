<?php
// Kết nối cơ sở dữ liệu
$host = '127.0.0.1';
$dbname = 'huydata';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

// Lấy thông tin khách hàng
$customerId = 1; // Giả lập ID khách hàng
$stmt = $pdo->prepare("SELECT diachi, sdt FROM kh WHERE idKH = :idKH");
$stmt->execute(['idKH' => $customerId]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);
$customerName = $customer['diachi'] ?? '12345 Phan Văn Khỏe';
$customerPhone = $customer['sdt'] ?? '0123459992';

// Giả lập ID đơn hàng (giỏ hàng)
$orderId = 1; // Thay bằng logic thực tế để lấy ID đơn hàng từ session hoặc cơ sở dữ liệu

// Xử lý xóa sản phẩm khỏi giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $idCTDH = $_POST['idCTDH'] ?? 0;
    try {
        $stmt = $pdo->prepare("DELETE FROM ctdonhang WHERE idCTDH = :idCTDH AND iddonhang = :iddonhang");
        $stmt->execute(['idCTDH' => $idCTDH, 'iddonhang' => $orderId]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa sản phẩm: ' . $e->getMessage()]);
    }
    exit();
}

// Lấy danh sách sản phẩm trong giỏ hàng từ bảng `ctdonhang` và `sp`
$stmt = $pdo->prepare("
    SELECT ctdh.idCTDH, ctdh.soluong, ctdh.giathanh, sp.images
    FROM ctdonhang ctdh
    JOIN sp ON ctdh.idsp = sp.idsp
    WHERE ctdh.iddonhang = :iddonhang
");
$stmt->execute(['iddonhang' => $orderId]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .delete-btn {
            background-color: #ff4d4d; /* Red background */
            color: white; /* White text */
            border: none; /* Remove border */
            padding: 8px 15px; /* Padding for the button */
            font-size: 14px; /* Font size */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s ease; /* Smooth background color transition */
        }

        .delete-btn:hover {
            background-color: #e60000; /* Darker red when hovered */
        }

        .delete-btn:focus {
            outline: none; /* Remove the focus outline */
        }

        .primary-btn1 {
            position: relative;
            overflow: hidden;
            justify-content: center; /* Căn giữa theo chiều ngang */
            color: #fff;
            left: 50%;
            padding: 0 30px;
            line-height: 50px;
            border-radius: 50px;
            display: inline-block;
            text-transform: uppercase;
            font-weight: 500;
            cursor: pointer;
            -webkit-transition: all 0.3s ease 0s;
            -moz-transition: all 0.3s ease 0s;
            -o-transition: all 0.3s ease 0s;
            transition: all 0.3s ease 0s;
            background: -webkit-linear-gradient(90deg, #ffba00 0%, #ff6c00 100%);
            background: -moz-linear-gradient(90deg, #ffba00 0%, #ff6c00 100%);
            background: -o-linear-gradient(90deg, #ffba00 0%, #ff6c00 100%);
            background: linear-gradient(90deg, #ffba00 0%, #ff6c00 100%);
        }

        /* CSS cho phần thông tin khách hàng */
        .customer-info {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .customer-info h3 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .customer-info p {
            margin: 5px 0;
            font-size: 16px;
        }
    </style>
</head>
<body>

<!-- Start Header Area -->
<header class="header_area sticky-header">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light main_box">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <a class="navbar-brand logo_h" href="index.html"><img src="img/logo.png" alt=""></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                    <ul class="nav navbar-nav menu_nav ml-auto">
                        <li class="nav-item active"><a class="nav-link" href="index2.html">Trang chủ</a></li>
                        <li class="nav-item submenu dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                               aria-expanded="false">Sản phẩm</a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a class="nav-link" href="category.html">Adidas</a></li>
                                <li class="nav-item"><a class="nav-link" href="category1.html">Vans</a></li>
                                <li class="nav-item"><a class="nav-link" href="category2.html">Nike</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="checkout.php">Thanh toán</a></li>
                    </ul>
                    <!-- Search Input Box -->
                    <input type="checkbox" id="search-toggle" class="search-toggle" hidden>
                    <div class="search_input">
                        <form id="search-form" action="ResultofSearch.html" method="GET" class="d-flex justify-content-between">
                            <input type="text" class="search-input" name="query" placeholder="Tìm kiếm" required>
                            <button type="submit" class="search-btn">
                                <span class="lnr lnr-magnifier"></span>
                            </button>
                            <label for="search-toggle" class="lnr lnr-cross" title="Close Search"></label>
                        </form>
                    </div>

                    <!-- Search Icon and Cart -->
                    <ul class="nav navbar-nav navbar-right">
                        <li class="nav-item"><a href="cart.php" class="cart"><span class="ti-bag"></span></a></li>
                        <li class="nav-item">
                            <!-- Search Button with Magnifier Icon -->
                            <label for="search-toggle" class="search-icon">
                                <span class="lnr lnr-magnifier"></span>
                            </label>
                        </li>
                    </ul>

                    <!-- User Dropdown -->
                    <ul class="nav navbar-nav navbar-right">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle user-btn" href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="username">Người dùng</span>
                                <span class="lnr lnr-user"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="User.html">Thông tin người dùng</a>
                                <a class="dropdown-item" href="confirmation.html">Lịch sử giao dịch</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="index.html">Đăng xuất</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
<!-- End Header Area -->

<!-- Start Banner Area -->
<section class="banner-area organic-breadcrumb">
    <div class="container">
        <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
            <div class="col-first">
                <h1>Giỏ hàng</h1>
                <nav class="d-flex align-items-center">
                    <a href="index.html">Trang chủ<span class="lnr lnr-arrow-right"></span></a>
                    <a href="category.html">Giỏ hàng</a>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- End Banner Area -->

<!--================Cart Area =================-->
<section class="cart_area">
    <div class="container">
        <div class="cart_inner">
            <!-- Thông tin khách hàng -->
            <div class="customer-info">
                <h3>Thông tin khách hàng</h3>
                <p><strong>Tên khách hàng:</strong> <?php echo htmlspecialchars($customerName); ?></p>
                <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($customerPhone); ?></p>
                <p><strong>Email:</strong> phanvankhoe@example.com</p>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Sản phẩm</th>
                        <th scope="col">Giá</th>
                        <th scope="col">Số lượng</th>
                        <th scope="col">Tổng</th>
                        <th scope="col">Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($cartItems)): ?>
                        <tr>
                            <td colspan="5" class="text-center">Giỏ hàng của bạn đang trống.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cartItems as $item): ?>
                            <tr data-idctdh="<?php echo htmlspecialchars($item['idCTDH']); ?>">
                                <td>
                                    <div class="media">
                                        <div class="d-flex">
                                            <img src="<?php echo htmlspecialchars($item['images']); ?>" alt="Sản phẩm">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <h5><?php echo number_format($item['giathanh'], 0, ',', '.') . 'Đ'; ?></h5>
                                </td>
                                <td>
                                    <div class="product_count">
                                        <input type="number" name="qty" value="<?php echo htmlspecialchars($item['soluong']); ?>" min="1" class="input-text qty">
                                    </div>
                                </td>
                                <td>
                                    <h5><?php echo number_format($item['giathanh'] * $item['soluong'], 0, ',', '.') . 'Đ'; ?></h5>
                                </td>
                                <td>
                                    <!-- Delete button -->
                                    <button class="delete-btn" onclick="deleteRow(this)">Xóa</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <a class="primary-btn1" href="checkout.php">Tiến hành thanh toán</a>
        </div>
    </div>
</section>
<!--================End Cart Area =================-->

<!-- Start Footer Area -->
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
                    <h6>Liên Hệ Với Chúng Tôi</h6>
                    <p>ĐH Sài Gòn, <br>TP.HCM, VietNam</p>
                    <p>
                        <span class="lnr lnr-phone"></span> +01 234 567 89<br>
                        <span class="lnr lnr-envelope"></span> support@HKTC.com
                    </p>
                </div>
            </div>
        </div>
        <div class="row footer-bottom d-flex justify-content-between align-items-center">
            <p class="footer-text m-0 col-lg-6 col-md-6">
                2024 © Mọi quyền được bảo lưu | Mẫu này được tạo với <i class="fa fa-heart" aria-hidden="true"></i> by
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
<!-- End Footer Area -->

<!-- Bootstrap and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function deleteRow(button) {
        let row = button.closest('tr');
        let idCTDH = row.dataset.idctdh;

        // Gửi yêu cầu xóa đến server
        fetch('cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'delete_item=1&idCTDH=' + idCTDH
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Bạn đã xóa sản phẩm ra khỏi giỏ hàng.");
                    row.remove();
                } else {
                    alert("Có lỗi xảy ra khi xóa sản phẩm: " + (data.message || "Không xác định"));
                }
            })
            .catch(error => {
                alert("Có lỗi xảy ra: " + error);
            });
    }
</script>
</body>
</html>
