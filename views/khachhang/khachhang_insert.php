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
    $makh = $_REQUEST["makh"];
    $tenkh = $_REQUEST["txt_tenkh"];
    $diachi = $_REQUEST["txt_diachi"];
    $sdt = $_REQUEST["txt_sdt"];

    mysqli_select_db($conn, "qlshopdienthoai");

    $sql_create_tk = "INSERT INTO taikhoan VALUES (null, '$tenkh', '123456', '0')";
    mysqli_query($conn, $sql_create_tk);
    $result = mysqli_query($conn, "Select LAST_INSERT_ID()");

    $id = -1;
    if ($result)
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['LAST_INSERT_ID()'];
    }

    if ($id == -1) die ("KO co id");
    $sql_insert = "INSERT INTO `khachhang` (`makh`, `tenkh`, `diachi`, `sdt`) 
                   VALUES ('$id', '$tenkh', '$diachi', '$sdt');";


    mysqli_query($conn, $sql_insert);
    header("Location: khachhang.php");
    ?>
</body>
</html>
