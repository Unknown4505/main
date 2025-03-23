<?php
// Kết nối database
$servername = "localhost";
$username = "root"; // Thay bằng username của bạn
$password = ""; // Nếu có mật khẩu, hãy điền vào đây
$database = "test"; // Thay bằng tên database của bạn

$conn = new mysqli($servername, $username, $password, $database);
?>

<!-- Start Header Area -->
<header class="header_area sticky-header">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light main_box">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <a class="navbar-brand logo_h" href="index.php"><img src="img/logo.png" alt=""></a>
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

                        <!-- Lấy danh mục từ database -->
                        <?php
                        $query = "SELECT * FROM loaisp"; // Thay thế bằng tên bảng danh mục của bạn
                        $result = mysqli_query($conn, $query);
                        ?>
                        <li class="nav-item submenu dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                               aria-expanded="false">Sản phẩm</a>
                            <ul class="dropdown-menu">
                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <li class="nav-item"><a class="nav-link" href="category.php?id=<?= $row['idloai'] ?>"><?= $row['tenloai'] ?></a></li>
                                <?php endwhile; ?>
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
                            <a class="nav-link dropdown-toggle user-btn" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="username">
                                    <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : "Người dùng"; ?>
                                </span>
                                <span class="lnr lnr-user"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?php if (isset($_SESSION['username'])) : ?>
                                    <a class="dropdown-item" href="User.php">Thông tin người dùng</a>
                                    <a class="dropdown-item" href="confirmation.php">Lịch sử giao dịch</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="logout.php">Đăng xuất</a>
                                <?php else : ?>
                                    <a class="dropdown-item" href="login.php">Đăng nhập</a>
                                <?php endif; ?>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
<!-- End Header -->
