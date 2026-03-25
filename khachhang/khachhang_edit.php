<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/nv.css">
    <title>Document</title>
</head>
<body>
    <h1 align = "center">SỬA KHÁCH HÀNG</h1>
    <?php
        include($_SERVER['DOCUMENT_ROOT'] . '/QLShopDT_API/api/db.php');
        require_once($_SERVER['DOCUMENT_ROOT'] . '/QLShopDT_API/api/db.php');
        $makh = $_REQUEST["makh"];
        //Chọn CSDL để làm việc
        mysqli_select_db($conn,"qlshopdienthoai") or die ("Không tìm thấy CSDL");
        $sql_select = "Select * from `khachhang` where `makh` = '$makh'";
        $result = mysqli_query($conn, $sql_select);
        $row = mysqli_fetch_object($result);

        $makh = $row->makh;
        $tenkh = $row->tenkh;
        $diachi = $row->diachi;
        $sdt = $row->sdt;
    ?>

    <form method="post" action="khachhang_edit_save.php?makh= <?php echo $makh?>" enctype="multipart/form-data">        
        <form action="khachhang_insert.php" method="post" enctype="multipart/form-data">
        <table border="1" align="center">
            <tr>
                <td colspan="2" align="center">Thông tin khách hàng</td>
            </tr>
            <tr>
                <td>Tên khách hàng:</td>
                <td>
                    <input type="text" name="txt_tenkh"
                    value="<?php echo $tenkh ?>">
                </td>
            </tr>
            <tr>
                <td>Địa chỉ:</td>
                <td>
                    <input type="text" name="txt_diachi"
                    value="<?php echo $diachi ?>">
                </td>
            </tr>
            <tr>
                <td>Số điện thoại:</td>
                <td>
                    <input type="text" name="txt_sdt"
                    value="<?php echo $sdt ?>">
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <input type="submit" value="OK">
                <input type="reset" value="Reset">
                <input type="button" value="Quay lại" onclick="window.location.href='khachhang.php'">
                </td>
            </tr>
        </table>
    </form>
</body>
</html>