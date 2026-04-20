<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$page_title = 'Quản lý Đơn hàng';
$active_nav = 'donhang';
$extra_css = '<link rel="stylesheet" href="/QLShopDT_API/assets/css/footer.css">';
include "../../includes/header.php";
include "../../includes/api_helper.php";
include "../../includes/footer.php";
include "../../model/donhang_model.php";

// Lấy role từ session
$role = isset($_SESSION['role']) ? (int)$_SESSION['role'] : -1;
$username = $_SESSION['username'];

// Lấy danh sách đơn hàng
if ($role === 0) {
    // Khách hàng: lấy makh từ DB rồi lấy đơn hàng của khách hàng đó từ API
    include($_SERVER['DOCUMENT_ROOT'] . '/QLShopDT_API/api/db.php');
    $u = mysqli_real_escape_string($conn, $username);
    $res = mysqli_query($conn, "SELECT kh.makh FROM taikhoan tk JOIN khachhang kh ON tk.matk = kh.makh WHERE tk.tentk = '$u'");
    
    if (!$res || mysqli_num_rows($res) == 0) {
        echo "<script>alert('Không tìm thấy thông tin khách hàng!'); window.location.href='../trangchu.php';</script>";
        exit();
    }
    
    $row = mysqli_fetch_assoc($res);
    $makh = $row['makh'];
    $orders = DonHang::getOrdersByCustomer($makh);
} else {
    // Admin/Nhân viên: lấy tất cả đơn hàng
    $orders = DonHang::getAllOrders();
}
?>

<html>
    <link rel="stylesheet" href="/QLShopDT_API/assets/css/donhang.css">
</html>

<h1 align="center">QUẢN LÝ ĐƠN HÀNG</h1>

<table width="1500" align="center" border="1">
    <tr>
        <th>STT</th>
        <th>Mã đơn hàng</th>
        <th>Tên khách hàng</th>
        <th>Địa chỉ</th>
        <th>SĐT</th>
        <th>Ngày đặt</th>
        <th>Tổng tiền</th>
        <th>Chi tiết</th>
        <?php if ($role !== 0): ?>
            <th>Thao tác</th>
        <?php endif; ?>
    </tr>

    <?php if (count($orders) > 0): ?>
        <?php foreach ($orders as $i => $dh): ?>
            <tr align="center">
                <td><?php echo $i + 1; ?></td>
                <td><?php echo htmlspecialchars($dh['madh']); ?></td>
                <td><?php echo htmlspecialchars($dh['tenkh']); ?></td>
                <td><?php echo htmlspecialchars($dh['diachi']); ?></td>
                <td><?php echo htmlspecialchars($dh['sdt']); ?></td>
                <td><?php echo date('d/m/Y', strtotime($dh['ngaydat'])); ?></td>
                <td><?php echo number_format($dh['trigia']); ?> VNĐ</td>
                <td>
                    <a href="donhang_chitiet.php?madh=<?php echo htmlspecialchars($dh['madh']); ?>">Xem chi tiết</a>
                </td>
                <?php if ($role !== 0): ?>
                    <td>
                        <a href="donhang_edit.php?madh=<?php echo htmlspecialchars($dh['madh']); ?>">Cập nhật</a> |
                        <a href="donhang_del.php?madh=<?php echo htmlspecialchars($dh['madh']); ?>" 
                           onclick="return confirm('Bạn có chắc muốn xóa đơn hàng này?')">Xóa</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="<?php echo $role !== 0 ? '9' : '8'; ?>" align="center">Chưa có đơn hàng nào</td>
        </tr>
    <?php endif; ?>

    <tr>
        <td colspan="<?php echo $role !== 0 ? '9' : '8'; ?>" align="right">
            Tổng số: <strong><?php echo count($orders); ?></strong> đơn hàng
        </td>
    </tr>
</table>

</body>
</html>
