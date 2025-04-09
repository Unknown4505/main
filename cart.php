<?php
session_start(); // Bắt đầu session

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
// Cấu hình kết nối CSDL
$host = '127.0.0.1';
$dbname = 'test';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

// Lấy ID khách hàng từ session (giả sử nếu chưa có thì mặc định là 1)
$customerId = $_SESSION['user_id'] ?? 1;

// Xử lý thêm sản phẩm vào giỏ hàng với số lượng mặc định là 1
if (isset($_GET['add'])) {
    $idsp = intval($_GET['add']);

    // Kiểm tra số lượng tồn kho
    $stmt = $pdo->prepare("SELECT soluong FROM sp WHERE idsp = :idsp FOR UPDATE");
    $stmt->execute(['idsp' => $idsp]);
    $availableQuantity = $stmt->fetchColumn();

    if ($availableQuantity === false || $availableQuantity < 1) {
        echo "<script>alert('Sản phẩm đã hết hàng!'); window.location.href='cart.php';</script>";
        exit();
    }

    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE idKH = :idKH AND idsp = :idsp");
    $stmt->execute(['idKH' => $customerId, 'idsp' => $idsp]);
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);

    $pdo->beginTransaction();
    try {
        if (!$exists) {
            // Nếu chưa có, thêm sản phẩm với số lượng mặc định là 1
            $stmt = $pdo->prepare("INSERT INTO cart (idKH, idsp, quantity) VALUES (:idKH, :idsp, 1)");
            $stmt->execute(['idKH' => $customerId, 'idsp' => $idsp]);

            // Trừ số lượng tồn kho
            $stmt = $pdo->prepare("UPDATE sp SET soluong = soluong - 1 WHERE idsp = :idsp");
            $stmt->execute(['idsp' => $idsp]);
        } else {
            // Nếu đã có, kiểm tra số lượng tồn kho trước khi tăng
            $newQuantity = $exists['quantity'] + 1;
            if ($availableQuantity < 1) {
                echo "<script>alert('Số lượng tồn kho không đủ!'); window.location.href='cart.php';</script>";
                $pdo->rollBack();
                exit();
            }
            $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE idcart = :idcart");
            $stmt->execute(['idcart' => $exists['idcart']]);

            // Trừ số lượng tồn kho
            $stmt = $pdo->prepare("UPDATE sp SET soluong = soluong - 1 WHERE idsp = :idsp");
            $stmt->execute(['idsp' => $idsp]);
        }
        $pdo->commit();
        echo "<script>alert('Đã thêm vào giỏ hàng!'); window.location.href='cart.php';</script>";
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "<script>alert('Lỗi khi thêm vào giỏ hàng: " . $e->getMessage() . "'); window.location.href='cart.php';</script>";
    }
}

// Xử lý cập nhật số lượng sản phẩm trong giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $idcart = $_POST['idcart'];
    $quantity = intval($_POST['quantity']); // Không sử dụng max(1, ...) để cho phép 0

    $pdo->beginTransaction();
    try {
        // Lấy thông tin hiện tại từ giỏ hàng
        $stmt = $pdo->prepare("SELECT idsp, quantity FROM cart WHERE idcart = :idcart AND idKH = :idKH");
        $stmt->execute(['idcart' => $idcart, 'idKH' => $customerId]);
        $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cartItem) {
            // Kiểm tra tồn kho hiện tại
            $stmt = $pdo->prepare("SELECT soluong FROM sp WHERE idsp = :idsp FOR UPDATE");
            $stmt->execute(['idsp' => $cartItem['idsp']]);
            $availableQuantity = $stmt->fetchColumn();

            $currentTotalQuantity = $cartItem['quantity']; // Số lượng hiện tại trong giỏ
            $newTotalQuantity = $quantity; // Số lượng mới muốn đặt
            $quantityDiff = $newTotalQuantity - $currentTotalQuantity;

            // Kiểm tra tồn kho trước khi cập nhật
            if ($newTotalQuantity > 0 && $availableQuantity + $currentTotalQuantity < $newTotalQuantity) {
                echo json_encode(['success' => false, 'message' => 'Số lượng vượt quá tồn kho! Tồn kho còn ' . $availableQuantity . ' sản phẩm.']);
                $pdo->rollBack();
                exit();
            }

            if ($newTotalQuantity === 0) {
                // Nếu số lượng = 0, xóa sản phẩm khỏi giỏ hàng
                $stmt = $pdo->prepare("DELETE FROM cart WHERE idcart = :idcart AND idKH = :idKH");
                $stmt->execute(['idcart' => $idcart, 'idKH' => $customerId]);

                // Cộng lại số lượng tồn kho
                $stmt = $pdo->prepare("UPDATE sp SET soluong = soluong + :quantity WHERE idsp = :idsp");
                $stmt->execute(['quantity' => $currentTotalQuantity, 'idsp' => $cartItem['idsp']]);
            } else {
                // Cập nhật số lượng nếu > 0
                $stmt = $pdo->prepare("UPDATE cart SET quantity = :quantity WHERE idcart = :idcart AND idKH = :idKH");
                $stmt->execute(['quantity' => $quantity, 'idcart' => $idcart, 'idKH' => $customerId]);

                // Cập nhật tồn kho
                $stmt = $pdo->prepare("UPDATE sp SET soluong = soluong - :diff WHERE idsp = :idsp");
                $stmt->execute(['diff' => $quantityDiff, 'idsp' => $cartItem['idsp']]);
            }

            $pdo->commit();
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm trong giỏ hàng!']);
            $pdo->rollBack();
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật số lượng: ' . $e->getMessage()]);
    }
    exit();
}

// Xử lý xóa sản phẩm khỏi giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $idcart = $_POST['idcart'];

    $stmt = $pdo->prepare("SELECT idsp, quantity FROM cart WHERE idcart = :idcart AND idKH = :idKH");
    $stmt->execute(['idcart' => $idcart, 'idKH' => $customerId]);
    $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cartItem) {
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("DELETE FROM cart WHERE idcart = :idcart AND idKH = :idKH");
            $stmt->execute(['idcart' => $idcart, 'idKH' => $customerId]);

            $stmt = $pdo->prepare("UPDATE sp SET soluong = soluong + :quantity WHERE idsp = :idsp");
            $stmt->execute(['quantity' => $cartItem['quantity'], 'idsp' => $cartItem['idsp']]);

            $pdo->commit();
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa sản phẩm: ' . $e->getMessage()]);
        }
    }
    exit();
}

// Xử lý thanh toán toàn bộ giỏ hàng
if (isset($_POST['checkout_all'])) {
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE idKH = :idKH");
    $stmt->execute(['idKH' => $customerId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($cartItems) > 0) {
        $_SESSION['cart_items'] = $cartItems;
        header("Location: checkout.php");
        exit();
    } else {
        echo "<script>alert('Giỏ hàng trống!'); window.location.href='cart.php';</script>";
    }
}

// Lấy danh sách sản phẩm trong giỏ hàng
$stmt = $pdo->prepare("
    SELECT cart.idcart, sp.idsp, sp.tensp, sp.giathanh, sp.images, sp.soluong AS available_quantity, cart.quantity
    FROM cart
    JOIN sp ON cart.idsp = sp.idsp
    WHERE cart.idkh = :idkh
");
$stmt->execute(['idkh' => $customerId]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tính tổng tiền
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['giathanh'] * $item['quantity'];
}

// Hiển thị giao diện
include 'header.php';
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js" xmlns="http://www.w3.org/1999/html">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="img/fav.png">
    <meta name="author" content="CodePixar">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta charset="UTF-8">
    <title>Karma Shop - Giỏ hàng</title>

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
        body { font-family: 'Roboto', sans-serif; }
        .delete-btn {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 15px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .delete-btn:hover { background-color: #e60000; }
        .delete-btn:focus { outline: none; }
        .primary-btn1 {
            position: relative;
            overflow: hidden;
            justify-content: center;
            color: #fff;
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
        .customer-info {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .customer-info h3 { font-size: 20px; font-weight: bold; margin-bottom: 15px; }
        .customer-info p { margin: 5px 0; font-size: 16px; }
        .total-amount { font-size: 18px; font-weight: bold; margin-top: 20px; }
        .checkout-btn, .checkout-all-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 15px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-left: 10px;
        }
        .checkout-btn:hover, .checkout-all-btn:hover { background-color: #45a049; }
        .checkout-btn:focus, .checkout-all-btn:focus { outline: none; }
        .stock-info {
            color: #ff0000;
            font-size: 12px;
            margin-top: 5px;
        }
        .out-of-stock {
            background-color: #ffebee;
            color: #c62828;
        }
        .quantity {
            width: 60px;
            padding: 5px;
        }
    </style>
</head>

<body>

<!-- Header Area -->
<?php include 'header.php'; ?>

<!-- Banner Area -->
<section class="banner-area organic-breadcrumb">
    <div class="container">
        <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
            <div class="col-first"><h1>Giỏ hàng</h1><nav class="d-flex align-items-center"><a href="index.php">Trang chủ<span class="lnr lnr-arrow-right"></span></a><a href="cart.php">Giỏ hàng</a></nav></div>
        </div>
    </div>
</section>

<!-- Cart Area -->
<section>
    <h2>Giỏ hàng của bạn</h2>
    <div class="table-responsive">
        <table class="table">
            <tr>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th>Hành động</th>
                <th>Tồn kho</th>
            </tr>
            <?php foreach ($cartItems as $item): ?>
                <tr data-idcart="<?php echo $item['idcart']; ?>" class="<?php echo $item['available_quantity'] < 1 ? 'out-of-stock' : ''; ?>">
                    <td><img src="<?php echo $item['images']; ?>" width="50"></td>
                    <td><?php echo $item['tensp']; ?></td>
                    <td class="price" data-price="<?php echo $item['giathanh']; ?>">
                        <?php echo number_format($item['giathanh'], 0, ',', '.') . ' VNĐ'; ?>
                    </td>
                    <td>
                        <input type="number" class="quantity" value="<?php echo $item['quantity']; ?>" min="0" max="<?php echo $item['available_quantity']; ?>" onchange="updateQuantity(this, <?php echo $item['idcart']; ?>)">
                    </td>
                    <td class="total-price">
                        <?php echo number_format($item['giathanh'] * $item['quantity'], 0, ',', '.') . ' VNĐ'; ?>
                    </td>
                    <td>
                        <button onclick="deleteItem(<?php echo $item['idcart']; ?>)" class="delete-btn">Xóa</button>
                    </td>
                    <td>
                        <span class="stock-info">Còn: <?php echo $item['available_quantity']; ?> sản phẩm</span>
                        <?php if ($item['available_quantity'] < 1): ?>
                            <p class="stock-info">Hết hàng</p>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <h3>Tổng tiền: <span id="total-price"><?php echo number_format($total, 0, ',', '.') . ' VNĐ'; ?></span></h3>
        <form method="POST" style="margin-top: 20px;">
            <button type="submit" name="checkout_all" class="checkout-all-btn">Thanh toán tất cả</button>
        </form>
    </div>
</section>

<!-- Footer Area -->
<footer class="footer-area section_gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6"><div class="single-footer-widget"><h6>Về Chúng Tôi</h6><p>“Kể từ lúc thành lập vào năm 2012, Karma luôn được khách hàng đánh giá là một trong những cửa hàng giày chất lượng cao tại Việt Nam. Hiện tại, Karma vẫn tiếp tục duy trì chất lượng dịch vụ và sản phẩm tốt để gìn giữ sự hài lòng của khách hàng.”</p></div></div>
            <div class="col-lg-4 col-md-6 col-sm-6"><div class="single-footer-widget"><h6>Bảng tin</h6><p>Luôn cập nhật thông tin mới nhất của chúng tôi</p><div class=""><form target="_blank" novalidate="true" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&id=92a4423d01" method="get" class="form-inline"><div class="form-group lbel-inline"><input type="email" class="form-control" name="EMAIL" placeholder="Nhập email" required></div><button class="btn btn-default"><span class="lnr lnr-arrow-right"></span></button><div style="position: absolute; left: -5000px;"><input type="text" name="b_1462626880ade1ac87bd9c93a_92a4423d01" tabindex="-1" value=""></div></form></div><div class="info"></div></div></div>
            <div class="col-lg-2 col-md-6 col-sm-6"><div class="single-footer-widget"><h6>Instagram</h6><div class="instagram-row"><a href="#"><img src="img/i1.jpg" alt=""></a><a href="#"><img src="img/i2.jpg" alt=""></a><a href="#"><img src="img/i3.jpg" alt=""></a><a href="#"><img src="img/i4.jpg" alt=""></a><a href="#"><img src="img/i5.jpg" alt=""></a><a href="#"><img src="img/i6.jpg" alt=""></a></div></div></div>
            <div class="col-lg-3 col-md-6 col-sm-6"><div class="single-footer-widget"><h6>Liên Hệ Với Chúng Tôi</h6><p>ĐH Sài Gòn, <br>TP.HCM, VietNam</p><p><span class="lnr lnr-phone"></span> +01 234 567 89<br><span class="lnr lnr-envelope"></span> support@HKTC.com</p></div></div>
        </div>
        <div class="row footer-bottom d-flex justify-content-between align-items-center">
            <p class="footer-text m-0 col-lg-6 col-md-6">2024 © Mọi quyền được bảo lưu | Mẫu này được tạo với <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">HKTC</a></p>
            <div class="col-lg-6 col-md-6 footer-social"><a href="#"><i class="fa fa-facebook"></i></a><a href="#"><i class="fa fa-twitter"></i></a><a href="#"><i class="fa fa-dribbble"></i></a><a href="#"><i class="fa fa-behance"></i></a></div>
        </div>
    </div>
</footer>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function deleteItem(idcart) {
        if (!confirm("Bạn có chắc muốn xóa sản phẩm này?")) return;
        fetch('cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ delete_item: 1, idcart: idcart })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`tr[data-idcart='${idcart}']`).remove();
                    updateTotal();
                    location.reload(); // Tải lại trang để cập nhật tồn kho
                } else {
                    alert("Có lỗi xảy ra!");
                }
            });
    }

    function updateQuantity(input, idcart) {
        let quantity = parseInt(input.value);
        let maxQuantity = parseInt(input.getAttribute('max')); // Lấy max từ thuộc tính HTML

        // Không cho phép số lượng âm hoặc vượt quá tồn kho
        if (quantity > maxQuantity) {
            alert('Số lượng vượt quá tồn kho! Số lượng tối đa là ' + maxQuantity);
            input.value = maxQuantity;
            quantity = maxQuantity;
        } else if (quantity < 0) {
            input.value = 0;
            quantity = 0;
        }

        fetch('cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ update_quantity: 1, idcart: idcart, quantity: quantity })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (quantity === 0) {
                        document.querySelector(`tr[data-idcart='${idcart}']`).remove(); // Xóa dòng nếu quantity = 0
                    }
                    updateTotal();
                    location.reload(); // Tải lại trang để cập nhật tồn kho và giao diện
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi cập nhật số lượng!');
                    input.value = parseInt(input.getAttribute('data-original-quantity')); // Khôi phục giá trị cũ
                    updateTotal();
                }
            });
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('tr[data-idcart]').forEach(row => {
            let price = parseFloat(row.querySelector('.price').dataset.price);
            let quantity = parseInt(row.querySelector('.quantity').value);
            total += price * quantity;
        });
        document.getElementById('total-price').textContent = total.toLocaleString('vi-VN') + " VNĐ";
    }

    // Lưu giá trị ban đầu của quantity khi tải trang
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.quantity').forEach(input => {
            input.setAttribute('data-original-quantity', input.value);
        });
    });
</script>
</body>
</html>
