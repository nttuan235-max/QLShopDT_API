<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật vận chuyển</title>
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

    $mavc = isset($_REQUEST["mavc"]) ? $_REQUEST["mavc"] : "";
    $ngaygiao = isset($_REQUEST["ngaygiao"]) ? $_REQUEST["ngaygiao"] : "";

    if (empty($mavc) || empty($ngaygiao)) {
        echo "<p align='center'>Vui lòng điền đầy đủ thông tin!</p>";
        echo "<p align='center'><a href='vanchuyen_edit.php?mavc=$mavc'>Quay lại</a></p>";
        exit();
    }

    mysqli_set_charset($conn, "utf8");

    $sql_update = "UPDATE vanchuyen SET ngaygiao = '$ngaygiao' WHERE mavc = '$mavc'";

    if (mysqli_query($conn, $sql_update)) {
        echo "<p align='center'>Cập nhật thông tin vận chuyển thành công!</p>";
        echo "<p align='center'><a href='vanchuyen.php'>Quay lại danh sách</a></p>";
    } else {
        echo "<p align='center'>Lỗi: " . mysqli_error($conn) . "</p>";
        echo "<p align='center'><a href='vanchuyen_edit.php?mavc=$mavc'>Thử lại</a></p>";
    }

    mysqli_close($conn);
    ?>
</body>
</html>