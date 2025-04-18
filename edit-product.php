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

// Kết nối database
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra có `idsp` hay không
if (!isset($_GET['idsp'])) {
    die("Không có sản phẩm nào được chọn!");
}
$idsp = intval($_GET['idsp']);

// Lấy thông tin sản phẩm từ database
$sql = "SELECT * FROM sp WHERE idsp = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idsp);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
if (!$product) {
    die("Sản phẩm không tồn tại!");
}

// Lấy danh sách loại sản phẩm
$sql = "SELECT * FROM loaisp";
$loai_result = $conn->query($sql);

// Xử lý khi nhấn "Lưu thay đổi"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tensp = trim($_POST["tensp"]);
    $giathanh = floatval($_POST["giathanh"]);
    $soluong = intval($_POST["soluong"]);
    $idloai = intval($_POST["idloai"]);
    $mota = trim($_POST["mota"]);
    $image_path = $product["images"]; // Giữ ảnh cũ nếu không thay đổi

    // Kiểm tra dữ liệu đầu vào
    if (empty($tensp) || $soluong <= 0 || $giathanh <= 0 || $idloai <= 0 || empty($mota)) {
        die("Dữ liệu không hợp lệ!");
    }

    // Xử lý upload ảnh mới (nếu có)
    if ($_FILES["images"]["error"] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($_FILES["images"]["name"]);
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
                imagewebp($dst, $target_file, 90);
            }

            imagedestroy($src);
            imagedestroy($dst);

            // Cập nhật đường dẫn ảnh mới
            $image_path = $target_file;
        } else {
            die("Lỗi khi xử lý hình ảnh: Không thể tạo ảnh từ file.");
        }
    }

    // Cập nhật thông tin sản phẩm vào database
    $sql = "UPDATE sp SET tensp=?, giathanh=?, soluong=?, idloai=?, mota=?, images=? WHERE idsp=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdiissi", $tensp, $giathanh, $soluong, $idloai, $mota, $image_path, $idsp);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='managerp.php';</script>";
    } else {
        echo "Lỗi cập nhật: " . $stmt->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giao diện Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="img/fav.png">
    <meta name="author" content="CodePixar">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta charset="UTF-8">
    <title>Karma Shop</title>
    <style>
        /* Admin Container */
        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 15%;
            background-color: #232323;
            color: white;
            padding: 20px 15px;
        }

        .sidebar .logo-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .sidebar .logo-header img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .sidebar .logo-header h2 {
            color: white;
            font-size: 22px;
            margin: 0;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #ecf0f1;
        }

        .main-content h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        /* Dashboard Cards */
        .dashboard {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            flex: 1 1 calc(20% - 20px);
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px
        }

        /* Đặt dropdown là relative để menu con hiển thị đúng vị trí */
        .dropdown {
            position: relative;
        }

        /* Menu con bị ẩn mặc định */
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #34495e;
            list-style: none;
            margin: 0;
            padding: 0;
            border-radius: 5px;
            z-index: 10;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        /* Các mục trong menu con */
        .dropdown-menu li {
            margin: 0;
        }

        .dropdown-menu li a {
            display: block;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-bottom: 1px solid #2c3e50;
            transition: background 0.3s;
        }

        .dropdown-menu li a:hover {
            background-color: #2c3e50;
        }

        /* Hiển thị menu con khi di chuột vào dropdown */
        .dropdown:hover .dropdown-menu {
            display: block;
        }

        /* CSS cho hộp thông báo tùy chỉnh */
        .custom-alert {
            display: none; /* Ẩn ban đầu */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 300px;
            padding: 20px;
            text-align: center;
            z-index: 1000;
            border-radius: 10px;
        }

        .custom-alert p {
            margin-bottom: 20px;
        }

        .custom-alert button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .custom-alert button:hover {
            background-color: #218838;
        }

        /* CSS cho nút */
        .button-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .button-container button {
            padding: 10px 15px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            width: 150px;
        }

        .button-container .add-product-btn {
            background-color: #28a745;
            color: white;
        }

        .button-container .add-product-btn:hover {
            background-color: #218838;
        }

        .button-container .back-home-btn {
            background-color: #007bff;
            color: white;
        }

        .button-container .back-home-btn:hover {
            background-color: #0056b3;
        }

        /* CSS cho nút Chọn tệp */
        .fake-upload-btn {
            padding: 8px 15px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            background-color: white;
            color: black;
            border: 1px solid #000;
        }

        .fake-upload-btn:hover {
            background-color: #f8f8f8;
        }
        /* CSS cơ bản */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width:1200px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .product-edit {
            display: flex;
            gap: 20px;
        }

        .product-image {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }

        .product-image img {
            max-width: 100%;
            border-radius: 8px;
            width: 300px;
        }

        .product-details {
            flex: 2;
        }

        .product-details form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 600px;
            height: auto;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .button-group {
            display: flex;
            gap: 10px;
        }

        .button-group button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .button-group .btn-save {
            background-color: #28a745;
            color: #fff;
        }

        .button-group .btn-cancel {
            background-color: #dc3545;
            color: #fff;
        }
        .custom-alert {
            display: none; /* Ẩn hộp thông báo mặc định */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            padding: 20px;
            text-align: center;
            z-index: 1000;
            border-radius: 10px;
        }

        .custom-alert p {
            margin-bottom: 20px;
            font-size: 16px;
            color: #333;
        }

        .custom-alert button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .custom-alert button:hover {
            background-color: #218838;
        }
    </style>
</head>

<?php include 'header-admin.php' ?>

<div class="admin-container">
    <!-- Sidebar -->
    <?php include 'sidebar.php' ?>
    <body>
    <div class="container">
        <h1>Chỉnh sửa sản phẩm</h1>
        <div class="product-edit">
            <div class="product-image">
                <img src="<?php echo $product['images']; ?>" alt="Ảnh sản phẩm" width="150">
            </div>
            <div class="product-details">
                <form id="edit-product-form" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="tensp">Tên sản phẩm</label>
                        <input type="text" id="tensp" name="tensp" value="<?php echo htmlspecialchars($product['tensp']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="giathanh">Giá sản phẩm (VNĐ)</label>
                        <input type="number" id="giathanh" name="giathanh" value="<?php echo $product['giathanh']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="soluong">Số lượng</label>
                        <input type="number" id="soluong" name="soluong" value="<?php echo $product['soluong']; ?>" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="idloai">Loại sản phẩm</label>
                        <select id="idloai" name="idloai" required>
                            <?php while ($loai = $loai_result->fetch_assoc()) { ?>
                                <option value="<?php echo $loai['idloai']; ?>" <?php echo ($loai['idloai'] == $product['idloai']) ? 'selected' : ''; ?>>
                                    <?php echo $loai['tenloai']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tinhtrang">Tình trạng</label>
                        <select id="tinhtrang" name="tinhtrang">
                            <option value="Còn hàng">Còn hàng</option>
                            <option value="Hết hàng">Hết hàng</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mota">Mô tả sản phẩm</label>
                        <textarea id="mota" name="mota" rows="5"><?php echo htmlspecialchars($product['mota']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="images">Thay đổi hình ảnh</label>
                        <input type="file" id="images" name="images" accept="image/*">
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn-save">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
