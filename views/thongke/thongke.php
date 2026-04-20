<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/sanpham.css">
    <title>Thống kê doanh thu</title>
</head>
<body>
<?php
// Khởi tạo mặc định
$dayChecked          = false;
$monthChecked        = false;
$yearChecked         = false;
$day                 = '';
$month               = '';
$year                = '';
$phuongThucThanhToan = 'Tất cả';
$trangThaiThanhToan  = 'Tất cả';

$extra_css = '<link rel="stylesheet" href="../../assets/css/footer.css">';
include "../../includes/header.php";
include "../../includes/footer.php";
include "../../model/thongke_model.php";

// Xây filters từ POST
$filters = [];
if (isset($_POST['dayChecked']) && $_POST['dayChecked']) {
    $dayChecked      = true;
    $day             = (int)$_POST['day'];
    $filters['day']  = $day;
}
if (isset($_POST['monthChecked']) && $_POST['monthChecked']) {
    $monthChecked      = true;
    $month             = (int)$_POST['month'];
    $filters['month']  = $month;
}
if (isset($_POST['yearChecked']) && $_POST['yearChecked']) {
    $yearChecked      = true;
    $year             = (int)$_POST['year'];
    $filters['year']  = $year;
}
if (isset($_POST['phuongThuc'])) {
    $phuongThucThanhToan  = $_POST['phuongThuc'];
    if ($phuongThucThanhToan != 'Tất cả')
        $filters['phuongThuc'] = $phuongThucThanhToan;
}
if (isset($_POST['trangThai'])) {
    $trangThaiThanhToan  = $_POST['trangThai'];
    if ($trangThaiThanhToan != 'Tất cả')
        $filters['trangThai'] = $trangThaiThanhToan;
}

// Gọi qua Model → REST API → DB
$rows    = ThongKe::getThongKe($filters);
$tong_bg = count($rows);
$tong_tien = array_sum(array_column($rows, 'sotien'));
?>

<div class="filters">
    <h3>Lọc theo:</h3>
    <h4>Thời gian thanh toán:</h4><hr>
    <form action="thongke.php" method="post">

        <input type="checkbox" name="dayChecked" id="dayChecked" <?php if ($dayChecked) echo 'checked'; ?>>
        <label for="dayChecked">Ngày</label>
        <input type="number" name="day" id="day" value="<?php echo $day; ?>">
        <br>

        <input type="checkbox" name="monthChecked" id="monthChecked" <?php if ($monthChecked) echo 'checked'; ?>>
        <label for="monthChecked">Tháng</label>
        <input type="number" name="month" id="month" value="<?php echo $month; ?>">
        <br>

        <input type="checkbox" name="yearChecked" id="yearChecked" <?php if ($yearChecked) echo 'checked'; ?>>
        <label for="yearChecked">Năm</label>
        <input type="number" name="year" id="year" value="<?php echo $year; ?>">
        <br>

        <h4>Phương thức thanh toán:</h4><hr>
        <select name="phuongThuc" id="phuongThuc">
            <option value="Tất cả" <?php if ($phuongThucThanhToan == 'Tất cả') echo 'selected'; ?>>Tất cả</option>
            <option value="Chuyển khoản" <?php if ($phuongThucThanhToan == 'Chuyển khoản') echo 'selected'; ?>>Chuyển khoản</option>
            <option value="Tiền mặt" <?php if ($phuongThucThanhToan == 'Tiền mặt') echo 'selected'; ?>>Tiền mặt</option>
            <option value="Thẻ" <?php if ($phuongThucThanhToan == 'Thẻ') echo 'selected'; ?>>Thẻ</option>
            <option value="Ví điện tử" <?php if ($phuongThucThanhToan == 'Ví điện tử') echo 'selected'; ?>>Ví điện tử</option>
        </select>

        <h4 style="margin-top: 10px;">Trạng thái thanh toán:</h4><hr>
        <select name="trangThai" id="trangThai">
            <option value="Tất cả" <?php if ($trangThaiThanhToan == 'Tất cả') echo 'selected'; ?>>Tất cả</option>
            <option value="Chờ xác nhận" <?php if ($trangThaiThanhToan == 'Chờ xác nhận') echo 'selected'; ?>>Chờ xác nhận</option>
            <option value="Đã thanh toán" <?php if ($trangThaiThanhToan == 'Đã thanh toán') echo 'selected'; ?>>Đã thanh toán</option>
            <option value="Thất bại" <?php if ($trangThaiThanhToan == 'Thất bại') echo 'selected'; ?>>Thất bại</option>
        </select>

        <input type="submit" value="Lọc" style="margin-top: 10px;">
    </form>
</div>

<h1 align="center">THỐNG KÊ DOANH THU</h1>
<table>
    <tr>
        <th>STT</th>
        <th>Khách hàng</th>
        <th>Nhân viên</th>
        <th>Phương thức</th>
        <th>Ngày thanh toán</th>
        <th>Số tiền</th>
        <th>Trạng thái</th>
        <th>Ghi chú</th>
    </tr>

    <?php if ($tong_bg > 0): ?>
        <?php foreach ($rows as $i => $row): ?>
        <tr align="center">
            <td><?php echo $i + 1; ?></td>
            <td><?php echo htmlspecialchars($row['tenkh']); ?></td>
            <td><?php echo htmlspecialchars($row['tennv']); ?></td>
            <td><?php echo htmlspecialchars($row['phuongthuc']); ?></td>
            <td><?php echo date('d/m/Y H:i', strtotime($row['ngaythanhtoan'])); ?></td>
            <td><?php echo number_format($row['sotien'], 0, ',', '.'); ?> đ</td>
            <td><?php echo htmlspecialchars($row['trangthai']); ?></td>
            <td><?php echo htmlspecialchars($row['ghichu']); ?></td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>

    <tr>
        <td colspan="8" align="right">
            <?php if ($tong_bg > 0): ?>
                Tổng thu: <?php echo number_format($tong_tien, 0, ',', '.'); ?> đ
            <?php else: ?>
                Không có hóa đơn nào được tìm thấy!
            <?php endif; ?>
        </td>
    </tr>
</table>

</body>
</html>