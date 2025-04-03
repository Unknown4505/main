<?php
session_start(); // Bắt đầu session

// Cấu hình kết nối CSDL
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

// Lấy ID khách hàng từ session
$customerId = $_SESSION['user_id'] ?? 1;

// Lấy idcart từ URL
$idcart = isset($_GET['idcart']) ? intval($_GET['idcart']) : null;

if (!$idcart) {
    die("Không tìm thấy giỏ hàng để thanh toán.");
}

// Kiểm tra xem giỏ hàng có thuộc về khách hàng không
$stmt = $pdo->prepare("SELECT * FROM cart WHERE idcart = :idcart AND idKH = :idKH");
$stmt->execute(['idcart' => $idcart, 'idKH' => $customerId]);
$cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cartItem) {
    die("Giỏ hàng không hợp lệ hoặc không thuộc về bạn.");
}

// Lấy thông tin sản phẩm từ bảng `sp`
$stmt = $pdo->prepare("SELECT sp.tensp, sp.giathanh, sp.images FROM sp WHERE idsp = :idsp");
$stmt->execute(['idsp' => $cartItem['idsp']]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Không tìm thấy sản phẩm.");
}

// Tính tổng tiền cho giỏ hàng này
$total = $product['giathanh'] * $cartItem['quantity'];

// Lấy thông tin khách hàng từ bảng `kh`
$stmt = $pdo->prepare("SELECT * FROM kh WHERE idKH = :idKH");
$stmt->execute(['idKH' => $customerId]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

// Biến để kiểm tra xem có hiển thị modal hay không
$showSuccessModal = false;

// Xử lý khi người dùng nhấn "Đặt hàng"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['number'] ?? '';
    $address = $_POST['address'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $paymentMethod = $_POST['payment_method'] ?? 'Tiền mặt'; // Mặc định là "Tiền mặt" nếu không chọn

    // Tạo đơn hàng mới trong bảng `orders` (giả sử bạn đã tạo bảng này như trong hướng dẫn trước)
    try {
        $stmt = $pdo->prepare("INSERT INTO orders (idKH, idcart, total_amount, order_date, status, phuongthucthanhtoan) VALUES (:idKH, :idcart, :total_amount, NOW(), 'paid', :phuongthucthanhtoan)");
        $stmt->execute([
            'idKH' => $customerId,
            'idcart' => $idcart,
            'total_amount' => $total,
            'phuongthucthanhtoan' => $paymentMethod
        ]);

        // Cập nhật thông tin khách hàng trong bảng `kh`
        $stmt = $pdo->prepare("UPDATE kh SET diachi = :diachi, sdt = :sdt WHERE idKH = :idKH");
        $stmt->execute(['diachi' => $address, 'sdt' => $phone, 'idKH' => $customerId]);

        // Xóa giỏ hàng sau khi thanh toán thành công
        $stmt = $pdo->prepare("DELETE FROM cart WHERE idcart = :idcart");
        $stmt->execute(['idcart' => $idcart]);

        $showSuccessModal = true;
    } catch (PDOException $e) {
        echo "Lỗi khi đặt hàng: " . $e->getMessage();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="img/fav.png">
    <meta name="author" content="CodePixar">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta charset="UTF-8">
    <title>Cửa hàng Karma - Thanh toán</title>

    <!-- Thêm Bootstrap và các thư viện cần thiết -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/nouislider.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/ResultOfSearch.css">
    <link rel="stylesheet" href="css/search.css">

    <style>
        .primary-btn {
            padding: 8px 20px;
            font-size: 14px;
            border-radius: 5px;
            border: none;
            transition: background-color 0.3s ease;
        }
        .primary-btn:hover {
            background-color: #f06c4d;
            cursor: pointer;
        }
        .order-summary {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
        }
        .order-summary h4 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .order-summary .product-item {
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .order-summary .total {
            font-weight: bold;
            font-size: 16px;
            margin-top: 10px;
        }
        .order-btn {
            background-color: #ff4d4d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            font-size: 16px;
            width: 100%;
            margin-top: 20px;
        }
        .order-btn:hover {
            background-color: #e63939;
        }
        /* CSS cho modal */
        .modal-content {
            text-align: center;
        }
        .modal-header {
            border-bottom: none;
        }
        .modal-title {
            color: #28a745;
            font-size: 24px;
            font-weight: bold;
        }
        .modal-body p {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .modal-footer {
            border-top: none;
            justify-content: center;
        }
        .modal-footer .btn {
            background-color: #ff4d4d;
            color: white;
            border-radius: 20px;
            padding: 10px 20px;
        }
        .modal-footer .btn:hover {
            background-color: #e63939;
        }
    </style>
</head>

<body>

<!-- Start Header Area -->
<header class="header_area sticky-header">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light main_box">
            <div class="container">
                <a class="navbar-brand logo_h" href="index.html"><img src="img/logo.png" alt=""></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
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
                    <input type="checkbox" id="search-toggle" class="search-toggle" hidden>
                    <div class="search_input">
                        <form id="search-form" action="ResultOfSearch.html" method="GET" class="d-flex justify-content-between">
                            <input type="text" class="search-input" name="query" placeholder="Tìm kiếm" required>
                            <button type="submit" class="search-btn">
                                <span class="lnr lnr-magnifier"></span>
                            </button>
                            <label for="search-toggle" class="lnr lnr-cross" title="Đóng tìm kiếm"></label>
                        </form>
                    </div>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="nav-item"><a href="cart.php" class="cart"><span class="ti-bag"></span></a></li>
                        <li class="nav-item">
                            <label for="search-toggle" class="search-icon">
                                <span class="lnr lnr-magnifier"></span>
                            </label>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle user-btn" href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="username">Người dùng</span>
                                <span class="lnr lnr-user"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="User.html">Thông tin người dùng</a>
                                <a class="dropdown-item" href="confirmation.php">Lịch sử giao dịch</a>
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
                <h1>Thanh toán</h1>
                <nav class="d-flex align-items-center">
                    <a href="index.html">Trang chủ<span class="lnr lnr-arrow-right"></span></a>
                    <a href="checkout.php">Thanh toán</a>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- End Banner Area -->

<!--================Checkout Area =================-->
<section class="checkout_area section_gap">
    <div class="container">
        <div class="row">
            <!-- Bên trái: Thông tin người nhận và Chọn địa chỉ giao hàng -->
            <div class="col-lg-6">
                <h3>Thông tin người nhận</h3>
                <form class="row contact_form" action="" method="post" novalidate="novalidate" id="checkoutForm">
                    <div class="col-md-12 form-group">
                        <label for="name">Tên người nhận:</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Tên người nhận"
                               value="<?php echo htmlspecialchars($customer['diachi'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="number">Số điện thoại:</label>
                        <input type="text" class="form-control" id="number" name="number" placeholder="Số điện thoại"
                               value="<?php echo htmlspecialchars($customer['sdt'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="notes">Ghi chú giao hàng (nếu có):</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3"
                                  placeholder="Nhập ghi chú giao hàng"></textarea>
                    </div>

                    <!-- Thêm phần chọn phương thức thanh toán -->
                    <div class="col-md-12 form-group">
                        <h3>Phương thức thanh toán</h3>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="Tiền mặt" checked>
                            <label class="form-check-label" for="payment_cash">
                                Tiền mặt
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_transfer" value="Chuyển khoản">
                            <label class="form-check-label" for="payment_transfer">
                                Chuyển khoản
                            </label>
                        </div>
                    </div>

                    <h3>Chọn địa chỉ giao hàng</h3>
                    <div class="col-md-12 form-group">
                        <label for="address">Địa chỉ giao hàng mặc định:</label>
                        <input type="text" class="form-control" id="address" name="address"
                               value="<?php echo htmlspecialchars($customer['diachi'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-12 form-group">
                        <input type="checkbox" id="new-address" name="new-address">
                        <label for="new-address">Nhập địa chỉ giao hàng mới</label>
                        <input type="text" class="form-control" id="new-address-input" name="new-address-input"
                               placeholder="Nhập địa chỉ giao hàng mới" style="display: none;">
                    </div>

                    <input type="hidden" name="place_order" value="1">
                </form>
            </div>

            <!-- Bên phải: Tóm tắt hóa đơn -->
            <div class="col-lg-6">
                <div class="order-summary">
                    <h4>Tóm tắt hóa đơn</h4>
                    <div class="product-list">
                        <?php if (!$cartItem): ?>
                            <p>Không có sản phẩm nào trong giỏ hàng.</p>
                        <?php else: ?>
                            <div class="product-item">
                                <p><strong>Tên sản phẩm:</strong> <?php echo htmlspecialchars($product['tensp']); ?></p>
                                <p><strong>Số lượng:</strong> <?php echo htmlspecialchars($cartItem['quantity']); ?></p>
                                <p><strong>Giá:</strong> <?php echo number_format($product['giathanh'], 0, ',', '.') . 'đ'; ?></p>
                                <p><strong>Hình ảnh:</strong> <img src="<?php echo htmlspecialchars($product['images']); ?>" width="50"></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="total">
                        Tổng tiền: <?php echo number_format($total, 0, ',', '.') . 'đ'; ?>
                    </div>
                    <hr>
                    <div class="summary-details">
                        <p><strong>Tên người nhận:</strong> <?php echo htmlspecialchars($customer['diachi'] ?? ''); ?></p>
                        <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($customer['sdt'] ?? ''); ?></p>
                        <p><strong>Địa chỉ giao hàng:</strong> <?php echo htmlspecialchars($customer['diachi'] ?? ''); ?></p>
                        <p><strong>Phương thức thanh toán:</strong> Tiền mặt</p> <!-- Mặc định, sẽ cập nhật khi chọn -->
                        <p><strong>Tôi đã chấp nhận điều khoản và điều kiện</strong></p>
                    </div>
                    <button type="submit" class="order-btn" form="checkoutForm">Đặt hàng</button>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Checkout Area =================-->

<!-- Modal thông báo đặt hàng thành công -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Thông báo</h5>
            </div>
            <div class="modal-body">
                <p>Bạn đã đặt hàng thành công</p>
            </div>
            <div class="modal-footer">
                <a href="index2.php" class="btn">Quay lại trang chủ</a>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
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
                    <p>ĐH Sài Gòn, <br>TP.HCM, Việt Nam</p>
                    <p>
                        <span class="lnr lnr-phone"></span> +01 234 567 89<br>
                        <span class="lnr lnr-envelope"></span> support@HKTC.com
                    </p>
                </div>
            </div>
        </div>
        <div class="row footer-bottom d-flex justify-content-between align-items-center">
            <p class="footer-text m-0 col-lg-6 col-md-6">
                2024 © Mọi quyền được bảo lưu | Mẫu này được tạo với <i class="fa fa-heart" aria-hidden="true"></i> bởi
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

<!-- Thêm jQuery và Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Hiển thị ô nhập địa chỉ mới khi checkbox được chọn
    document.getElementById('new-address').addEventListener('change', function() {
        const newAddressInput = document.getElementById('new-address-input');
        newAddressInput.style.display = this.checked ? 'block' : 'none';
        if (this.checked) {
            document.getElementById('address').value = ''; // Xóa địa chỉ mặc định khi chọn nhập mới
        } else {
            document.getElementById('address').value = '<?php echo htmlspecialchars($customer['diachi'] ?? ''); ?>'; // Khôi phục địa chỉ mặc định
        }
    });

    // Cập nhật địa chỉ giao hàng trong tóm tắt hóa đơn khi người dùng nhập địa chỉ mới
    document.getElementById('new-address-input').addEventListener('input', function() {
        document.getElementById('address').value = this.value;
    });

    // Cập nhật tóm tắt hóa đơn khi người dùng thay đổi thông tin
    document.getElementById('name').addEventListener('input', function() {
        document.querySelector('.summary-details p:nth-child(1)').innerHTML = '<strong>Tên người nhận:</strong> ' + this.value;
    });
    document.getElementById('number').addEventListener('input', function() {
        document.querySelector('.summary-details p:nth-child(2)').innerHTML = '<strong>Số điện thoại:</strong> ' + this.value;
    });
    document.getElementById('address').addEventListener('input', function() {
        document.querySelector('.summary-details p:nth-child(3)').innerHTML = '<strong>Địa chỉ giao hàng:</strong> ' + this.value;
    });
    document.querySelectorAll('input[name="payment_method"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelector('.summary-details p:nth-child(4)').innerHTML = '<strong>Phương thức thanh toán:</strong> ' + this.value;
        });
    });

    // Hiển thị modal nếu đặt hàng thành công
    <?php if ($showSuccessModal): ?>
    $(document).ready(function() {
        $('#successModal').modal('show');
    });
    <?php endif; ?>
</script>
</body>
</html>
