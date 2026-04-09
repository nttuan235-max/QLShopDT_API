<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$page_title = 'Quản lý Sản phẩm';
$active_nav = 'sanpham';
$extra_css = '<link rel="stylesheet" href="/QLShopDT_API/assets/css/footer.css">';
include "../../includes/header.php";
include "../../includes/api_helper.php";
include "../../includes/footer.php";
include "../../model/sanpham_model.php";

// Lấy danh sách sản phẩm từ model
$sanpham_list = SanPham::getAllProducts();

// Lấy role từ session
$role = isset($_SESSION['role']) ? (int)$_SESSION['role'] : -1;
$can_edit = ($role === 1 || $role === 2); // Admin hoặc Nhân viên
?>
<html>
    <link rel="stylesheet" href="/QLShopDT_API/assets/css/sanpham.css">
</html>
<h1 align="center">DANH SÁCH SẢN PHẨM</h1>

<table width="1300" align="center" border="1">
        <tr>
            <th>STT</th>
            <th width="250">Tên sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Hãng</th>
            <th>Bảo hành</th>
            <th>Hình ảnh</th>
            <th>Ghi chú</th>
            <th>Danh mục</th>
            <?php if ($can_edit): ?>
                <th width="180"><a href="sanpham_add.php">Thêm sản phẩm</a></th>
            <?php endif; ?>
            <th>Chi tiết sản phẩm</th>
        </tr>

        <?php if (empty($sanpham_list)): ?>
            <tr><td colspan="<?php echo $can_edit ? '11' : '10'; ?>" align="center">Không có sản phẩm nào</td></tr>
        <?php else: ?>
            <?php foreach ($sanpham_list as $i => $sp): ?>
                <tr align="center">
                    <td><?php echo $i + 1; ?></td>
                    <td><?php echo htmlspecialchars($sp['tensp']); ?></td>
                    <td><?php echo htmlspecialchars($sp['gia']); ?></td>
                    <td><?php echo htmlspecialchars($sp['sl']); ?></td>
                    <td><?php echo htmlspecialchars($sp['hang']); ?></td>
                    <td><?php echo htmlspecialchars($sp['baohanh']); ?></td>
                    <td><img src="/QLShopDT_API/includes/img/<?php echo htmlspecialchars($sp['hinhanh']); ?>" width="50"></td>
                    <td><?php echo htmlspecialchars($sp['ghichu']); ?></td>
                    <td><?php echo htmlspecialchars($sp['tendm']); ?></td>
                    <?php if ($can_edit): ?>
                        <td>
                            <a href="sanpham_edit.php?masp=<?php echo $sp['masp']; ?>">Sửa</a> |
                            <a href="sanpham_del.php?masp=<?php echo $sp['masp']; ?>"
                               onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">Xóa</a>
                        </td>
                    <?php endif; ?>
                    <td>
                        <a href="chitietsanpham.php?masp=<?php echo $sp['masp']; ?>">Xem chi tiết</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>

        <tr>
            <td colspan="<?php echo $can_edit ? '11' : '10'; ?>" align="right">
                Bảng có <?php echo count($sanpham_list); ?> sản phẩm
            </td>
        </tr>
    </table>

</body>
</html>