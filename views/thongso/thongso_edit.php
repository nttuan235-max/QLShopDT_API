<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông số</title>
</head>
<body>
    <h1 align="center">SỬA THÔNG SỐ SẢN PHẨM</h1>

    <?php
    include "../../includes/api_helper.php";

    $mats     = $_GET['mats'] ?? $_POST['mats'] ?? 0;
    $masp     = $_GET['masp'] ?? $_POST['masp'] ?? 0;
    $thongbao = "";

    // Xử lý khi submit form (UPDATE)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tents  = $_POST['txt_tents']  ?? '';
        $masp   = $_POST['masp']       ?? '';
        $giatri = $_POST['txt_giatri'] ?? '';

        $result = callThongsoAPI([
            "action" => "update",
            "mats"   => $mats,
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

    // Lấy thông tin thông số để hiển thị form
    $result = callThongsoAPI([
        "action" => "getone",
        "mats"   => $mats
    ]);

    if ($result && $result['status']) {
        $ts     = $result['data'];
        $tents  = $ts['tents'];
        $giatri = $ts['giatri'];
        $masp   = $ts['masp'];   // ghi đè lại masp từ DB (đáng tin cậy hơn)
    } else {
        echo "<p align='center' style='color:red;'>Không tìm thấy thông số</p>";
        echo "<p align='center'><a href='thongso.php?masp=$masp'>Quay lại</a></p>";
        exit();
    }

    // Gọi API lấy danh sách sản phẩm cho dropdown
    $spResult = callThongsoAPI(['action' => 'getsanpham']);
    $sanphams = ($spResult && $spResult['status']) ? $spResult['data'] : [];
    ?>

    <?php if ($thongbao): ?>
        <p align="center" style="color:red;"><?php echo $thongbao; ?></p>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="mats" value="<?php echo $mats; ?>">
        <table align="center" border="1">
            <tr>
                <td colspan="2" align="center">Sửa thông số</td>
            </tr>
            <tr>
                <td>Tên thông số</td>
                <td>
                    <input type="text" name="txt_tents"
                           value="<?php echo htmlspecialchars($tents); ?>" required>
                </td>
            </tr>
            <tr>
                <td>Sản phẩm</td>
                <td>
                    <select name="masp">
                        <option value="0">--Chọn sản phẩm--</option>
                        <?php foreach ($sanphams as $sp): ?>
                            <option value="<?php echo $sp['masp']; ?>"
                                <?php echo ($sp['masp'] == $masp) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sp['tensp']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Giá trị</td>
                <td>
                    <input type="text" name="txt_giatri"
                           value="<?php echo htmlspecialchars($giatri); ?>">
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="OK">
                    <input type="reset"  value="Reset">
                    <input type="button" value="Quay lại"
                           onclick="window.location.href='thongso.php?masp=<?php echo $masp; ?>'">
                </td>
            </tr>
        </table>
    </form>
</body>
</html>