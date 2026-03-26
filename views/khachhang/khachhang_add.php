<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/nv.css">
    <title>Document</title>
</head>
<body>
    <h1 align = "center">THÊM KHÁCH HÀNG</h1>
    <?php
        include($_SERVER['DOCUMENT_ROOT'] . '/QLShopDT_API/api/db.php');
        require_once($_SERVER['DOCUMENT_ROOT'] . '/QLShopDT_API/api/db.php');
        $sql_select = "Select * from `khachhang`";
        $result = mysqli_query($conn,$sql_select);
        $tong = mysqli_num_rows($result);

        $stt = 0;
        while($row = mysqli_fetch_object($result))
        {
            $stt++;
            $makh[$stt] = $row->makh;
            $tenkh[$stt] = $row->tenkh;
            $diachi[$stt] = $row->diachi;
            $sdt[$stt] = $row->sdt;
        }
    ?>

    <form action="khachhang_insert.php" method="post" enctype="multipart/form-data">
        <table border="1" align="center">
            <tr>
                <td colspan="2" align="center">Thông tin khách hàng</td>
            </tr>
            <tr>
                <td>Tên khách hàng:</td>
                <td>
                    <input type="text" name="txt_tenkh">
                </td>
            </tr>
            <tr>
                <td>Địa chỉ:</td>
                <td>
                    <input type="text" name="txt_diachi">
                </td>
            </tr>
            <tr>
                <td>Số điện thoại:</td>
                <td>
                    <input type="text" name="txt_sdt">
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
