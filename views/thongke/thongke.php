<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/thongke.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <title>Thống kê doanh thu</title>
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
?>
</head>
<body>
    <?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/QLShopDT_API/config/database.php';
    include "../../includes/header.php";
<<<<<<< HEAD

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
=======
    include "../../includes/api_helper.php";
    
    $db = Database::getInstance();
    
    // Build query với prepared statements
    $sql_select = "SELECT tt.*, dh.ngaydat, kh.tenkh, nv.tennv 
                   FROM thanhtoan tt
                   JOIN donhang dh ON tt.madh = dh.madh
                   JOIN khachhang kh ON dh.makh = kh.makh
                   JOIN nhanvien nv ON dh.manv = nv.manv
                   WHERE 1=1";
    
    $params = [];
    $types = '';
 
    if (isset($_POST['dayChecked']) && $_POST['dayChecked']) {
        $dayChecked = true;
        $day = isset($_POST['day']) && $_POST['day'] !== '' ? (int)$_POST['day'] : 10;
        $sql_select .= " AND DAY(dh.ngaydat) = ?";
        $params[] = $day;
        $types .= 'i';
    }

    if (isset($_POST['monthChecked']) && $_POST['monthChecked']) {
        $monthChecked = true;
        $month = isset($_POST['month']) && $_POST['month'] !== '' ? (int)$_POST['month'] : 1;
        $sql_select .= " AND MONTH(dh.ngaydat) = ?";
        $params[] = $month;
        $types .= 'i';
    }

    if (isset($_POST['yearChecked']) && $_POST['yearChecked']) {
        $yearChecked = true;
        $year = isset($_POST['year']) && $_POST['year'] !== '' ? (int)$_POST['year'] : date('Y');
        $sql_select .= " AND YEAR(dh.ngaydat) = ?";
        $params[] = $year;
        $types .= 'i';
    }

    if (isset($_POST['phuongThuc']) && $_POST['phuongThuc'] !== "Tất cả") {
        $phuongThucThanhToan = $_POST['phuongThuc'];
        $sql_select .= " AND phuongThuc = ?";
        $params[] = $phuongThucThanhToan;
        $types .= 's';
    }

    if (isset($_POST['trangThai']) && $_POST['trangThai'] !== "Tất cả") {
        $trangThaiThanhToan = $_POST['trangThai'];
        $sql_select .= " AND trangThai = ?";
        $params[] = $trangThaiThanhToan;
        $types .= 's';
    }

    // Execute query
    $rows = $db->select($sql_select, $types, $params);
    $tong_bg = count($rows);
    
    $phuongthuc = [];
    $ngaythanhtoan = [];
    $sotien = [];
    $trangthai = [];
    $ghichu = [];
    $tenkh = [];
    $tennv = [];

    foreach ($rows as $i => $row) {
        $idx = $i + 1;
        $phuongthuc[$idx] = $row['phuongthuc'];
        $ngaythanhtoan[$idx] = $row['ngaythanhtoan'];
        $sotien[$idx] = $row['sotien'];
        $trangthai[$idx] = $row['trangthai'];
        $ghichu[$idx] = $row['ghichu'];
        $tenkh[$idx] = $row['tenkh'];
        $tennv[$idx] = $row['tennv'];
    }
    ?>
>>>>>>> dac04e628c9690cc1973fddf27fd33bc89a04ed4

<<<<<<< HEAD
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

=======
    <div class="filters">
        <h3>Lọc theo</h3>
        <form action="thongke.php" method="post">
            <div>
                <h4>Thời gian thanh toán</h4>
                <hr>
                <label>
                    <input type="checkbox" name="dayChecked" id="dayChecked" <?php if (isset($dayChecked) && $dayChecked) echo 'checked';?>>
                    <span>Ngày</span>
                </label>
                <input type="number" name="day" id="day" value="<?php echo isset($day) ? $day : ''; ?>" min="1" max="31">
            </div>

            <div>
                <h4>Tháng</h4>
                <hr>
                <label>
                    <input type="checkbox" name="monthChecked" id="monthChecked" <?php if (isset($monthChecked) && $monthChecked) echo 'checked';?>>
                    <span>Tháng</span>
                </label>
                <input type="number" name="month" id="month" value="<?php echo isset($month) ? $month : ''; ?>" min="1" max="12">
            </div>

            <div>
                <h4>Năm</h4>
                <hr>
                <label>
                    <input type="checkbox" name="yearChecked" id="yearChecked" <?php if (isset($yearChecked) && $yearChecked) echo 'checked';?>>
                    <span>Năm</span>
                </label>
                <input type="number" name="year" id="year" value="<?php echo isset($year) ? $year : ''; ?>" min="2000">
            </div>

            <div>
                <h4>Phương thức thanh toán</h4>
                <hr>
                <select name="phuongThuc" id="phuongThuc">
                    <option value="Tất cả" selected>Tất cả</option>
                    <option value="Chuyển khoản" <?php if (isset($phuongThucThanhToan) && $phuongThucThanhToan === "Chuyển khoản") echo "selected"; ?>>Chuyển khoản</option>
                    <option value="Tiền mặt" <?php if (isset($phuongThucThanhToan) && $phuongThucThanhToan === "Tiền mặt") echo "selected"; ?>>Tiền mặt</option>
                    <option value="Thẻ" <?php if (isset($phuongThucThanhToan) && $phuongThucThanhToan === "Thẻ") echo "selected"; ?>>Thẻ</option>
                    <option value="Ví điện tử" <?php if (isset($phuongThucThanhToan) && $phuongThucThanhToan === "Ví điện tử") echo "selected"; ?>>Ví điện tử</option>
                </select>
            </div>

            <div>
                <h4>Trạng thái thanh toán</h4>
                <hr>
                <select name="trangThai" id="trangThai">
                    <option value="Tất cả" selected>Tất cả</option>
                    <option value="Chờ xác nhận" <?php if (isset($trangThaiThanhToan) && $trangThaiThanhToan === "Chờ xác nhận") echo "selected"; ?>>Chờ xác nhận</option>
                    <option value="Đã thanh toán" <?php if (isset($trangThaiThanhToan) && $trangThaiThanhToan === "Đã thanh toán") echo "selected"; ?>>Đã thanh toán</option>
                    <option value="Thất bại" <?php if (isset($trangThaiThanhToan) && $trangThaiThanhToan === "Thất bại") echo "selected"; ?>>Thất bại</option>
                </select>
            </div>
            
            <input type="submit" value="Lọc">
        </form>
    </div>

    <h1>Thống kê doanh thu</h1>
    <table>
        <thead>
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
        </thead>
        <tbody>
        <?php
            for ($i=1; $i<=$tong_bg; $i++) {
        ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $tenkh[$i]; ?></td>
                <td><?php echo $tennv[$i]; ?></td>
                <td><?php echo $phuongthuc[$i]; ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($ngaythanhtoan[$i])); ?></td>
                <td><?php echo number_format($sotien[$i], 0, ',', '.'); ?> đ</td>
                <td><?php echo $trangthai[$i]; ?></td>
                <td><?php echo $ghichu[$i]; ?></td>
            </tr>
        <?php
            }
        ?>
            <tr>
                <td colspan="8">
                    <?php if (isset($sotien)) { 
                        echo "Tổng thu: " . number_format(array_sum($sotien), 0, ',', '.') . " đ";
                    } else { 
                        echo "Không có hóa đơn nào được tìm thấy!";
                    } ?>
                </td>
            </tr>
        </tbody>
    </table>

    <?php include "../../includes/footer.php"; ?>
>>>>>>> origin/main
</body>
</html>