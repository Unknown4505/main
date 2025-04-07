<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Kết nối MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Nhận dữ liệu từ form
$tenloai = isset($_GET['tenloai']) ? trim($_GET['tenloai']) : ''; // Lọc theo tên loại
$tensp = isset($_GET['tensp']) ? trim($_GET['tensp']) : '';
$minPrice = isset($_GET['min-price']) && $_GET['min-price'] !== '' ? (int)$_GET['min-price'] : null;
$maxPrice = isset($_GET['max-price']) && $_GET['max-price'] !== '' ? (int)$_GET['max-price'] : null;

// Nếu không có điều kiện nào được nhập, báo lỗi
if (empty($tenloai) && empty($tensp) && is_null($minPrice) && is_null($maxPrice)) {
    echo "<p>Vui lòng nhập ít nhất một điều kiện lọc.</p>";
    exit;
}

// Tạo truy vấn SQL với JOIN bảng loaisp
$query = "SELECT sp.*, loaisp.tenloai 
          FROM sp 
          JOIN loaisp ON sp.idloai = loaisp.idloai 
          WHERE 1=1";
$params = [];
$types = "";

// Lọc theo tên loại sản phẩm
if (!empty($tenloai)) {
    $query .= " AND loaisp.tenloai = ?";
    $params[] = $tenloai;
    $types .= "s";
}

// Lọc theo tên sản phẩm
if (!empty($tensp)) {
    $query .= " AND sp.tensp LIKE ?";
    $params[] = "%" . $tensp . "%";
    $types .= "s";
}

// Lọc theo khoảng giá
if (!is_null($minPrice) && !is_null($maxPrice)) {
    $query .= " AND sp.giathanh BETWEEN ? AND ?";
    $params[] = $minPrice;
    $params[] = $maxPrice;
    $types .= "ii";
}

// Chuẩn bị truy vấn
$stmt = $conn->prepare($query);

if ($stmt) {
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
$stt=1;
    // Hiển thị sản phẩm nếu tìm thấy
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Loại sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>".$stt."</td>
                <td>".$row["tensp"]."</td> <!-- Đổi 'ten' thành 'tensp' -->
                <td><img src='".$row["images"]."' alt='Sản phẩm' class='product-img' width='100'></td> <!-- Đổi 'hinhanh' thành 'images' -->
                <td>".number_format($row["giathanh"])."đ</td> <!-- Đổi 'gia' thành 'giathanh' -->
                <td>".$row["soluong"]."</td>
                <td style='text-align: center;'>";

            // Kiểm tra trạng thái ẩn và hiển thị nút tương ứng
            if ($row["ansp"] == 1) {
                echo "<a href='managerp.php?toggle_id=".$row['idsp']."' class='action-btn btn-secondary' onclick=\"return confirm('Bạn có chắc chắn muốn hiển thị lại sản phẩm này?')\">Bỏ ẩn</a>";
            } else {
                echo "<a href='managerp.php?toggle_id=".$row['idsp']."' class='action-btn btn-warning' onclick=\"return confirm('Bạn có chắc chắn muốn ẩn sản phẩm này?')\">Ẩn</a>";
            }

            // Nút sửa và xóa
            echo " <a href='edit-product.php?idsp=".$row['idsp']."' class='action-btn btn-primary'>Sửa</a>
               <a href='managerp.php?delete_id=".$row['idsp']."' class='action-btn btn-danger' onclick=\"return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')\">Xóa</a>
              </td></tr>";
            $stt++;
        }
        echo "</table>";
    } else {
        echo "<p>Không tìm thấy sản phẩm phù hợp.</p>";
    }

    $stmt->close();
} else {
    echo "<p>Lỗi truy vấn.</p>";
}

$conn->close();
?>
