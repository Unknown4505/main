<?php
session_start();
include 'header-admin.php'; // Tái sử dụng header từ file header.php

// Đặt tên đăng nhập và mật khẩu mặc định
$default_username = "admin";
$default_password = "123456";

// Xử lý đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra thông tin đăng nhập
    if ($username === $default_username && $password === $default_password) {
        $_SESSION['admin_id'] = 1; // Gán session để xác nhận đăng nhập thành công
        header("Location: admin.php"); // Chuyển hướng đến trang admin
        exit;
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
    }
}
?>

<body>
<div class="login-container">
    <div class="login-form-container">
        <form method="POST">
            <h2>Đăng nhập Admin</h2>
            <?php if (isset($error)): ?>
                <p style="color: red; font-size: 14px; margin-bottom: 15px;"><?php echo $error; ?></p>
            <?php endif; ?>
            <input type="text" name="username" placeholder="Tên đăng nhập" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required><br>
            <input type="password" name="password" placeholder="Mật khẩu" required><br>
            <button type="submit">Đăng nhập</button>
        </form>
    </div>
</div>
<style>
    /* Container chính để căn giữa */
    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 105px); /* Trừ đi chiều cao của header */
        background: linear-gradient(135deg, #a8b4ff, #fcffb0);
        padding: 20px;
    }

    /* Form container */
    .login-form-container {
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        text-align: center;
    }

    /* Tiêu đề form */
    .login-form-container h2 {
        margin-bottom: 20px;
        font-size: 24px;
        color: #222;
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
    }

    /* Input fields */
    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 12px 15px;
        margin: 10px 0;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        font-family: 'Roboto', sans-serif;
        box-sizing: border-box;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 8px rgba(74, 144, 226, 0.3);
        outline: none;
    }

    input[type="text"]::placeholder,
    input[type="password"]::placeholder {
        color: #999;
        font-style: italic;
    }

    /* Button styling */
    button[type="submit"] {
        width: 100%;
        padding: 12px;
        background-color: #4a90e2;
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 16px;
        font-weight: 600;
        font-family: 'Roboto', sans-serif;
        cursor: pointer;
        transition: background-color 0.3s, box-shadow 0.3s;
        margin-top: 15px;
    }

    button[type="submit"]:hover {
        background-color: #357abd;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Responsive design */
    @media (max-width: 576px) {
        .login-form-container {
            padding: 20px;
            max-width: 100%;
        }
        input[type="text"],
        input[type="password"],
        button[type="submit"] {
            font-size: 14px;
        }
    }
</style>
</body>
</html>
