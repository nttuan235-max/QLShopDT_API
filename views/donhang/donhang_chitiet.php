<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
$extra_css = '<link rel="stylesheet" href="/QLShopDT_API/assets/css/chitietdonhang.css">';

include "../../includes/header.php";
include "../../includes/api_helper.php";
include "../../includes/footer.php";
include "../../model/donhang_model.php";

$madh = isset($_REQUEST['madh']) ? $_REQUEST['madh'] : 0;

if (empty($madh)) {
    echo "<script>alert('Không tìm thấy đơn hàng!'); window.location.href='donhang.php';</script>";
    exit();
}

// Lấy thông tin đơn hàng qua API
$result_dh = callDonhangAPI([
    "action" => "getone",
    "madh"   => $madh
]);

if (!($result_dh && $result_dh['status'])) {
    echo "<script>alert('Không tìm thấy đơn hàng!'); window.location.href='donhang.php';</script>";
    exit();
}
$donhang = $result_dh['data'];

// Lấy chi tiết đơn hàng qua API
$result_ct = callDonhangAPI([
    "action" => "getchitiet",
    "madh"   => $madh
]);
$chitiet = ($result_ct && $result_ct['status']) ? $result_ct['data'] : [];
?>

<h1 align="center">CHI TIẾT ĐƠN HÀNG</h1>
<link rel="stylesheet" href="/QLShopDT_API/assets/css/footer.css">
<table width="900" align="center" border="1">
    <tr>
        <th colspan="2"><strong>Thông tin đơn hàng</strong></th>
    </tr>
    <tr>
        <td>Mã đơn hàng</td>
        <td><?php echo htmlspecialchars($donhang['madh']); ?></td>
    </tr>
    <tr>
        <td>Tên khách hàng</td>
        <td><?php echo htmlspecialchars($donhang['tenkh']); ?></td>
    </tr>
    <tr>
        <td>Địa chỉ</td>
        <td><?php echo htmlspecialchars($donhang['diachi']); ?></td>
    </tr>
    <tr>
        <td>Số điện thoại</td>
        <td><?php echo htmlspecialchars($donhang['sdt']); ?></td>
    </tr>
    <tr>
        <td>Ngày đặt</td>
        <td><?php echo date('d/m/Y', strtotime($donhang['ngaydat'])); ?></td>
    </tr>
    <tr>
        <td>Tổng tiền</td>
        <td><?php echo number_format($donhang['trigia']); ?> VNĐ</td>
    </tr>
</table>

<h2 align="center">Chi tiết sản phẩm</h2>

<table width="900" align="center" border="1">
    <tr>
        <th>STT</th>
        <th>Tên sản phẩm</th>
        <th>Hãng</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Thành tiền</th>
    </tr>
    
    <?php if (count($chitiet) > 0): ?>
        <?php foreach ($chitiet as $i => $ct): ?>
            <tr align="center">
                <td><?php echo $i + 1; ?></td>
                <td><?php echo htmlspecialchars($ct['tensp']); ?></td>
                <td><?php echo htmlspecialchars($ct['hang']); ?></td>
                <td><?php echo number_format($ct['gia']); ?> VNĐ</td>
                <td><?php echo htmlspecialchars($ct['sl']); ?></td>
                <td><?php echo number_format($ct['gia'] * $ct['sl']); ?> VNĐ</td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" align="center">Không có sản phẩm trong đơn hàng này</td>
        </tr>
    <?php endif; ?>
</table>

<p align="center">
    <a href="donhang.php">Quay lại danh sách đơn hàng</a>
</p>
