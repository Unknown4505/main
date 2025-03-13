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
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                    <ul class="nav navbar-nav menu_nav ml-auto">
                        <li class="nav-item active"><a class="nav-link" href="index.php">Trang chủ</a></li>
                        <li class="nav-item submenu dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                               aria-expanded="false">Sản phẩm</a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a class="nav-link" href="category.php?brand=adidas">Adidas</a></li>
                                <li class="nav-item"><a class="nav-link" href="category.php?brand=vans">Vans</a></li>
                                <li class="nav-item"><a class="nav-link" href="category.php?brand=nike">Nike</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="checkout.php">Thanh toán</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Đăng nhập</a></li>
                    </ul>
                    <!-- Search Input Box -->
                    <input type="checkbox" id="search-toggle" class="search-toggle" hidden>
                    <div class="search_input">
                        <form id="search-form" action="search.php" method="GET" class="d-flex justify-content-between">
                            <input type="text" class="search-input" name="query" placeholder="Tìm kiếm" required>
                            <button type="submit" class="search-btn">
                                <span class="lnr lnr-magnifier"></span>
                            </button>
                            <label for="search-toggle" class="lnr lnr-cross" title="Close Search"></label>
                        </form>
                    </div>
                    <!-- Search Icon -->
                    <ul class="nav navbar-nav navbar-right">
                        <li class="nav-item">
                            <label for="search-toggle" class="search-icon">
                                <span class="lnr lnr-magnifier"></span>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
