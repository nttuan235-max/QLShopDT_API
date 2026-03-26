<!DOCTYPE html>
<html lang="en">
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
    
    $masp = $_REQUEST['masp'];

    // Gọi RESTful API để xóa sản phẩm
    $api_url = "http://localhost/QLShopDT_API/api/sanpham/" . $masp;

    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if($result && $result['status'])
        {
            header("Location: sanpham.php");
            exit();
        }
    else
        {
            echo "<h3>Xóa thất bại</h3>";
            echo "<p>" . ($result['error'] ?? 'Lỗi không xác định') . "</p>";
            echo '<a href="sanpham.php">Quay lại</a>';
        }
    ?>
</body>
</html>
