<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa vận chuyển</title>
</head>
<body>
    <h1 align="center">SỬA VẬN CHUYỂN</h1>

    <?php
    session_start();
    include "../../includes/api_helper.php";
    requireLogin();

    $mavc     = $_GET['mavc'] ?? $_POST['mavc'] ?? 0;
    $thongbao = "";

    // Xử lý khi submit form (UPDATE)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ngaygiao = $_POST['txt_ngaygiao'] ?? '';

        // Validate dữ liệu
        if (empty($ngaygiao)) {
            $thongbao = "Vui lòng điền đầy đủ thông tin";
        } else {
            // Gọi API cập nhật vận chuyển
            $result = callVanchuyenAPI([
                "action"   => "update",
                "mavc"     => $mavc,
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

    // Lấy thông tin vận chuyển hiện tại để điền vào form
    $result_vc = callVanchuyenAPI([
        "action" => "getone",
        "mavc"   => $mavc
    ]);

    if (!($result_vc && $result_vc['status'])) {
        echo "<p align='center' style='color:red;'>Không tìm thấy vận chuyển</p>";
        echo "<p align='center'><a href='vanchuyen.php'>Quay lại</a></p>";
        exit();
    }
    $vc = $result_vc['data'];
    ?>

    <?php if ($thongbao): ?>
        <p align="center" style="color:red;"><?php echo $thongbao; ?></p>
    <?php endif; ?>

    <form method="post" action="vanchuyen_edit.php?mavc=<?php echo $mavc; ?>">
        <input type="hidden" name="mavc" value="<?php echo $mavc; ?>">

        <table align="center" border="1">
            <tr>
                <td colspan="2" align="center">Thông tin vận chuyển</td>
            </tr>
            <tr>
                <td>Mã vận chuyển:</td>
                <td><strong><?php echo htmlspecialchars($vc['mavc']); ?></strong></td>
            </tr>
            <tr>
                <td>Đơn hàng:</td>
                <td>
                    Đơn hàng #<?php echo htmlspecialchars($vc['madh']); ?> - 
                    <?php echo htmlspecialchars($vc['tenkh']); ?> - 
                    <?php echo number_format($vc['trigia'] ?? 0); ?> VNĐ
                </td>
            </tr>
            <tr>
                <td>Địa chỉ giao hàng:</td>
                <td><?php echo htmlspecialchars($vc['diachi'] ?? ''); ?></td>
            </tr>
            <tr>
                <td>Ngày giao dự kiến:</td>
                <td>
                    <input type="date" name="txt_ngaygiao" value="<?php echo htmlspecialchars($vc['ngaygiao']); ?>" required>
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
