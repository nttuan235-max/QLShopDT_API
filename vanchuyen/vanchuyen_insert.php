<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm vận chuyển</title>
</head>
<body>
    <?php
    include($_SERVER['DOCUMENT_ROOT'] . '/QLShopDT_API/api/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/QLShopDT_API/api/db.php');
    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit();
    }

    $madh = isset($_REQUEST["madh"]) ? $_REQUEST["madh"] : "";
    $ngaygiao = isset($_REQUEST["ngaygiao"]) ? $_REQUEST["ngaygiao"] : "";

    if (empty($madh) || empty($ngaygiao)) {
        echo "<p align='center'>Vui lòng điền đầy đủ thông tin!</p>";
        echo "<p align='center'><a href='vanchuyen_add.php'>Quay lại</a></p>";
        exit();
    }

    mysqli_set_charset($conn, "utf8");

    // Lấy mã khách hàng từ đơn hàng
    $sql_get_makh = "SELECT makh FROM donhang WHERE madh = '$madh'";
    $result_makh = mysqli_query($conn, $sql_get_makh);
    
    if (!$result_makh || mysqli_num_rows($result_makh) == 0) {
        echo "<p align='center'>Không tìm thấy đơn hàng!</p>";
        echo "<p align='center'><a href='vanchuyen_add.php'>Quay lại</a></p>";
        exit();
    }
    
    $row_makh = mysqli_fetch_object($result_makh);
    $makh = $row_makh->makh;

    $sql_insert = "INSERT INTO vanchuyen (mavc, madh, makh, ngaygiao) 
                   VALUES (NULL, '$madh', '$makh', '$ngaygiao')";

    if (mysqli_query($conn, $sql_insert)) {
        echo "<p align='center'>Thêm thông tin vận chuyển thành công!</p>";
        echo "<p align='center'><a href='vanchuyen.php'>Quay lại danh sách</a></p>";
    } else {
        echo "<p align='center'>Lỗi: " . mysqli_error($conn) . "</p>";
        echo "<p align='center'><a href='vanchuyen_add.php'>Thử lại</a></p>";
    }

    mysqli_close($conn);
    ?>
</body>
</html>