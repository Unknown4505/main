<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login-admin.php");
    exit;
}
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$idKH = isset($_GET['idKH']) ? intval($_GET['idKH']) : 0;

if ($idKH == 0) {
    die("Không tìm thấy khách hàng.");
}

// Pagination settings
$limit = 6; // Number of orders per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Count total orders for this customer
$count_sql = "SELECT COUNT(DISTINCT dh.iddonhang) as total 
             FROM donhang dh 
             WHERE dh.idKH = $idKH";
$count_result = $conn->query($count_sql);
$total_orders = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_orders / $limit);

// Query to get orders with pagination
$sql = "SELECT
    dh.iddonhang AS id_chuyen_hang,
    kh.tenkh AS ho_ten_khach_hang,
    GROUP_CONCAT(CONCAT(sp.tensp, ' (', ctdh.soluong, ')') SEPARATOR ', ') AS danh_sach_san_pham
FROM donhang dh
JOIN kh ON dh.idKH = kh.idKH
JOIN ctdonhang ctdh ON dh.iddonhang = ctdh.iddonhang
JOIN sp ON ctdh.idsp = sp.idsp
WHERE kh.idKH = $idKH
GROUP BY dh.iddonhang, kh.tenkh
LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <!-- Your existing meta tags and styles remain unchanged -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giao diện Admin</title>
    <!-- Keep all your existing CSS -->
    <!-- ... -->
</head>
<body>
<?php include 'header-admin.php'?>

<div class="admin-container">
    <?php include 'sidebar.php'; ?>
    <div class="dashboard-container">
        <header class="main-header">
            <h1>Danh sách hàng đã mua</h1>
        </header>

        <div class="container">
            <table>
                <thead>
                <tr>
                    <th>ID Chuyến Hàng</th>
                    <th>Họ Tên Khách Hàng</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Hành Động</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . $row["id_chuyen_hang"] . "</td>
                            <td>" . $row["ho_ten_khach_hang"] . "</td>
                            <td>" . $row["danh_sach_san_pham"] . "</td>
                            <td><a href='chitietgiaodich.php?iddonhang=" . $row["id_chuyen_hang"] . "' class='details-button'>Chi Tiết</a></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Không có dữ liệu</td></tr>";
                }
                ?>
                </tbody>
            </table>

            <!-- Pagination Links -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php
                    // Previous page link
                    if ($page > 1) {
                        echo "<a href='?idKH=$idKH&page=" . ($page - 1) . "'>&laquo; Trước</a>";
                    }

                    // Page number links
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $active_class = ($i == $page) ? 'active' : '';
                        echo "<a href='?idKH=$idKH&page=$i' class='$active_class'>$i</a>";
                    }

                    // Next page link
                    if ($page < $total_pages) {
                        echo "<a href='?idKH=$idKH&page=" . ($page + 1) . "'>Sau &raquo;</a>";
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $conn->close(); ?>
</body>
</html>
