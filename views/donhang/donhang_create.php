<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

include "../../includes/header.php";
include "../../includes/api_helper.php";
include "../../includes/footer.php";

$thongbao = "";

// Xử lý khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gọi API tạo đơn hàng
    $result = callDonhangAPI([
        "action"   => "add",
        "makh"     => $_POST['txt_makh'],
        "ngaydat"  => $_POST['date_ngaydat'],
        "trigia"   => $_POST['num_trigia']
    ]);

    if ($result && $result['status']) {
        header("Location: donhang.php");
        exit();
    } else {
        $thongbao = "Lỗi: " . ($result['message'] ?? 'Không xác định');
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo đơn hàng</title>
</head>
<body>
    <h1 align="center">TẠO ĐƠN HÀNG</h1>

    <?php if ($thongbao): ?>
        <p align="center" style="color:red;"><?php echo $thongbao; ?></p>
    <?php endif; ?>

    <form action="donhang_create.php" method="post">
        <table align="center" border="1">
            <tr>
                <td colspan="2" align="center">Thông tin đơn hàng</td>
            </tr>
            <tr>
                <td>Mã khách hàng</td>
                <td><input type="number" name="txt_makh" min="1" required></td>
            </tr>
            <tr>
                <td>Ngày đặt</td>
                <td><input type="date" name="date_ngaydat" required></td>
            </tr>
            <tr>
                <td>Tổng tiền</td>
                <td><input type="number" name="num_trigia" min="0" required></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="TẠO ĐƠN HÀNG">
                    <input type="button" value="HỦY" onclick="window.location.href='donhang.php'">
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
