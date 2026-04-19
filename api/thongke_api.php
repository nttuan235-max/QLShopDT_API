<?php
header('Content-Type: application/json');
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$action = isset($data['action']) ? $data['action'] : '';

if ($action == 'getthongke') {
    $sql = "SELECT tt.*, dh.ngaydat, kh.tenkh, nv.tennv 
            FROM thanhtoan tt
            JOIN donhang dh ON tt.madh = dh.madh
            JOIN khachhang kh ON dh.makh = kh.makh
            JOIN nhanvien nv ON dh.manv = nv.manv
            WHERE 1=1";

    if (!empty($data['day']))
        $sql .= " AND DAY(dh.ngaydat) = '" . (int)$data['day'] . "'";
    if (!empty($data['month']))
        $sql .= " AND MONTH(dh.ngaydat) = '" . (int)$data['month'] . "'";
    if (!empty($data['year']))
        $sql .= " AND YEAR(dh.ngaydat) = '" . (int)$data['year'] . "'";
    if (!empty($data['phuongThuc']) && $data['phuongThuc'] != 'Tất cả')
        $sql .= " AND tt.phuongthuc = '" . $conn->real_escape_string($data['phuongThuc']) . "'";
    if (!empty($data['trangThai']) && $data['trangThai'] != 'Tất cả')
        $sql .= " AND tt.trangthai = '" . $conn->real_escape_string($data['trangThai']) . "'";

    $result = $conn->query($sql);
    if ($result) {
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode(["status" => true, "data" => $rows, "total" => count($rows)]);
    } else {
        echo json_encode(["status" => false, "message" => $conn->error]);
    }
} else {
    echo json_encode(["status" => false, "message" => "Hành động không hợp lệ"]);
}