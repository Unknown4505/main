<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login-admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/add-product.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm</title>
    <link rel="shortcut icon" href="img/fav.png">
    <style>
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 15%;
            background-color: #232323;
            color: white;
            padding: 20px 15px;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #ecf0f1;
        }
    </style>
    <script>
        function updateIdLoai() {
            var categorySelect = document.getElementById("category");
            var idLoaiInput = document.getElementById("idloai");
            var selectedOption = categorySelect.options[categorySelect.selectedIndex];
            idLoaiInput.value = selectedOption.getAttribute("data-idloai");
        }
    </script>
</head>
<body>
<?php include 'header-admin.php' ?>
<div class="admin-container">
    <?php include 'sidebar.php' ?>
    <div class="main-content">
        <h1>Thêm Sản Phẩm</h1>
        <form id="addProductForm" action="adp.php" method="POST" enctype="multipart/form-data">
            <label for="ten">Tên Sản Phẩm:</label>
            <input type="text" id="ten" name="ten" required><br><br>

            <label for="soluong">Số Lượng:</label>
            <input type="number" id="soluong" name="soluong" required><br><br>

            <label for="gia">Giá:</label>
            <input type="text" id="gia" name="gia" required><br><br>

            <label for="category">Danh mục:</label>
            <select id="category" name="category" required onchange="updateIdLoai()">
                <option value="" data-idloai="">Chọn loại</option>
                <option value="DienThoai" data-idloai="1">Nike</option>
                <option value="Laptop" data-idloai="2">Adidas</option>
                <option value="PhuKien" data-idloai="3">Vans</option>
            </select><br><br>

            <input type="hidden" id="idloai" name="idloai" value="">

            <label for="images">Thêm Ảnh:</label>
            <input type="file" id="images" name="images" required><br><br>
            <label for="mota">Mô tả sản phẩm:</label>
            <textarea name="mota" id="mota" rows="5" required></textarea>
            <button type="submit">Thêm</button>
        </form>
    </div>
</div>
</body>
</html>
