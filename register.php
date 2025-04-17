
<?php
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

if (isset($_POST['btn-reg'])) {
    $diachi = $_POST['diachi'];
    $sdt = $_POST['sdt'];
    $email = $_POST['email'];
    $dob = DateTime::createFromFormat('d/m/Y', $_POST['dob'])->format('Y-m-d');
    $tenkh = $_POST['tenkh'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash mật khẩu

    if (!empty($tenkh) && !empty($diachi) && !empty($sdt) && !empty($email) && !empty($dob) && !empty($_POST['password'])) {
        $sql = "INSERT INTO `kh` (`diachi`, `sdt`, `email`, `dob`, `tenkh`, `password`) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $diachi, $sdt, $email, $dob, $tenkh, $password);

        if ($stmt->execute()) {
            $message = "Đăng ký thành công!";
        } else {
            $message = "Lỗi: " . $conn->error;
        }
        $stmt->close();
    } else {
        $message = "Bạn cần nhập đầy đủ thông tin!";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Tài Khoản</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fde5c8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #000000;
        }
        .registration-form {
            background: #ffffff;
            padding: 45px;
            padding-right: 50px;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            border: 1px solid #ddd;
        }
        .registration-form h1 {
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            color: #333333;
        }
        .registration-form label {
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
            color: #666666;
        }
        .registration-form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #cccccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            font-size: 14px;
        }
        .registration-form input:focus {
            outline: none;
            border-color: #f57c00;
            box-shadow: 0 0 5px rgba(245, 124, 0, 0.5);
        }
        .registration-form button {
            width: 100%;
            padding: 12px;
            background-color: #ffa726;
            border: none;
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .registration-form button:hover {
            background-color: #fb8c00;
        }
        .registration-form .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: #333333;
        }
        .registration-form .footer a {
            color: #f57c00;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }
        .registration-form .footer a:hover {
            text-decoration: underline;
        }

        /* Custom Notification */
        .notification {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffffff;
            border: 2px solid #ffa726;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            z-index: 1000;
            width: 300px;
        }
        .notification h2 {
            margin: 0 0 10px;
            color: #333333;
        }
        .notification p {
            margin: 0;
            color: #666666;
        }
        .notification button {
            margin-top: 20px;
            padding: 10px 20px;
            border: none;
            background-color: #ffa726;
            color: #ffffff;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .notification button:hover {
            background-color: #fb8c00;
        }
    </style>
</head>
<body>
<div class="registration-form">
    <h1>Đăng Ký Tài Khoản</h1>
    <form action="" method="POST">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Nhập email">

        <label for="tenkh">Họ và Tên</label>
        <input type="text" id="tenkh" name="tenkh" placeholder="Nhập họ và tên">

        <label for="diachi">Địa chỉ</label>
        <input type="text" id="diachi" name="diachi" placeholder="Nhập địa chỉ">

        <label for="sdt">Số điện thoại</label>
        <input type="text" id="sdt" name="sdt" placeholder="Nhập số điện thoại">

        <label for="password">Nhập mật khẩu</label>
        <input type="password" id="password" name="password" placeholder="Nhập mật khẩu ">

        <label for="dob">Ngày sinh (dd/mm/yyyy)</label>
        <input
                type="text"
                id="dob"
                name="dob"
                placeholder="dd/mm/yyyy"
                pattern="^([0-2][0-9]|(3)[0-1])\/(0[1-9]|1[0-2])\/\d{4}$"
                title="Nhập đúng định dạng: dd/mm/yyyy (VD: 25/12/2000)"
                required
        >


        <button type="submit" name="btn-reg">Đăng Ký</button>
    </form>

    <div class="footer">
        <p>Bạn đã có tài khoản? <a href="login.php">Đăng Nhập</a></p>
    </div>

    <?php if (isset($message)): ?>
        <div class="notification" style="display: block;">
            <h2><?php echo $message; ?></h2>
            <p><?php echo isset($message) ? "Bạn đã đăng ký thành công!" : "Có lỗi xảy ra"; ?></p>
            <button onclick="window.location.href='login.php';">Đóng</button>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
