<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa vận chuyển</title>
</head>
<body>
    <?php
    session_start();
    include "../../includes/api_helper.php";
    requireLogin();

    $mavc = $_GET['mavc'] ?? 0;

    // Gọi API xóa vận chuyển
    $result = callVanchuyenAPI([
        "action" => "delete",
        "mavc"   => $mavc
    ]);

    if ($result && $result['status']) {
        header("Location: vanchuyen.php");
        exit();
    } else {
        echo "<h3>Xóa thất bại</h3>";
        echo "<p>" . ($result['message'] ?? 'Lỗi không xác định') . "</p>";
        echo '<a href="vanchuyen.php">Quay lại</a>';
    }
    ?>
</body>
</html>
