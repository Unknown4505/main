<?php
ob_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý xóa hoặc ẩn/bỏ ẩn sản phẩm
if (isset($_GET['action_id'])) {
    $id = intval($_GET['action_id']);

    $product_result = $conn->query("SELECT tensp, ansp FROM sp WHERE idsp = $id");
    $product = $product_result->fetch_assoc();

    $check_sql = "SELECT COUNT(*) as total FROM ctdonhang WHERE idsp = $id";
    $check_result = $conn->query($check_sql);
    $row = $check_result->fetch_assoc();

    if ($product["ansp"] == 1) {
        $conn->query("UPDATE sp SET ansp = 0 WHERE idsp = $id");
    } elseif ($row['total'] > 0) {
        $conn->query("UPDATE sp SET ansp = 1 WHERE idsp = $id");
        echo "<script>alert('Sản phẩm đã từng bán. Đã chuyển sang trạng thái ẩn.');</script>";
    } else {
        $conn->query("DELETE FROM sp WHERE idsp = $id");
        echo "<script>alert('Đã xóa sản phẩm: ".$product['tensp']."');</script>";
    }

    echo "<script>window.location.href='managerp.php';</script>";
    exit();
}

// Nhận dữ liệu lọc
$tenloai = isset($_GET['tenloai']) ? trim($_GET['tenloai']) : '';
$tensp = isset($_GET['tensp']) ? trim($_GET['tensp']) : '';
$minPrice = isset($_GET['min-price']) && $_GET['min-price'] !== '' ? (int)$_GET['min-price'] : null;
$maxPrice = isset($_GET['max-price']) && $_GET['max-price'] !== '' ? (int)$_GET['max-price'] : null;

// Phân trang
$limit = 6;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Câu truy vấn
$query = "SELECT sp.*, loaisp.tenloai FROM sp JOIN loaisp ON sp.idloai = loaisp.idloai WHERE 1=1";
$count_query = "SELECT COUNT(*) as total FROM sp JOIN loaisp ON sp.idloai = loaisp.idloai WHERE 1=1";
$params = [];
$types = "";

// Điều kiện lọc
if (!empty($tenloai)) {
    $query .= " AND loaisp.tenloai LIKE ?";
    $count_query .= " AND loaisp.tenloai LIKE ?";
    $params[] = "%$tenloai%";
    $types .= "s";
}
if (!empty($tensp)) {
    $query .= " AND sp.tensp LIKE ?";
    $count_query .= " AND sp.tensp LIKE ?";
    $params[] = "%$tensp%";
    $types .= "s";
}
if (!is_null($minPrice)) {
    $query .= " AND sp.giathanh >= ?";
    $count_query .= " AND sp.giathanh >= ?";
    $params[] = $minPrice;
    $types .= "i";
}
if (!is_null($maxPrice)) {
    $query .= " AND sp.giathanh <= ?";
    $count_query .= " AND sp.giathanh <= ?";
    $params[] = $maxPrice;
    $types .= "i";
}

// Đếm tổng sản phẩm
$stmt_count = $conn->prepare($count_query);
if ($types && $stmt_count) {
    $stmt_count->bind_param($types, ...$params);
}
$stmt_count->execute();
$count_result = $stmt_count->get_result()->fetch_assoc();
$total = $count_result['total'];
$totalPages = ceil($total / $limit);

// Truy vấn dữ liệu với phân trang
$query .= " LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($query);
if ($types && $stmt) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Hiển thị sản phẩm
if ($result->num_rows > 0) {
    echo "<table class='table table-bordered'>
            <thead>
            <tr>
              <th>STT</th>
              <th>Tên sản phẩm</th>
              <th>Hình ảnh</th>
              <th>Giá</th>
              <th>Số lượng</th>
              <th>Hành động</th>
            </tr>
            </thead>
            <tbody>";
    $stt = $offset + 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$stt."</td>
                <td>".$row["tensp"]."</td>
                <td><img src='".$row["images"]."' class='product-img' width='100'></td>
                <td>".number_format($row["giathanh"])."đ</td>
                <td>".$row["soluong"]."</td>
                <td style='text-align: center;'>
                  <a href='managerp.php?action_id=".$row['idsp']."' class='action-btn btn-danger' onclick=\"return confirm('Bạn có chắc chắn thực hiện thao tác này?')\">".($row["ansp"] == 1 ? "Bỏ ẩn" : "Xóa")."</a>
                  <a href='edit-product.php?idsp=".$row['idsp']."' class='action-btn btn-primary'>Sửa</a>
                </td>
              </tr>";
        $stt++;
    }
    echo "</tbody></table>";
} else {
    echo "<p>Không có sản phẩm phù hợp.</p>";
}

// Hiển thị phân trang
if ($totalPages > 1) {
    echo '<div class="pagination">';
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = ($i == $page) ? "class='active'" : "";
        echo "<a href='#' data-page='{$i}' $active>$i</a>";
    }
    echo '</div>';
}

$conn->close();
ob_end_flush();
?>
