<?php
session_start();
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

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login-admin.php");
    exit;
}

// Xử lý tìm kiếm theo tên
$search_query = "";
if (isset($_GET['product-name']) && !empty($_GET['product-name'])) {
    $search_query = $conn->real_escape_string($_GET['product-name']);
$query = "SELECT * FROM kh WHERE tenkh LIKE '%$search_query%'";
} else {
$query = "SELECT * FROM kh";
}

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giao diện Admin</title>
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
    <link  rel="stylesheet" href="css/manager-user.css">
    <style>
        /* Admin Container */
        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 15%;
            background-color: #252525;
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
        .dashboard-container {
            flex: 1;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            margin: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dashboard-container h1 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 30px;
            color: #212529;
            font-weight: 600;
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
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #b5efaf, #c6abf4);
            margin: 0;
            padding: 0;
            color: #333;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }


        .dashboard-container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background: linear-gradient(135deg, #ffc1fc, #bfffe9);
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            padding-bottom: 50px;
            margin-bottom: 70px;
        }

        h3 {
            margin-top: 0;
            color: #1976d2;
            border-bottom: 2px solid #1976d2;
            padding-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;
            background-color: #bbdefb;
            border-radius: 8px;
            padding: 10px;
        }

        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 15px;
            background-color: #0277bd;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .back-button:hover {
            background-color: #01579b;
        }



        h1 {
            text-align: center;
        }
        .pagination {
            display: flex;
            justify-content: center;
            padding: 20px 0;
        }

        .pagination a {
            color: #2c3e50;
            text-decoration: none;
            margin: 0 5px;
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        .pagination a:hover {
            background-color: #34495e;
            color: white;
        }

        .pagination a.active {
            background-color: #2c3e50;
            color: white;
            border: 1px solid #2c3e50;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            padding: 20px 0;
        }

        .pagination a {
            color: #343a40;
            text-decoration: none;
            margin: 0 5px;
            padding: 8px 16px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .pagination a:hover {
            background-color: #343a40;
            color: white;
        }

        .pagination a.active {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }
        .pagination {
            display: flex;
            justify-content: center;
            padding: 20px 0;
        }

        .pagination a {
            color: #2c3e50;
            text-decoration: none;
            margin: 0 5px;
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        .pagination a:hover {
            background-color: #34495e;
            color: white;
        }

        .pagination a.active {
            background-color: #2c3e50;
            color: white;
            border: 1px solid #2c3e50;
        }
    </style>
</head>
<body>
<?php include 'header-admin.php'?>

<div class="admin-container">
    <?php include 'sidebar.php'; ?>
    <div class="dashboard-container">
        <!-- Header Section -->
        <header class="main-header">
            <h1>Quản lí người dùng </h1>
        </header>
        <div class="filter-container">
            <form method="GET" action="your_backend_endpoint">

                <!-- Lọc theo tên -->
                <div class="filter-name">
                    <label for="product-name">Tên người dùng:</label>
                    <input type="text" id="product-name" name="product-name" placeholder="Nhập tên người dùng...">
                </div>
                <!-- Thông báo cập nhật trạng thái -->
                <div class="alert" id="status-alert">
                    <span>Trạng thái đã được cập nhật thành công!</span>
                    <span class="close-btn" onclick="closeAlert()">&times;</span>
                </div>
                <!-- Nút tìm kiếm -->
                <div class="filter-actions">
                    <a href="manager-user.html" type="submit" class="filter-btn">Lọc</a>
                </div>
            </form>
        </div>
        <a href="add-user.php" class="btn-link">Thêm</a>
        <h2>Danh sách sản phẩm:</h2>

        <div class="container">
            <!-- Back Button -->
            <table class="user-table">
                <thead>
                <tr>
                    <th>Mã Khách Hàng</th>
                    <th>Tên Khách Hàng</th>
                    <th>Địa Chỉ Email</th>
                    <th>Số Điện Thoại</th>
                    <th>Ngày Đăng Ký</th>
                    <th>Trạng Thái</th>
                    <th>Sửa</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['idKH']); ?></td>
                            <td><?php echo htmlspecialchars($row['tenkh']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['sdt']); ?></td>
                            <td><?php echo htmlspecialchars($row['ngaydangky']); ?></td>
                            <td>
                                <select class="status-select" data-id="<?php echo $row['idKH']; ?>" onchange="updateStatus(this)">
                                    <option value="1" <?php echo $row['status'] == 1 ? 'selected' : ''; ?>>Hoạt động</option>
                                    <option value="0" <?php echo $row['status'] == 0 ? 'selected' : ''; ?>>Ngưng hoạt động</option>
                                </select>
                            </td>
                            <td>
                                <a href="edit-user.php?id=<?php echo $row['idKH']; ?>" class="action-btn">Sửa</a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='8'>Không tìm thấy người dùng nào</td></tr>";
                }
                $conn->close();
                ?>
                </tbody>
            </table>
            <!-- Table Section -->
            <div class="pagination">
                <a href="manager-user.html">&laquo;</a> <!-- Quay lại trang 1 -->
                <a href="manager-user.html">1</a> <!-- Trang 1 -->
                <a href="manager-user.html">2</a> <!-- Trang 2 -->
                <a href="manager-user.html">3</a> <!-- Trang 3 -->
                <a href="manager-user.html">4</a> <!-- Trang 4 -->
                <a href="manager-user.html">5</a> <!-- Trang 5 -->
                <a href="manager-user.html">&raquo;</a> <!-- Chuyển tới trang 5 -->
            </div>
        </div>
    </div>
    <!-- JavaScript -->
    <script>// Hiển thị thông báo khi trạng thái được thay đổi
        function updateStatus(select) {
            const userId = select.getAttribute('data-id');
            const status = select.value;

            fetch('update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `user_id=${userId}&status=${status}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert();
                    } else {
                        alert('Cập nhật trạng thái thất bại: ' + (data.message || 'Lỗi không xác định'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Đã xảy ra lỗi khi cập nhật trạng thái!');
                });
        }

        function showAlert() {
            var alert = document.getElementById('status-alert');
            alert.classList.add('show');
            setTimeout(function() {
                alert.classList.remove('show');
            }, 3000);
        }

        function closeAlert() {
            var alert = document.getElementById('status-alert');
            alert.classList.remove('show');
        }
    </script>
</div>
</body>
</html>
