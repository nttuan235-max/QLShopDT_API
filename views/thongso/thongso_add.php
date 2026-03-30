<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm thông số</title>
</head>
<body>
    <h1 align="center">THÊM THÔNG SỐ</h1>

    <?php
    include "../../includes/api_helper.php";

    // Lấy masp từ URL để pre-select và quay lại đúng trang
    $masp_url = $_GET['masp'] ?? '';
    $thongbao = "";

    // Xử lý khi submit form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tents  = $_POST['txt_tents']  ?? '';
        $masp   = $_POST['masp']       ?? '';
        $giatri = $_POST['txt_giatri'] ?? '';

        // Gọi API để thêm thông số
        $result = callThongsoAPI([
            "action" => "add",
            "tents"  => $tents,
            "masp"   => $masp,
            "giatri" => $giatri
        ]);

        if ($result && $result['status']) {
            header("Location: thongso.php?masp=$masp");
            exit();
        } else {
            $thongbao = "Lỗi: " . ($result['message'] ?? 'Không xác định');
        }
    }

    // Gọi API lấy danh sách sản phẩm cho dropdown
    $spResult = callThongsoAPI(['action' => 'getsanpham']);
    $sanphams = ($spResult && $spResult['status']) ? $spResult['data'] : [];
    ?>

    <?php if ($thongbao): ?>
        <p align="center" style="color:red;"><?php echo $thongbao; ?></p>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <table align="center" border="1">
            <tr>
                <td colspan="2" align="center">Thông tin thông số</td>
            </tr>
            <tr>
                <td>Tên thông số</td>
                <td><input type="text" name="txt_tents" required></td>
            </tr>
            <tr>
                <td>Sản phẩm</td>
                <td>
                    <select name="masp">
                        <option value="0">--Chọn sản phẩm--</option>
                        <?php foreach ($sanphams as $sp): ?>
                            <option value="<?php echo $sp['masp']; ?>"
                                <?php echo ($sp['masp'] == $masp_url) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sp['tensp']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Giá trị</td>
                <td><input type="text" name="txt_giatri"></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="OK">
                    <input type="reset"  value="Reset">
                    <input type="button" value="Quay lại"
                           onclick="window.location.href='thongso.php?masp=<?php echo $masp_url; ?>'">
                </td>
            </tr>
        </table>
    </form>
</body>
</html>