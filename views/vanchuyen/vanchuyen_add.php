<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm vận chuyển</title>
</head>
<body>
    <h1 align="center">THÊM VẬN CHUYỂN</h1>

    <?php
    session_start();
    include "../../includes/api_helper.php";
    requireLogin();

    $thongbao = "";

    // Xử lý khi submit form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $madh      = $_POST['txt_madh'] ?? '';
        $ngaygiao  = $_POST['txt_ngaygiao'] ?? '';

        // Validate dữ liệu
        if (empty($madh) || empty($ngaygiao)) {
            $thongbao = "Vui lòng điền đầy đủ thông tin";
        } else {
            // Gọi API thêm vận chuyển
            $result = callVanchuyenAPI([
                "action"   => "add",
                "madh"     => $madh,
                "ngaygiao" => $ngaygiao
            ]);

            if ($result && $result['status']) {
                header("Location: vanchuyen.php");
                exit();
            } else {
                $thongbao = "Lỗi: " . ($result['message'] ?? 'Không xác định');
            }
        }
    }

    // Lấy danh sách đơn hàng chưa có vận chuyển
    $result_orders = callVanchuyenAPI(['action' => 'getunshipped']);
    $unshipped_orders = ($result_orders && $result_orders['status']) ? $result_orders['data'] : [];
    ?>

    <?php if ($thongbao): ?>
        <p align="center" style="color:red;"><?php echo $thongbao; ?></p>
    <?php endif; ?>

    <form action="vanchuyen_add.php" method="post">
        <table align="center" border="1">
            <tr>
                <td colspan="2" align="center">Thông tin vận chuyển</td>
            </tr>
            <tr>
                <td>Chọn đơn hàng:</td>
                <td>
                    <select name="txt_madh" required>
                        <option value="">-- Chọn đơn hàng --</option>
                        <?php foreach ($unshipped_orders as $order): ?>
                            <option value="<?php echo $order['madh']; ?>">
                                Đơn hàng #<?php echo $order['madh']; ?> - 
                                <?php echo htmlspecialchars($order['tenkh']); ?> - 
                                <?php echo number_format($order['trigia']); ?> VNĐ
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Ngày giao dự kiến:</td>
                <td>
                    <input type="date" name="txt_ngaygiao" required>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="OK">
                    <input type="reset" value="Reset">
                    <input type="button" value="Quay lại" onclick="window.location.href='vanchuyen.php'">
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
