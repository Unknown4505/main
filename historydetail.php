<?php
// Kết nối cơ sở dữ liệu
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy iddonhang từ URL
if (!isset($_GET['iddonhang']) || empty($_GET['iddonhang'])) {
    echo "<p>Không tìm thấy mã đơn hàng.</p>";
    exit();
}

$iddonhang = $_GET['iddonhang'];

// Truy vấn thông tin đơn hàng và khách hàng
$sql_order = "SELECT dh.iddonhang, dh.ngaymua, dh.diachi, dh.sdt, dh.ghichu, dh.phuongthucthanhtoan, kh.tenkh
              FROM donhang dh
              JOIN kh ON dh.idKH = kh.idKH
              WHERE dh.iddonhang = ?";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("i", $iddonhang);
$stmt_order->execute();
$result_order = $stmt_order->get_result();

if ($result_order->num_rows == 0) {
    echo "<p>Không tìm thấy đơn hàng với mã này.</p>";
    exit();
}

$order = $result_order->fetch_assoc();

// Truy vấn chi tiết sản phẩm trong đơn hàng
$sql_details = "SELECT sp.tensp, ctdh.soluong, ctdh.giathanh
                FROM ctdonhang ctdh
                JOIN sp ON ctdh.idsp = sp.idsp
                WHERE ctdh.iddonhang = ?";
$stmt_details = $conn->prepare($sql_details);
$stmt_details->bind_param("i", $iddonhang);
$stmt_details->execute();
$result_details = $stmt_details->get_result();

// Tính tổng tiền
$total = 0;
$products = [];
while ($row = $result_details->fetch_assoc()) {
    $products[] = $row;
    $total += $row['soluong'] * $row['giathanh'];
}
include 'header.php'
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="CodePixar">
    <meta charset="UTF-8">
    <title>Karma Shop</title>
    <link rel="shortcut icon" href="img/fav.png">
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/nouislider.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/search.css">
    <style>
        .container1 {
            width: 100%;
            max-width: 800px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-left: auto;
            margin-right: auto;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .info-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .info-group label {
            font-weight: bold;
            color: #555;
            width: 30%;
        }
        .info-group .info {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f8f8f8;
            color: #333;
            width: 65%;
            font-size: 14px;
        }
        .product-info {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .product-info label {
            font-weight: bold;
            color: #333;
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
        }
        .product-info .info {
            display: flex;
            justify-content: space-between;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f8f8f8;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 14px;
        }
        .total-info {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-top: 20px;
            font-size: 16px;
        }
        .total-info span {
            color: #333;
        }

        footer {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
        }

        footer h6 {
            color: #ff914d;
        }

        footer p,
        footer a {
            color: #bbb;
            font-size: 14px;
            line-height: 1.8;
        }

        footer a:hover {
            color: #ff914d;
        }
    </style>
</head>

<body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<section class="banner-area organic-breadcrumb">
        <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
            <div class="col-first">
                <h1>Chi tiết</h1>
                <nav class="d-flex align-items-center">
                    <a href="index.php">Trang Chủ<span class="lnr lnr-arrow-right"></span></a>
                    <a href="confirmation.php">Lịch sử giao dịch</a>
                </nav>
            </div>
    </div>
</section>
<!-- Product Details Section -->
<section>
<div class="container1">
    <h2>Chi Tiết Hóa Đơn</h2>
    <!-- Thông tin khách hàng -->
    <div class="info-group">
        <label>Tên khách hàng:</label>
        <div class="info"><?php echo htmlspecialchars($order['tenkh']); ?></div>
    </div>
    <div class="info-group">
        <label>Số điện thoại:</label>
        <div class="info"><?php echo htmlspecialchars($order['sdt']); ?></div>
    </div>
    <div class="info-group">
        <label>Địa chỉ nhận hàng:</label>
        <div class="info"><?php echo htmlspecialchars($order['diachi']); ?></div>
    </div>
    <div class="info-group">
        <label>Phương thức thanh toán:</label>
        <div class="info"><?php echo htmlspecialchars($order['phuongthucthanhtoan']); ?></div>
    </div>
    <div class="info-group">
        <label>Ghi Chú:</label>
        <textarea disabled rows="3" class="info" style="font-family: 'Roboto', sans-serif;"><?php echo htmlspecialchars($order['ghichu']); ?></textarea>
    </div>
    <div class="info-group">
        <label>Mã giao dịch:</label>
        <div class="info"><?php echo htmlspecialchars($order['iddonhang']); ?></div>
    </div>
    <div class="info-group">
        <label>Ngày đặt hàng:</label>
        <div class="info"><?php echo htmlspecialchars($order['ngaymua']); ?></div>
    </div>

    <!-- Sản phẩm và số lượng -->
    <div class="product-info">
        <label>Danh sách sản phẩm:</label>
        <?php foreach ($products as $product): ?>
            <div class="info">
                <span><?php echo htmlspecialchars($product['tensp']); ?></span>
                <span>x<?php echo htmlspecialchars($product['soluong']); ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Giá tiền thanh toán -->
    <div class="total-info">
        <span>Tổng tiền:</span>
        <span><?php echo number_format($total, 0, ',', '.') . ' VND'; ?></span>
    </div>
</div>
</div>
</section>
<?php
// Đóng kết nối
$stmt_order->close();
$stmt_details->close();
$conn->close();
?>

<!-- Footer Area -->
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
</body>
</html>
