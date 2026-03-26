<?php
header("Content-Type: application/json");

// Kết nối database
include "db.php";

// Đọc dữ liệu từ input
$data = json_decode(file_get_contents("php://input"), true);

// Lấy username và password từ dữ liệu gửi lên
$username = $data['username'];
$password = $data['password'];

// Truy vấn kiểm tra tài khoản
$sql = "SELECT * FROM taikhoan WHERE tentk = '$username' AND mk = '$password'";
$result = $conn->query($sql);

if($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    echo json_encode([
        "status" => true,
        "message" => "Đăng nhập thành công",
        "user" => [
            "matk" => $user['matk'],
            "tentk" => $user['tentk'],
            "role" => $user['role']
        ]
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Sai tên đăng nhập hoặc mật khẩu",
        "thongtin" => ["username" => $username, "password" => $password]
    ]);
}

$conn->close();
?>
