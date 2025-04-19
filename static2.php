<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login-admin.php");
    exit;
}
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Pagination settings
$limit = 6; // Number of customers per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Count total customers with completed orders
$count_sql = "SELECT COUNT(DISTINCT kh.idKH) as total
             FROM ctdonhang
             JOIN donhang dh ON ctdonhang.iddonhang = dh.iddonhang
             JOIN kh ON dh.idKH = kh.idKH
             WHERE dh.trangthai = 'Hoàn thành'";
$count_result = $conn->query($count_sql);
$total_customers = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_customers / $limit);

// Query to get top customers with phone number
$sql = "SELECT
            kh.idKH,
            kh.tenkh,
            kh.sdt,
            SUM(ctdonhang.soluong * ctdonhang.giathanh) AS tongdoanhthu,
            SUM(ctdonhang.soluong) AS soluong
        FROM ctdonhang
        JOIN donhang dh ON ctdonhang.iddonhang = dh.iddonhang
        JOIN kh ON dh.idKH = kh.idKH
        WHERE dh.trangthai = 'Hoàn thành'
        GROUP BY kh.idKH, kh.tenkh, kh.sdt
        ORDER BY tongdoanhthu DESC
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
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
            background: linear-gradient(135deg, #afefe1, #f4ddab);
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
            background: linear-gradient(135deg, #ffffff, #b3e5fc);
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table, th, td {
            border: 1px solid #dee2e6;
        }

        th, td {
            padding: 50px;
            text-align: center;
        }

        th {
            background-color: #343a40;
            color: white;
            font-size: 16px;
            text-transform: uppercase;
            font-weight: 500;
        }

        td {
            font-size: 20px;
            color: #495057;
        }

        tr:nth-child(even) td {
            background-color: #f8f9fa;
        }

        tr:hover td {
            background-color: #e9ecef;
            color: #212529;
            cursor: pointer;
        }


        footer {
            background-color: #333;
            padding: 10px;
            color: #fff;
            text-align: center;
            font-size: 0.9em;
            position: fixed;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }

        .view-details-btn {
            background-color: #0288d1;
            color: white;
            padding: 5px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 20px;
            text-align: center;
            margin-left: 10px;
        }

        .view-details-btn:hover {
            background-color: #0277bd;
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
        /* Bộ lọc */
        .filter-container {
            margin: 20px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        .filter-container form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .filter-category,
        .filter-name {
            display: flex;
            flex-direction: column;
        }

        .filter-category label,
        .filter-name label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .filter-actions {
            display: flex;
            align-items: center;
        }

        /* Buttons */
        .view-details a {
            display: inline-block;
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .view-details a:hover {
            background-color: #0056b3;
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

    </style>
</head>
<body>
<?php include 'header-admin.php'?>

<div class="admin-container">
    <?php include 'sidebar.php'; ?>
    <div class="dashboard-container">
        <header class="main-header">
            <h1>TOP KHÁCH HÀNG</h1>
        </header>

        <div class="container">
            <table>
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên Khách Hàng</th>
                    <th>Số Điện Thoại</th>
                    <th>Tổng Doanh Thu (VNĐ)</th>
                    <th>Hành Động</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $stt = $offset + 1; // Adjust STT based on page
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . $stt++ . "</td>
                            <td>" . htmlspecialchars($row["tenkh"]) . "</td>
                            <td>" . htmlspecialchars($row["sdt"]) . "</td>
                            <td>" . number_format($row["tongdoanhthu"], 0, ',', '.') . " VNĐ</td>
                            <td class='view-details'><a href='thongkesanpham.php?idKH=" . $row["idKH"] . "'>Chi Tiết</a></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Không có dữ liệu</td></tr>";
                }
                ?>
                </tbody>
            </table>

            <!-- Pagination Links -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php
                    // Previous page link
                    if ($page > 1) {
                        echo "<a href='?page=" . ($page - 1) . "'>« Trước</a>";
                    }

                    // Page number links
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $active_class = ($i == $page) ? 'active' : '';
                        echo "<a href='?page=$i' class='$active_class'>$i</a>";
                    }

                    // Next page link
                    if ($page < $total_pages) {
                        echo "<a href='?page=" . ($page + 1) . "'>Sau »</a>";
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$stmt->close();
$conn->close();
?>
</body>
</html>
