<?php
// Xác định trang hiện tại để đánh dấu menu đang active
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <ul>
        <li><a href="admin.php" class="<?= ($current_page == 'admin.php') ? 'active' : '' ?>">Trang chủ</a></li>
        <li><a href="managerp.php" class="<?= ($current_page == 'managerp.php') ? 'active' : '' ?>">Quản lí sản phẩm</a></li>
        <li><a href="manager-user.php" class="<?= ($current_page == 'manager-user.php') ? 'active' : '' ?>">Quản lí người dùng</a></li>
        <li><a href="ManageCustomerOrder.php" class="<?= ($current_page == 'ManageCustomerOrder.php') ? 'active' : '' ?>">Quản lí đơn hàng</a></li>
        <li class="dropdown">
            <a href="#">Thống kê</a>
            <ul class="dropdown-menu">
                <li><a href="static.php" class="<?= ($current_page == 'static.php') ? 'active' : '' ?>">Sản phẩm</a></li>
                <li><a href="static2.php" class="<?= ($current_page == 'static2.php') ? 'active' : '' ?>">Người dùng</a></li>
            </ul>
        </li>
    </ul>
</div>

