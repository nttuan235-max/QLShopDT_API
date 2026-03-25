<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/nv.css">
    <title>Document</title>
</head>
<body>
    <h1 align = "center">THÊM DANH MỤC</h1>
    <form method="post" action="danhmuc_insert.php" enctype="multipart/form-data">        
        <table align="center" border="1">
            <tr>
                <td colspan="2" align="center">Thông tin danh mục</td>
            </tr>
            <tr>
                <td>Tên danh mục</td>
                <td>
                    <input type="text" name="txt_tendm">
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