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
    //Tạo câu truy vấn
    $sql_del_tk="DELETE FROM taikhoan WHERE matk = $makh";
    mysqli_query($conn,$sql_del_tk);
	header("Location: khachhang.php");
	?>
</body>
</html>