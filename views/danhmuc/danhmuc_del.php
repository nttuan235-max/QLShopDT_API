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
    require_once($_SERVER['DOCUMENT_ROOT'] . '/QLShopDT_API/api/db.php');;
    //Tạo câu truy vấn
    $sql_del_hangxs="DELETE FROM danhmuc WHERE `danhmuc`.`madm` = $madm";
	mysqli_query($conn,$sql_del_hangxs);
	header("Location: danhmuc.php");
	?>
</body>
</html>
