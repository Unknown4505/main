<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "test";

// Kết nối đến MySQL
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID lớn nhất hiện tại
$result = $conn->query("SELECT MAX(idsp) AS max_id FROM sp");
$row = $result->fetch_assoc();
$next_id = ($row["max_id"] !== null) ? $row["max_id"] + 1 : 1;

// Kiểm tra nếu form được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tensp = trim($_POST["ten"]);
    $soluong = intval($_POST["soluong"]);
    $giathanh = floatval($_POST["gia"]);
    $idloai = intval($_POST["idloai"]); // Lấy ID loại từ form

    // Kiểm tra dữ liệu đầu vào
    if (empty($tensp) || $soluong <= 0 || $giathanh <= 0 || $idloai <= 0) {
        die("Dữ liệu không hợp lệ!");
    }

    // Xử lý upload ảnh
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if ($_FILES["images"]["error"] == 0) {
        $file_name = basename($_FILES["images"]["name"]);
        $target_file = $target_dir . $file_name;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Chỉ cho phép upload file JPG, JPEG, PNG
        $allowTypes = ["jpg", "jpeg", "png"];
        if (!in_array($fileType, $allowTypes)) {
            die("Chỉ cho phép upload file JPG, JPEG, PNG.");
        }

        if (move_uploaded_file($_FILES["images"]["tmp_name"], $target_file)) {
            // Chèn dữ liệu vào bảng product
            $sql = "INSERT INTO sp (idsp, idloai, tensp, soluong, giathanh, images, ansp) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $ansp = false; // Hoặc $ansp = 1 nếu cột ansp là TINYINT(1)
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iisddsi", $next_id, $idloai, $tensp, $soluong, $giathanh, $target_file, $ansp);


            if ($stmt->execute()) {
                header("Location: add-product.html?success=1");
                exit();
            } else {
                echo "Lỗi: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Lỗi: Không thể upload ảnh.";
        }
    } else {
        echo "Lỗi: Không có ảnh nào được tải lên.";
    }
}

$conn->close();
?>
