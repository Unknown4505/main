<?php
session_start(); // Để dùng CSRF token nếu có

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
    $idloai = intval($_POST["idloai"]);
    $mota = trim($_POST["mota"]);

    // Kiểm tra dữ liệu đầu vào
    if (empty($tensp) || $soluong <= 0 || $giathanh <= 0 || $idloai <= 0 || empty($mota)) {
        die("Dữ liệu không hợp lệ!");
    }

    // Xử lý upload ảnh
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if ($_FILES["images"]["error"] == 0) {
        $file_name = basename($_FILES["images"]["name"]);
        $file_tmp = $_FILES["images"]["tmp_name"];
        $target_file = $target_dir . $file_name;

        // Kiểm tra MIME type của file
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file_tmp);
        finfo_close($finfo);

        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($mime_type, $allowed_mime_types)) {
            die("Chỉ cho phép upload file JPG, PNG hoặc WebP. Detected MIME type: $mime_type");
        }

        // Kiểm tra file là ảnh hợp lệ
        $image_info = getimagesize($file_tmp);
        if (!$image_info) {
            die("File không phải là ảnh hợp lệ.");
        }

        // Resize ảnh
        list($width, $height) = $image_info;
        $new_width = 413.75;
        $new_height = 310.31;

        $src = null;
        if ($mime_type == "image/jpeg") {
            $src = imagecreatefromjpeg($file_tmp);
        } elseif ($mime_type == "image/png") {
            $src = imagecreatefrompng($file_tmp);
        } elseif ($mime_type == "image/webp") {
            $src = imagecreatefromwebp($file_tmp);
        }

        if ($src) {
            $dst = imagecreatetruecolor($new_width, $new_height);
            // Giữ nền trong suốt cho PNG và WebP
            if ($mime_type == "image/png" || $mime_type == "image/webp") {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
                imagefilledrectangle($dst, 0, 0, $new_width, $new_height, $transparent);
            }

            imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

            // Lưu ảnh với định dạng phù hợp
            if ($mime_type == "image/jpeg") {
                imagejpeg($dst, $target_file, 90);
            } elseif ($mime_type == "image/png") {
                imagepng($dst, $target_file, 9);
            } elseif ($mime_type == "image/webp") {
                imagewebp($dst, $target_file, 90); // Chất lượng 90
            }

            imagedestroy($src);
            imagedestroy($dst);

            // Chèn dữ liệu vào bảng sp
            $sql = "INSERT INTO sp (idsp, idloai, tensp, soluong, giathanh, images, mota, ansp) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $ansp = false;
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iisddssi", $next_id, $idloai, $tensp, $soluong, $giathanh, $target_file, $mota, $ansp);

            if ($stmt->execute()) {
                echo "<script>alert('Thêm thành công sản phẩm: $tensp'); window.location.href='add-product.php';</script>";
                exit();
            } else {
                echo "Lỗi: " . $stmt->error;
            }
            $stmt->close();
        } else {
            die("Lỗi khi xử lý hình ảnh: Không thể tạo ảnh từ file.");
        }
    } else {
        die("Lỗi: Không có ảnh nào được tải lên hoặc có lỗi khi upload. Mã lỗi: " . $_FILES["images"]["error"]);
    }
}

$conn->close();
?>
