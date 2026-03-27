<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa sản phẩm</title>
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit();
    }

    include "../../includes/api_helper.php";

    $masp = $_GET['masp'] ?? 0;

    // Gọi API xóa sản phẩm
    $result = callSanphamAPI([
        "action" => "delete",
        "masp"   => $masp
    ]);

    if ($result && $result['status']) {
        header("Location: sanpham.php");
        exit();
    } else {
        echo "<h3>Xóa thất bại</h3>";
        echo "<p>" . ($result['message'] ?? 'Lỗi không xác định') . "</p>";
        echo '<a href="sanpham.php">Quay lại</a>';
    }
    ?>
</body>
</html>