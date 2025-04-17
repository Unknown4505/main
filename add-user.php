
<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login-admin.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenkh = $conn->real_escape_string($_POST['tenkh']);
    $email = $conn->real_escape_string($_POST['email']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $sdt = $conn->real_escape_string($_POST['sdt']);
    $diachi = $conn->real_escape_string($_POST['diachi']);
    $password = $conn->real_escape_string($_POST['password']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO kh (tenkh, email, dob, sdt, diachi,password) 
            VALUES ('$tenkh', '$email', '$dob', '$sdt', '$diachi','$password')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Đã thêm người dùng thành công!";
    } else {
        $error_message = "Lỗi khi thêm người dùng: " . $conn->error;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/add-product.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm</title>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/fav.png">
    <!-- Author Meta -->
    <meta name="author" content="CodePixar">
    <!-- Meta Description -->
    <meta name="description" content="">
    <!-- Meta Keyword -->
    <meta name="keywords" content="">
    <!-- meta character set -->
    <meta charset="UTF-8">
    <!-- Site Title -->
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
        body, html {
            margin: 0;
            padding: 0;
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

    </style>
</head>
<body>
<?php include 'header-admin.php'; ?>

<div class="admin-container">
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <h1>Thêm Người Dùng</h1>

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

        <form id="addUserForm" action="add-user.php" method="POST">
            <label for="tenkh">Tên Người Dùng:</label>
            <input type="text" id="tenkh" name="tenkh" required><br><br>

            <label for="addUserForm">Địa Chỉ Email:</label>
            <input type="text" id="email" name="email" required><br><br>

            <label for="dob">Ngày Sinh (DD/MM/YYYY):</label>
            <input type="text" id="dob" name="dob" placeholder="VD: 01/01/2024" required><br><br>

            <label for="sdt">Số Điện Thoại:</label>
            <input type="text" id="sdt" name="sdt" required><br><br>

            <label for="diachi">Địa Chỉ:</label>
            <input type="text" id="diachi" name="diachi" required><br><br>
            <label for="password">Mật khẩu:</label>
            <input type="text" id="password" name="password" required><br><br>

            <button type="submit">Thêm</button>
        </form>


        <!-- Modal hiện thông báo thêm thành công -->
        <div id="successModal">
            <p>Đã thêm thành công!</p>
            <button onclick="closeModal()">OK</button>
        </div>

        <!-- Overlay mờ khi hiển thị modal -->
        <div id="overlay"></div>
    </div>
</div>

<script>
    // Hiển thị modal nếu có success_message
    <?php if (!empty($success_message)): ?>
    document.getElementById("successModal").style.display = "block";
    document.getElementById("overlay").style.display = "block";
    <?php endif; ?>

    function closeModal() {
        // Ẩn modal thông báo và overlay
        document.getElementById("successModal").style.display = "none";
        document.getElementById("overlay").style.display = "none";

        // Reset form sau khi thêm thành công
        document.getElementById("addUserForm").reset();
    }

    // Định dạng ngày sinh (DD/MM/YYYY)
    const dobInput = document.getElementById('dob');
    dobInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, ''); // Chỉ giữ lại số
        if (value.length >= 2 && value.length < 4) {
            value = value.slice(0, 2) + '/' + value.slice(2);
        } else if (value.length >= 4) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4) + '/' + value.slice(4, 8);
        }
        e.target.value = value;
    });

    // Kiểm tra định dạng ngày sinh khi gửi form
    document.getElementById('addUserForm').addEventListener('submit', function (e) {
        const dobValue = dobInput.value;
        const dobRegex = /^\d{2}\/\d{2}\/\d{4}$/;
        if (!dobRegex.test(dobValue)) {
            e.preventDefault();
            alert('Vui lòng nhập ngày sinh đúng định dạng DD/MM/YYYY (VD: 01/01/2024)');
        } else {
            // Chuyển đổi định dạng DD/MM/YYYY sang YYYY-MM-DD để lưu vào cơ sở dữ liệu
            const [day, month, year] = dobValue.split('/');
            const formattedDob = `${year}-${month}-${day}`;
            dobInput.value = formattedDob;
        }
    });
</script>
</body>
</html>
