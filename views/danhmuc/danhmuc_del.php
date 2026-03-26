<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $madm = $_GET['madm'] ?? 0;
    
    // Gọi API để xóa danh mục
    $post_data = json_encode([
        "action" => "delete",
        "madm" => $madm
    ]);
    
    $api_url = "http://localhost/QLShopDT_API/api/danhmuc_api.php";
    $options = [
        "http" => [
            "method"  => "POST",
            "header"  => "Content-Type: application/json",
            "content" => $post_data
        ]
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($api_url, false, $context);
    $result = json_decode($response, true);
    
    if($result && $result['status']) {
        header("Location: danhmuc.php");
        exit();
    } else {
        echo "<h3>Xóa thất bại</h3>";
        echo "<p>" . ($result['message'] ?? 'Lỗi không xác định') . "</p>";
    }
	?>
</body>
</html>
