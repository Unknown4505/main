<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "test";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login-admin.php");
    exit;
}
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}


// Xử lý lọc đơn hàng
$statusFilter = $_GET['status'] ?? 'all';
$fromDate = $_GET['from-date'] ?? '';
$toDate = $_GET['to-date'] ?? '';
$addressFilter = $_GET['address'] ?? 'all';

$query = "SELECT donhang.*, kh.tenkh, kh.sdt FROM donhang 
          LEFT JOIN kh ON donhang.idKH = kh.idKH
          WHERE 1=1";

$params = [];
$paramTypes = "";

if ($statusFilter !== 'all') {
    $query .= " AND donhang.trangthai = ?";
    $paramTypes .= "s";
    $params[] = $statusFilter;
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
if ($addressFilter !== 'all') {
    $query .= " AND donhang.diachi LIKE ?";
    $paramTypes .= "s";
    $params[] = '%' . $addressFilter . '%'; // ✅ chỉ 1 biến
}
$query .= " ORDER BY donhang.ngaymua DESC";

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Lỗi prepare: " . $conn->error);
}
if (!empty($paramTypes)) {
    $stmt->bind_param($paramTypes, ...$params);
}

if (!$stmt->execute()) {
    die("Lỗi execute: " . $stmt->error);
}
$result = $stmt->get_result();
$stmt->close();

// Xử lý cập nhật trạng thái đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_update_status'])) {
    $orderId = (int)$_POST['order_id'];
    $newStatus = trim($_POST['new_status']);

    $checkQuery = "SELECT trangthai FROM donhang WHERE iddonhang = ?";
    $stmt = $conn->prepare($checkQuery);
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Lỗi prepare checkQuery: " . $conn->error]);
        exit();
    }
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        echo json_encode(["status" => "error", "message" => "Đơn hàng không tồn tại"]);
        exit();
    }

    $currentStatus = trim($row['trangthai']);
    $validTransitions = [
        "Chưa xác nhận" => ["Đã xác nhận"],
        "Đã xác nhận" => ["Hoàn thành", "Hủy bỏ"],
        "Hoàn thành" => ["Hủy bỏ"], // Thêm "Hủy bỏ" vào đây
        "Hủy bỏ" => []
    ];

    if (!isset($validTransitions[$currentStatus]) || !in_array($newStatus, $validTransitions[$currentStatus])) {
        echo json_encode(["status" => "error", "message" => "Không thể cập nhật ngược trạng thái"]);
        exit();
    }

    $updateQuery = "UPDATE donhang SET trangthai = ?, ngaycapnhat = NOW() WHERE iddonhang = ?";
    $stmt = $conn->prepare($updateQuery);
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Lỗi prepare updateQuery: " . $conn->error]);
        exit();
    }
    $stmt->bind_param("si", $newStatus, $orderId);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    $stmt->close();

    exit();

}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="shortcut icon" href="img/fav.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng - Karma Shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/ManageCustomerOrder.css">
</head>
<body>
<?php include 'header-admin.php'; ?>

<div class="admin-container">
    <?php include 'sidebar.php'; ?>
    <div class="dashboard-container">
        <h1>Quản lý đơn hàng</h1>
        <div class="alert" id="status-alert">
            <span>Trạng thái đã được cập nhật thành công!</span>
            <span class="close-btn" onclick="closeAlert()">×</span>
        </div>
        <form method="GET" class="filter-section">
            <label for="status">Trạng thái:</label>
            <select id="status" name="status">
                <option value="all" <?php if ($statusFilter == 'all') echo 'selected'; ?>>Tất cả</option>
                <option value="Chưa xác nhận" <?php if ($statusFilter == 'Chưa xác nhận') echo 'selected'; ?>>Chưa xác nhận</option>
                <option value="Đã xác nhận" <?php if ($statusFilter == 'Đã xác nhận') echo 'selected'; ?>>Đã xác nhận</option>
                <option value="Hoàn thành" <?php if ($statusFilter == 'Hoàn thành') echo 'selected'; ?>>Hoàn thành</option>
                <option value="Hủy bỏ" <?php if ($statusFilter == 'Hủy bỏ') echo 'selected'; ?>>Hủy bỏ</option>
            </select>
            <label for="from-date">Từ ngày:</label>
            <input type="date" id="from-date" name="from-date" value="<?php echo htmlspecialchars($fromDate); ?>">
            <label for="to-date">Đến ngày:</label>
            <input type="date" id="to-date" name="to-date" value="<?php echo htmlspecialchars($toDate); ?>">
            <?php
            $districts = [ // <- đặt ở đây
                'Quận 1', 'Quận 3', 'Quận 4', 'Quận 5', 'Quận 6',
                'Quận 7', 'Quận 8', 'Quận 10', 'Quận 11', 'Quận 12',
                'Bình Thạnh', 'Tân Bình', 'Tân Phú', 'Phú Nhuận',
                'Gò Vấp', 'Bình Tân', 'Thủ Đức', 'Bình Chánh',
                'Hóc Môn', 'Nhà Bè', 'Củ Chi', 'Cần Giờ'
            ];
            ?>
            <label for="address">Địa chỉ:</label>
            <select id="address" name="address">
                <option value="all" <?php if ($addressFilter == 'all') echo 'selected'; ?>>Tất cả</option>
                <?php foreach ($districts as $quan): ?>
                    <option value="<?php echo htmlspecialchars($quan); ?>"
                        <?php if ($addressFilter == $quan) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($quan); ?>
                    </option>
                <?php endforeach; ?>
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
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['iddonhang']); ?></td>
                        <td><?php echo htmlspecialchars($row['tenkh'] ?? 'Khách vãng lai'); ?></td>
                        <td><?php echo htmlspecialchars($row['sdt']); ?></td>
                        <td><?php echo htmlspecialchars($row['ngaymua']); ?></td>
                        <td><?php echo number_format($row['tongtien'], 0, ',', '.'); ?> VND</td>
                        <td><?php echo htmlspecialchars($row['phuongthucthanhtoan']); ?></td>
                        <td>
                            <select class="update-status" data-order-id="<?php echo $row['iddonhang']; ?>">
                                <option value="Chưa xác nhận" <?php if (trim($row['trangthai']) == 'Chưa xác nhận') echo 'selected'; ?>>Chưa xác nhận</option>
                                <option value="Đã xác nhận" <?php if (trim($row['trangthai']) == 'Đã xác nhận') echo 'selected'; ?>>Đã xác nhận</option>
                                <option value="Hoàn thành" <?php if (trim($row['trangthai']) == 'Hoàn thành') echo 'selected'; ?>>Hoàn thành</option>
                                <option value="Hủy bỏ" <?php if (trim($row['trangthai']) == 'Hủy bỏ') echo 'selected'; ?>>Hủy bỏ</option>
                            </select>
                        </td>
                        <td><?php echo htmlspecialchars($row['diachi']); ?></td>
                        <td>
                            <a href="viewdetail.php?id=<?php echo $row['iddonhang']; ?>" class="view-details-btn">Chi tiết</a>
                        </td>
                    </tr>
                    <?php
                }
            } else { ?>
                <tr>
                    <td colspan="9" style="text-align: center; font-weight: bold; color: red;">Không có đơn hàng nào</td>
                </tr>
            <?php } ?>
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
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === "success") {
                            showAlert();
                        } else {
                            alert(result.message);
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

    function closeAlert() {
        document.getElementById('status-alert').style.display = 'none';
    }

    // Xóa các tham số GET sau khi lọc để tránh lưu trạng thái khi F5
    if (window.history.replaceState) {
        const url = new URL(window.location);
        url.search = ''; // Xóa query string
        window.history.replaceState({}, document.title, url);
    }

    const yearInput = document.getElementById('year');
    if (yearInput) {
        yearInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 4); // Chỉ cho nhập 4 chữ số
        });
    }
</script>
</body>
</html>
