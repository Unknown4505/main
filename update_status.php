<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Kết nối thất bại: ' . $conn->connect_error]);
    exit;
}

if (isset($_POST['user_id']) && isset($_POST['status'])) {
    $user_id = $conn->real_escape_string($_POST['user_id']);
    $status = $conn->real_escape_string($_POST['status']);

    $sql = "UPDATE kh SET status = '$status' WHERE idKH = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Thiếu tham số user_id hoặc status']);
}

$conn->close();
?>
