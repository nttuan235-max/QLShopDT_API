<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

    $username = $_SESSION['username'];
    mysqli_set_charset($conn, "utf8");

    $madh = isset($_POST['madh']) ? $_POST['madh'] : "";
    $ngaydat = isset($_POST['ngaydat']) ? $_POST['ngaydat'] : "";
    $manv = isset($_POST['manv']) ? $_POST['manv'] : "";
    $trigia = isset($_POST['trigia']) ? $_POST['trigia'] : 0;

    if (empty($madh) || empty($ngaydat) || empty($manv)) {
        echo "<p align='center'>Vui lòng điền đầy đủ thông tin!</p>";
        echo "<p align='center'><a href='donhang_edit.php?madh=$madh'>Quay lại</a></p>";
        exit();
    }

    $sql_update = "UPDATE donhang 
                SET ngaydat = '$ngaydat', 
                    manv = '$manv', 
                    trigia = '$trigia' 
                WHERE madh = '$madh'";

    if (mysqli_query($conn, $sql_update)) {
        echo "<p align='center'>Cập nhật đơn hàng thành công!</p>";
        echo "<p align='center'><a href='donhang_chitiet.php?madh=$madh'>Xem chi tiết</a> | <a href='donhang.php'>Danh sách đơn hàng</a></p>";
    } else {
        echo "<p align='center'>Lỗi: " . mysqli_error($conn) . "</p>";
        echo "<p align='center'><a href='donhang_edit.php?madh=$madh'>Thử lại</a></p>";
    }

    mysqli_close($conn);
    ?>
</body>
</html>
