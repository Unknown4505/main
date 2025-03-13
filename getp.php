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

// Xóa sản phẩm nếu có yêu cầu
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM sp WHERE idsp = $delete_id"); // Đổi id thành idsp
    header("Location: managerp.php"); // Tải lại trang sau khi xóa
    exit();
}

// Truy vấn dữ liệu từ bảng sản phẩm
$sql = "SELECT idsp, idloai, tensp, soluong, giathanh, images FROM sp";
$result = $conn->query($sql);

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
    $stt = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$stt."</td>
                <td>".$row["tensp"]."</td> <!-- Đổi 'ten' thành 'tensp' -->
                <td><img src='".$row["images"]."' alt='Sản phẩm' class='product-img' width='100'></td> <!-- Đổi 'hinhanh' thành 'images' -->
                <td>".number_format($row["giathanh"])."đ</td> <!-- Đổi 'gia' thành 'giathanh' -->
                <td>".$row["soluong"]."</td>
                <td>
                    <a href='managerp.php?delete_id=".$row['idsp']."' class='action-btn' onclick=\"return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')\">Xóa</a>
                    <a href='edit-product.php?idsp=".$row['idsp']."' class='action-btn'>Sửa</a>
                </td>
              </tr>";
        $stt++;
    }
    echo "</tbody></table>";
} else {
    echo "Không có dữ liệu!";
}

// Đóng kết nối
$conn->close();
?>
