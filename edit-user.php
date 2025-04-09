<?php
session_start();

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login-admin.php");
    exit;
}

// Kết nối MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy thông tin người dùng để chỉnh sửa
if (!isset($_GET['id'])) {
    header("Location: manager-user.php?error=Không tìm thấy người dùng!");
    exit;
}

$user_id = $conn->real_escape_string($_GET['id']);
$sql = "SELECT * FROM kh WHERE idKH = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: manager-user.php?error=Không tìm thấy người dùng!");
    exit;
}

$user = $result->fetch_assoc();

// Xử lý khi form được gửi
$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenkh = $conn->real_escape_string($_POST['tenkh']);
    $email = $conn->real_escape_string($_POST['email']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $sdt = $conn->real_escape_string($_POST['sdt']);
    $diachi = $conn->real_escape_string($_POST['diachi']);

    // Cập nhật thông tin người dùng
    $sql = "UPDATE kh SET 
            tenkh = '$tenkh', 
            email = '$email', 
            dob = '$dob', 
            sdt = '$sdt', 
            diachi = '$diachi' 
            WHERE idKH = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Đã sửa thành công!";
    } else {
        $error_message = "Lỗi khi cập nhật: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
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
        body {
            font-family: 'Roboto', sans-serif;
        }

        h1, h6, p, a, button, .single-features, .product-details {
            font-family: 'Roboto', sans-serif;
        }
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
            background: linear-gradient(135deg, #f8c2c2, #fafcb3);
        }

        .container {
            max-width: 1200px;
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
        }

        .product-details {
            flex: 2;
        }

        .product-details form {
            display: flex;
            flex-direction: column;
            gap: 15px;
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
<body>
<?php include 'header-admin.php'; ?>

<div class="admin-container">
    <?php include 'sidebar.php'; ?>

    <div class="container">
        <h1>Sửa thông tin người dùng</h1>
        <div class="product-edit">
            <div class="product-image">
                <img src="img/product/hinhconnguoi.jpg" alt="Ảnh người dùng">
            </div>
            <div class="product-details">
                <!-- Hiển thị thông báo thành công hoặc lỗi -->
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success">
                        <strong>Thành công!</strong> <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger">
                        <strong>Lỗi!</strong> <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form id="edit-product-form" method="POST" action="edit-user.php?id=<?php echo $user_id; ?>">
                    <div class="form-group">
                        <label for="tenkh">Tên người dùng:</label>
                        <input type="text" id="tenkh" name="tenkh" value="<?php echo htmlspecialchars($user['tenkh']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Địa chỉ email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="dob">Ngày sinh:</label>
                        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="sdt">Số điện thoại:</label>
                        <input type="text" id="sdt" name="sdt" value="<?php echo htmlspecialchars($user['sdt']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="diachi">Địa chỉ:</label>
                        <input type="text" id="diachi" name="diachi" value="<?php echo htmlspecialchars($user['diachi']); ?>" required>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn-save">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Hộp thông báo -->
    <div class="custom-alert" id="success-alert">
        <p>Đã sửa thành công!</p>
        <button onclick="closeAlert()">OK</button>
    </div>
</div>

<script>
    // Hiển thị thông báo nếu có success_message
    <?php if (!empty($success_message)): ?>
    document.getElementById('success-alert').style.display = 'block';
    <?php endif; ?>

    // Đóng thông báo
    function closeAlert() {
        document.getElementById('success-alert').style.display = 'none';
    }
</script>
</body>
</html>
