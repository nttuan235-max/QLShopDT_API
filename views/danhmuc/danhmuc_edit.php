<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/nv.css">
    <title>Document</title>
</head>
<body>
    <h1 align = "center">SỬA DANH MỤC</h1>
    <?php
        include($_SERVER['DOCUMENT_ROOT'] . '/QLShopDT_API/api/db.php');
        require_once($_SERVER['DOCUMENT_ROOT'] . '/QLShopDT_API/api/db.php');;
        $sql_select = "Select * from `danhmuc` where `madm` = '$madm'";
        $result = mysqli_query($conn, $sql_select);
        $row = mysqli_fetch_object($result);

        $madm = $row->madm;
        $tendm = $row->tendm;
    ?>

    <form method="post" action="danhmuc_edit_save.php?madm= <?php echo $madm ?>" enctype="multipart/form-data">        
        <table align="center" border="1">
            <tr>
                <td colspan="2" align="center">Thêm danh mục</td>
            </tr>
            <tr>
                <td>Tên danh mục</td>
                <td>
                    <input type="text" name="txt_tendm"
                    value="<?php echo $tendm ?>">
                </td>
            </tr>
            
            <tr>
                <td colspan="2" align="center">
                <input type="submit" value="OK">
                <input type="reset" value="Reset">
                <input type="button" value="Quay lại" onclick="window.location.href='danhmuc.php'">
            </td>
            </tr>
        </table>
    </form>
</body>
</html>
