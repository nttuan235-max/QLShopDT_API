<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

include "../../includes/api_helper.php";

$madh = $_GET['madh'] ?? 0;

// Gọi API xóa đơn hàng
$result = callDonhangAPI([
    "action" => "delete",
    "madh"   => $madh
]);

if ($result && $result['status']) {
    header("Location: donhang.php");
} else {
    echo "<p align='center' style='color:red;'>Lỗi: " . ($result['message'] ?? 'Không xác định') . "</p>";
    echo "<p align='center'><a href='donhang.php'>Quay lại</a></p>";
}
exit();
?>
