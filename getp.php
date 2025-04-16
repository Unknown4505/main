<?php
ob_start(); // Bắt đầu bộ đệm đầu ra
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
// Xóa sản phẩm nếu có yêu cầu
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    // Lấy tên sản phẩm trước khi kiểm tra và xóa
    $product_result = $conn->query("SELECT tensp FROM sp WHERE idsp = $delete_id");
    $product_name = '';
    if ($product_result && $product_result->num_rows > 0) {
        $product_row = $product_result->fetch_assoc();
        $product_name = $product_row['tensp'];
    }

    // Kiểm tra xem sản phẩm đã từng xuất hiện trong đơn hàng hay chưa
    $check_sql = "SELECT COUNT(*) as total FROM ctdonhang WHERE idsp = $delete_id";
    $check_result = $conn->query($check_sql);
    $row = $check_result->fetch_assoc();

    if ($row['total'] > 0) {
        // Nếu sản phẩm đã từng được bán, không cho phép xóa
        echo "<script>alert('Không thể xóa sản phẩm này vì đã có đơn hàng bán ra.'); window.location.href='managerp.php';</script>";
    } else {
        // Xóa sản phẩm nếu chưa từng được bán
        $conn->query("DELETE FROM sp WHERE idsp = $delete_id");
        echo "<script>alert('Đã xóa thành công sản phẩm: $product_name'); window.location.href='managerp.php';</script>";
    }

    exit();
}



// Ẩn / Hiện sản phẩm
if (isset($_GET['toggle_id'])) {
    $toggle_id = intval($_GET['toggle_id']);
    $result = $conn->query("SELECT ansp FROM sp WHERE idsp = $toggle_id");
    if ($row = $result->fetch_assoc()) {
        $new_status = ($row["ansp"] == 1) ? 0 : 1;
        $conn->query("UPDATE sp SET ansp = $new_status WHERE idsp = $toggle_id");
    }
    echo "<script>window.location.href='managerp.php';</script>"; // Reload lại trang
    exit();
}

// Truy vấn danh sách sản phẩm
$sql = "SELECT idsp, idloai, tensp, soluong, giathanh, images, ansp FROM sp";
$result = $conn->query($sql);

// Hiển thị danh sách sản phẩm
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
                <td>".$row["tensp"]."</td>
                <td><img src='".$row["images"]."' alt='Sản phẩm' class='product-img' width='100'></td>
                <td>".number_format($row["giathanh"])."đ</td>
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
    echo "</tbody></table>";
} else {
    echo "Không có dữ liệu!";
}

// Đóng kết nối
$conn->close();
ob_end_flush(); // Kết thúc bộ đệm đầu ra
?>
