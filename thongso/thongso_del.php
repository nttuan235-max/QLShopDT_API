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
    $masp = $_REQUEST["masp"];
	$mats = $_REQUEST["mats"];
    //Chọn CSDL để làm việc
    mysqli_select_db($conn,"qlshopdienthoai") or die ("Không tìm thấy CSDL");
    //Tạo câu truy vấn
    $sql_del_hangxs="DELETE FROM thongso WHERE `thongso`.`mats` = $mats";
	mysqli_query($conn,$sql_del_hangxs);
	header("Location: thongso.php?masp=$masp");
	?>
</body>
</html>