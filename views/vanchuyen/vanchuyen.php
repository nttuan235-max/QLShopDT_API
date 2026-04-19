<?php
session_start();
include "../../includes/api_helper.php";
requireLogin();

$page_title = 'Quản lý Vận chuyển';
$active_nav = 'vanchuyen';
$extra_css = '<link rel="stylesheet" href="/QLShopDT_API/assets/css/footer.css">';
include "../../includes/header.php";
include "../../includes/footer.php";
include "../../model/vanchuyen_model.php";

// Kiểm tra quyền chỉnh sửa
$can_edit = isAdminOrStaff();

// Lấy danh sách vận chuyển từ model
$makh = null;
if (!isAdminOrStaff()) {
    // Khách hàng: lấy makh từ database
    include($_SERVER['DOCUMENT_ROOT'] . '/QLShopDT_API/api/db.php');
    $username = $_SESSION['username'];
    $sql_get_makh = "SELECT kh.makh FROM taikhoan tk
                     JOIN khachhang kh ON tk.matk = kh.makh
                     WHERE tk.tentk = '$username'";
    $result_makh = mysqli_query($conn, $sql_get_makh);
    if (mysqli_num_rows($result_makh) > 0) {
        $row_makh = mysqli_fetch_object($result_makh);
        $makh = $row_makh->makh;
    }
    mysqli_close($conn);
}

$vanchuyen_list = VanChuyen::getAllShipments($makh);
?>
<html>
    <link rel="stylesheet" href="/QLShopDT_API/assets/css/vanchuyen.css">
</html>
<h1 align="center">DANH SÁCH VẬN CHUYỂN</h1>

<table width="1300" align="center" border="1">
    <tr>
        <th>STT</th>
        <th>Mã vận chuyển</th>
        <th>Mã đơn hàng</th>
        <th>Tên khách hàng</th>
        <th>Địa chỉ giao hàng</th>
        <th>SĐT</th>
        <th>Ngày đặt</th>
            <th>Ngày giao</th>
        <th>Tổng tiền</th>
        <?php if ($can_edit): ?>
            <th width="180"><a href="vanchuyen_add.php">Thêm vận chuyển</a></th>
        <?php endif; ?>
        <th>Chi tiết</th>
    </tr>

    <?php if (empty($vanchuyen_list)): ?>
        <tr><td colspan="<?php echo $can_edit ? '10' : '9'; ?>" align="center">Không có thông tin vận chuyển nào</td></tr>
    <?php else: ?>
        <?php foreach ($vanchuyen_list as $i => $vc): ?>
            <tr align="center">
                <td><?php echo $i + 1; ?></td>
                <td><?php echo htmlspecialchars($vc['mavc']); ?></td>
                <td><?php echo htmlspecialchars($vc['madh']); ?></td>
                <td><?php echo htmlspecialchars($vc['tenkh']); ?></td>
                <td><?php echo htmlspecialchars($vc['diachi']); ?></td>
                <td><?php echo htmlspecialchars($vc['sdt']); ?></td>
                <td><?php echo date('d/m/Y', strtotime($vc['ngaydat'] ?? '')); ?></td>
                <td><?php echo date('d/m/Y', strtotime($vc['ngaygiao'])); ?></td>
                <td><?php echo number_format($vc['trigia'] ?? 0); ?> VNĐ</td>
                <?php if ($can_edit): ?>
                    <td>
                        <a href="vanchuyen_edit.php?mavc=<?php echo $vc['mavc']; ?>">Sửa</a> |
                        <a href="vanchuyen_del.php?mavc=<?php echo $vc['mavc']; ?>"
                           onclick="return confirm('Bạn có chắc muốn xóa vận chuyển này?')">Xóa</a>
                    </td>
                <?php endif; ?>
                <td>
                    <a href="../donhang/donhang_chitiet.php?madh=<?php echo $vc['madh']; ?>">Xem chi tiết</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>

    <tr>
        <td colspan="<?php echo $can_edit ? '10' : '9'; ?>" align="right">
            Bảng có <?php echo count($vanchuyen_list); ?> vận chuyển
        </td>
    </tr>
</table>

</body>
</html>
