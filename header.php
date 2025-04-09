<?php
// Kết nối database
$servername = "localhost";
$username = "root";
$password = "";
$database = "test";

$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$sql_loaisp = "SELECT idloai, tenloai FROM loaisp";
$result_loaisp = $conn->query($sql_loaisp);

// Lưu danh mục vào mảng để sử dụng trong header
$categories = [];
if ($result_loaisp->num_rows > 0) {
    while ($row = $result_loaisp->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>
<!-- Start Header Area -->
<header class="header_area sticky-header">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light main_box">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand logo_h" href="index.php"><img src="img/logo.png" alt="Logo"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Navbar links -->
                <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                    <ul class="nav navbar-nav menu_nav ml-auto">
                        <li class="nav-item active"><a class="nav-link" href="index.php">Trang chủ</a></li>

                        <!-- Mục Sản Phẩm, danh mục được truyền từ trang chính -->
                        <li class="nav-item submenu dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                               aria-expanded="false">Sản phẩm</a>
                            <ul class="dropdown-menu">
                                <?php if (isset($categories) && is_array($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <li class="nav-item"><a class="nav-link" href="category.php?idloai=<?= $category['idloai'] ?>">
                                                <?= htmlspecialchars($category['tenloai']) ?></a></li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </li>

                        <li class="nav-item"><a class="nav-link" href="checkout.php">Thanh toán</a></li>
                    </ul>

                    <!-- Search Input Box -->
                    <input type="checkbox" id="search-toggle" class="search-toggle" hidden>
                    <div class="search_input">
                        <form id="search-form" action="search.php" method="GET" class="d-flex justify-content-between">
                            <input type="text" class="search-input" name="query" placeholder="Tìm kiếm sản phẩm..." required>
                            <button type="submit" class="search-btn">
                                <span class="lnr lnr-magnifier"></span>
                            </button>
                            <label for="search-toggle" class="lnr lnr-cross" title="Đóng tìm kiếm"></label>
                        </form>
                    </div>

                    <!-- Search Icon and Cart -->
                    <ul class="nav navbar-nav navbar-right">
                        <li class="nav-item"><a href="cart.php" class="cart"><span class="ti-bag"></span></a></li>
                        <li class="nav-item">
                            <label for="search-toggle" class="search-icon">
                                <span class="lnr lnr-magnifier"></span>
                            </label>
                        </li>
                    </ul>

                    <!-- User Dropdown -->
                    <ul class="nav navbar-nav navbar-right">
                        <li class="nav-item dropdown">
                            <?php if (isset($_SESSION['username'])) : ?>
                                <a class="nav-link dropdown-toggle user-btn" href="#" id="navbarDropdownUser" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="username"> <?= $_SESSION['username'] ?> </span>
                                    <span class="lnr lnr-user"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownUser">
                                    <a class="dropdown-item" href="User.php">Thông tin người dùng</a>
                                    <a class="dropdown-item" href="confirmation.php">Lịch sử giao dịch</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="logout.php">Đăng xuất</a>
                                </div>
                            <?php else : ?>
                                <a class="nav-link" href="login.php">Đăng nhập</a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('.dropdown-toggle').dropdown();

        // Toggle ô tìm kiếm
        $('#search-toggle').on('change', function () {
            if ($(this).is(':checked')) {
                $('.search_input').css({
                    'transform': 'translateY(0)',
                    'opacity': '1'
                });
                console.log('Search box displayed');
            } else {
                $('.search_input').css({
                    'transform': 'translateY(-100%)',
                    'opacity': '0'
                });
                console.log('Search box hidden');
            }
        });

        // Xử lý submit form
        $('#search-form').on('submit', function (e) {
            var searchInput = $('.search-input').val().trim();
            if (searchInput === '') {
                e.preventDefault();
                alert('Vui lòng nhập nội dung để tìm kiếm!');
            } else {
                console.log('Searching for: ' + searchInput);
            }
        });
    });

</script>

<!-- End Header -->
