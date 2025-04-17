<link rel="shortcut icon" href="img/fav.png">
<header
        style="background-color: rgb(255,255,255); color: #0b0b0b; padding: 20px 40px; display: flex; align-items: center; justify-content: space-between; font-size: 28px; border-bottom: 5px solid #0b0b0b;">
    <!-- Logo bên trái -->
    <div style="flex: 0; display: flex; align-items: center;">
        <img src="img/fav.png" alt="Karma Logo" style="width: 60px; height: 60px; border-radius: 50%; margin-right: 20px;">
        <h1 style="margin: 0; font-size: 20px;">Karma Shop</h1>
    </div>

    <!-- Phần menu bên phải -->
    <nav style="flex: 1; text-align: center">
        <ul style="list-style: none; display: flex; justify-content: right; margin: 0; padding: 0;">
            <li style="margin: 0 20px;">
                <a href="admin.php" style="color: #0b0b0b; text-decoration: none; font-size: 22px;">Admin</a>
            </li>

            <?php if (isset($_SESSION['admin_id'])): ?>
                <li style="margin: 0 20px;">
                    <a href="logout-admin.php" style="color: #0b0b0b; text-decoration: none; font-size: 22px;">Đăng xuất</a>
                </li>
            <?php else: ?>
                <li style="margin: 0 20px;">
                    <a href="login-admin.php" style="color: #0b0b0b; text-decoration: none; font-size: 22px;">Đăng nhập</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
