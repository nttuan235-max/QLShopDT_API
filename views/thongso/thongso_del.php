<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa thông số</title>
</head>
<body>
    <?php
    include "../../includes/api_helper.php";

    $mats = $_GET['mats'] ?? 0;
    $masp = $_GET['masp'] ?? 0;

    // Gọi API để xóa thông số
    $result = callThongsoAPI([
        "action" => "delete",
        "mats"   => $mats
    ]);

    if ($result && $result['status']) {
        header("Location: thongso.php?masp=$masp");
        exit();
    } else {
        echo "<h3>Xóa thất bại</h3>";
        echo "<p>" . ($result['message'] ?? 'Lỗi không xác định') . "</p>";
        echo "<p><a href='thongso.php?masp=$masp'>Quay lại</a></p>";
    }
    ?>
</body>
</html>