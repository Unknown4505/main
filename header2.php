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
                                        <li class="nav-item"><a class="nav-link" href="category.php?id=<?= $category['idloai'] ?>">
                                                <?= htmlspecialchars($category['tenloai']) ?></a></li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </li>

                        <li class="nav-item"><a class="nav-link" href="checkout.php">Thanh toán</a></li>

                        <?php if (isset($_SESSION['username'])) : ?>
                            <li class="nav-item"><a class="nav-link" href="logout.php">Đăng xuất</a></li>
                        <?php endif; ?>
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
                            <a class="nav-link dropdown-toggle user-btn" href="#" id="navbarDropdownUser" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="username">
                                    <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : "Người dùng"; ?>
                                </span>
                                <span class="lnr lnr-user"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownUser">
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
