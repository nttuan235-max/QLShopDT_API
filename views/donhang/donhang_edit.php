<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

include "../../includes/api_helper.php";
include "../../model/donhang_model.php";

$madh = $_GET['madh'] ?? $_POST['madh'] ?? 0;
$thongbao = "";

// Xử lý khi submit form (UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gọi API cập nhật đơn hàng
    $result = callDonhangAPI([
        "action"  => "update",
        "madh"    => $madh,
        "trigia"  => $_POST['num_trigia']
    ]);

    if ($result && $result['status']) {
        header("Location: donhang.php");
        exit();
    } else {
        $thongbao = "Lỗi: " . ($result['message'] ?? 'Không xác định');
    }
}

// Lấy thông tin đơn hàng hiện tại
$result_dh = callDonhangAPI([
    "action" => "getone",
    "madh"   => $madh
]);

if (!($result_dh && $result_dh['status'])) {
    echo "<p align='center' style='color:red;'>Không tìm thấy đơn hàng</p>";
    echo "<p align='center'><a href='donhang.php'>Quay lại</a></p>";
    exit();
}
$donhang = $result_dh['data'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa đơn hàng</title>
</head>
<body>
    <h1 align="center">SỬA ĐƠN HÀNG</h1>

    <?php if ($thongbao): ?>
        <p align="center" style="color:red;"><?php echo $thongbao; ?></p>
    <?php endif; ?>

    <form method="post" action="donhang_edit.php?madh=<?php echo $madh; ?>">
        <table align="center" border="1">
            <tr>
                <td colspan="2" align="center">Thông tin đơn hàng</td>
            </tr>
            <tr>
                <td>Mã đơn hàng</td>
                <td><?php echo htmlspecialchars($donhang['madh']); ?></td>
            </tr>
            <tr>
                <td>Tên khách hàng</td>
                <td><?php echo htmlspecialchars($donhang['tenkh']); ?></td>
            </tr>
            <tr>
                <td>Ngày đặt</td>
                <td><?php echo date('d/m/Y', strtotime($donhang['ngaydat'])); ?></td>
            </tr>
            <tr>
                <td>Tổng tiền</td>
                <td><input type="number" name="num_trigia" value="<?php echo $donhang['trigia']; ?>" min="0" required></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="hidden" name="madh" value="<?php echo $madh; ?>">
                    <input type="submit" value="CẬP NHẬT">
                    <input type="button" value="HỦY" onclick="window.location.href='donhang.php'">
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
