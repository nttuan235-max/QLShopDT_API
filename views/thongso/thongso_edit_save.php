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
    $mats = $_REQUEST["mats"];
    $tents = $_REQUEST["txt_tents"];
    $masp = $_REQUEST["masp"];
    $giatri = $_REQUEST["txt_giatri"];

    $sql_edit= "UPDATE `thongso` SET `tents` = '$tents', `masp` = '$masp', `giatri` = '$giatri' WHERE `thongso`.`mats` = $mats;";

    mysqli_query($conn,$sql_edit) or die("Query unsucessful");
    header("Location: thongso.php?masp=$masp");
    ?>
</body>
</html>
