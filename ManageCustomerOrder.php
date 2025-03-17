<?php
// Kết nối database
$servername = "localhost";
$username = "root"; // Thay bằng username của bạn
$password = ""; // Nếu có mật khẩu, hãy điền vào đây
$database = "mydatabase"; // Thay bằng tên database của bạn

$conn = new mysqli($servername, $username, $password, $database);
// Xử lý lọc đơn hàng
$statusFilter = $_GET['status'] ?? 'all';
$fromDate = $_GET['from-date'] ?? '';
$toDate = $_GET['to-date'] ?? '';
$addressFilter = $_GET['address'] ?? 'all';

$query = "SELECT donhang.*, kh.tenkh, kh.sdt FROM donhang 
          LEFT JOIN kh ON donhang.idKH = kh.idKH
          WHERE 1=1";

$params = [];      // Mảng chứa giá trị lọc
$paramTypes = "";  // Chuỗi chứa kiểu dữ liệu bind_param

// Thêm điều kiện lọc trạng thái
if ($statusFilter !== 'all') {
    $query .= " AND donhang.trangthai = ?";
}

if (!empty($fromDate)) {
    $query .= " AND donhang.ngaymua >= ?";
    $paramTypes .= "s";
    $params[] = date('Y-m-d', strtotime($fromDate));
}
if (!empty($toDate)) {
    $query .= " AND donhang.ngaymua <= ?";
    $paramTypes .= "s";
    $params[] = date('Y-m-d', strtotime($toDate));
}

// Thêm điều kiện lọc địa chỉ
if ($addressFilter !== 'all') {
    $query .= " AND donhang.diachi = ?";
}

// Chuẩn bị câu lệnh SQL
$stmt = $conn->prepare($query);

// Xây dựng mảng chứa các tham số cần bind
$paramTypes = "";
$params = [];

if ($statusFilter !== 'all') {
    $paramTypes .= "s";
    $params[] = &$statusFilter;
}
if (!empty($fromDate)) {
    $paramTypes .= "s";
    $params[] = &$fromDate;
}
if (!empty($toDate)) {
    $paramTypes .= "s";
    $params[] = &$toDate;
}
if ($addressFilter !== 'all') {
    $paramTypes .= "s";
    $params[] = &$addressFilter;
}

// Nếu có điều kiện lọc thì bind các giá trị vào
if (!empty($paramTypes)) {
    $stmt->bind_param($paramTypes, ...$params);
}

// Thực thi truy vấn
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_update_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['new_status'];

    $updateQuery = "UPDATE donhang SET trangthai = '" . mysqli_real_escape_string($conn, $newStatus) . "' WHERE iddonhang = " . (int)$orderId;
    if (mysqli_query($conn, $updateQuery)) {
        echo "success";
    } else {
        echo "error";
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giao diện Admin</title>
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
    <link rel="stylesheet" href="css/ManageCustomerOrder.css">

</head>
<body>
<header style="background-color: rgb(255,255,255); color: #0b0b0b; padding: 20px 40px; display: flex; align-items: center; justify-content: space-between; font-size: 28px; border-bottom: 5px solid #0b0b0b;">
    <!-- Logo bên trái -->
    <div style="flex: 0; display: flex; align-items: center;">
        <img src="img/fav.png" alt="Karma Logo" style="width: 60px; height: 60px; border-radius: 50%; margin-right: 20px;">
        <h1 style="margin: 0; font-size: 20px;">Karma Shop</h1>
    </div>

    <!-- Phần menu bên phải -->
    <nav style="flex: 1;text-align: center">
        <ul style="list-style: none; display: flex; justify-content: right; margin: 0; padding: 0;">
            <li style="margin: 0 20px;"><a href="admin.html" style="color: #0b0b0b; text-decoration: none; font-size: 22px;">Admin</a></li>
            <li style="margin: 0 20px;"><a href="dangxuatadmin.html" style="color: #0b0b0b; text-decoration: none; font-size: 22px;">Đăng xuất</a></li>
        </ul>
    </nav>
</header>

<div class="admin-container">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    <!--End sidebar-->
    <div class="dashboard-container">
        <h1>Quản lí đơn hàng</h1>
        <div class="alert" id="status-alert">
            <span>Trạng thái đã được cập nhật thành công!</span>
            <span class="close-btn" onclick="closeAlert()">&times;</span>
        </div>
        <form method="GET" class="filter-section">
            <label for="status">Trạng thái:</label>
            <select id="status" name="status">
                <option value="all">Tất cả</option>
                <option value="Đã xác nhận">Đã xác nhận</option>
                <option value="Hoàn thành">Hoàn thành</option>
                <option value="Hủy bỏ">Hủy bỏ</option>
            </select>
            <label for="from-date">Từ ngày:</label>
            <input type="date" id="from-date" name="from-date">
            <label for="to-date">Đến ngày:</label>
            <input type="date" id="to-date" name="to-date">
            <label for="address">Địa chỉ:</label>
            <select id="address" name="address">
                <option value="all">Tất cả</option>
                <option value="Quận 1, HCMC">Quận 1, HCMC</option>
                <option value="Quận 2, HCMC">Quận 2, HCMC</option>
                <option value="Quận 3, HCMC">Quận 3, HCMC</option>
                <option value="Quận 4, HCMC">Quận 4, HCMC</option>
                <option value="Quận 5, HCMC">Quận 5, HCMC</option>
                <option value="Quận 6, HCMC">Quận 6, HCMC</option>
                <option value="Long Thành, Đồng Nai">Long Thành, Đồng Nai</option>
            </select>
            <button type="submit">Lọc</button>
        </form>
        <table>
            <thead>
            <tr>
                <th>Mã Đơn hàng</th>
                <th>Tên Khách hàng</th>
                <th>Số Điện Thoại</th>
                <th>Ngày Đặt hàng</th>
                <th>Tổng tiền</th>
                <th>Phương thức thanh toán</th>
                <th>Trạng thái</th>
                <th>Địa chỉ giao hàng</th>
                <th>Chi Tiết</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $isFiltering = isset($_GET['status']) || isset($_GET['from-date']) || isset($_GET['to-date']) || isset($_GET['address']);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['iddonhang']; ?></td>
                        <td><?php echo $row['tenkh']; ?></td>
                        <td><?php echo $row['sdt']; ?></td>
                        <td><?php echo $row['ngaymua']; ?></td>
                        <td><?php echo number_format($row['tongtien'], 0, ',', '.'); ?> VND</td>
                        <td><?php echo $row['phuongthucthanhtoan']; ?></td>
                        <td>
                            <select class="update-status" data-order-id="<?php echo $row['iddonhang']; ?>">
                                <option value="Đã xác nhận" <?php if($row['trangthai'] == 'Đã xác nhận') echo 'selected'; ?>>Đã xác nhận</option>
                                <option value="Hoàn thành" <?php if($row['trangthai'] == 'Hoàn thành') echo 'selected'; ?>>Hoàn thành</option>
                                <option value="Hủy bỏ" <?php if($row['trangthai'] == 'Hủy bỏ') echo 'selected'; ?>>Hủy bỏ</option>
                            </select>
                        </td>
                        <td><?php echo $row['diachi']; ?></td>
                        <td>
                            <a href="viewdetail.php?id=<?php echo $row['iddonhang']; ?>" class="view-details-btn">Chi tiết</a>
                        </td>

                    </tr>
                    <?php
                }
            } elseif ($isFiltering) { // Chỉ hiển thị nếu có lọc mà không có kết quả
                ?>
                <tr>
                    <td colspan="9" style="text-align: center; font-weight: bold; color: red;">Không tìm thấy kết quả</td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".update-status").forEach(function (select) {
            select.addEventListener("change", function () {
                var orderId = this.getAttribute("data-order-id");
                var newStatus = this.value;

                var formData = new FormData();
                formData.append("ajax_update_status", "true");
                formData.append("order_id", orderId);
                formData.append("new_status", newStatus);

                fetch("ManageCustomerOrder.php", {
                    method: "POST",
                    body: formData
                })
                    .then(response => response.text())
                    .then(result => {
                        if (result.trim() === "success") {
                            showAlert();
                        } else {
                            alert("Lỗi khi cập nhật trạng thái.");
                        }
                    })
                    .catch(error => console.error("Lỗi:", error));
            });
        });
    });

    function showAlert() {
        var alertBox = document.getElementById('status-alert');
        alertBox.style.display = 'block';
        setTimeout(function () {
            alertBox.style.display = 'none';
        }, 3000);
    }
    document.addEventListener("DOMContentLoaded", function () {
        // Kiểm tra nếu trang được tải lại bằng F5
        if (performance.getEntriesByType("navigation")[0].type === "reload") {
            window.location.href = window.location.pathname; // Load lại trang mà không có tham số
        }
    });
</script>
</body>
</html>
