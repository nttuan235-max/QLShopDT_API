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

    $sql_edit= "UPDATE `khachhang` SET `tenkh` = '$tenkh', `diachi` = '$diachi', `sdt` = '$sdt' 
                                  WHERE `khachhang`.`makh` = $makh;";

    mysqli_query($conn,$sql_edit) or die("Query unsucessful");
    header("Location: khachhang.php");
    ?>    
</body>
</html>