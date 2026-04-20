<?php
header("Content-Type: application/json");
include "db.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT tt.*, dh.ngaydat, kh.tenkh, nv.tennv 
                FROM thanhtoan tt
                JOIN donhang dh ON tt.madh = dh.madh
                JOIN khachhang kh ON dh.makh = kh.makh
                JOIN nhanvien nv ON dh.manv = nv.manv
                WHERE 1=1";

        if (!empty($_GET['day']))
            $sql .= " AND DAY(dh.ngaydat) = " . (int)$_GET['day'];
        if (!empty($_GET['month']))
            $sql .= " AND MONTH(dh.ngaydat) = " . (int)$_GET['month'];
        if (!empty($_GET['year']))
            $sql .= " AND YEAR(dh.ngaydat) = " . (int)$_GET['year'];
        if (!empty($_GET['phuongThuc']) && $_GET['phuongThuc'] != 'Tất cả')
            $sql .= " AND tt.phuongthuc = '" . $conn->real_escape_string($_GET['phuongThuc']) . "'";
        if (!empty($_GET['trangThai']) && $_GET['trangThai'] != 'Tất cả')
            $sql .= " AND tt.trangthai = '" . $conn->real_escape_string($_GET['trangThai']) . "'";

        $result = $conn->query($sql);
        if ($result) {
            $rows = [];
            while ($row = $result->fetch_assoc()) $rows[] = $row;
            echo json_encode(["status" => true, "data" => $rows, "total" => count($rows)]);
        } else {
            echo json_encode(["status" => false, "message" => $conn->error]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["status" => false, "message" => "Method không hỗ trợ"]);
        break;
}
$conn->close();
?>