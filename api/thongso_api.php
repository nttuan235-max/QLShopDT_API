<?php
header("Content-Type: application/json");

// Kết nối database
include "db.php";

// Đọc dữ liệu từ input
$data   = json_decode(file_get_contents("php://input"), true);
$action = isset($data['action']) ? $data['action'] : '';

// ===================== XEM TẤT CẢ THÔNG SỐ THEO SẢN PHẨM =====================
if ($action == 'getall') {
    $masp = $conn->real_escape_string($data['masp'] ?? '');

    $sql = "SELECT ts.mats, ts.tents, ts.giatri, ts.masp, sp.tensp
            FROM thongso ts
            JOIN sanpham sp ON ts.masp = sp.masp
            WHERE ts.masp = '$masp'
            ORDER BY ts.mats ASC";
    $result = $conn->query($sql);

    if ($result) {
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        echo json_encode([
            "status"  => true,
            "message" => "Lấy danh sách thông số thành công",
            "data"    => $rows,
            "total"   => count($rows)
        ]);
    } else {
        echo json_encode(["status" => false, "message" => "Lỗi: " . $conn->error]);
    }
}

// ===================== XEM 1 THÔNG SỐ =====================
else if ($action == 'getone') {
    $mats = $conn->real_escape_string($data['mats'] ?? '');

    $sql = "SELECT ts.mats, ts.tents, ts.giatri, ts.masp, sp.tensp
            FROM thongso ts
            JOIN sanpham sp ON ts.masp = sp.masp
            WHERE ts.mats = '$mats'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        echo json_encode([
            "status"  => true,
            "message" => "Lấy thông tin thông số thành công",
            "data"    => $result->fetch_assoc()
        ]);
    } else {
        echo json_encode(["status" => false, "message" => "Không tìm thấy thông số"]);
    }
}

// ===================== THÊM THÔNG SỐ =====================
else if ($action == 'add') {
    $tents  = $conn->real_escape_string($data['tents']  ?? '');
    $masp   = $conn->real_escape_string($data['masp']   ?? '');
    $giatri = $conn->real_escape_string($data['giatri'] ?? '');

    $sql = "INSERT INTO thongso (mats, tents, masp, giatri) 
            VALUES (NULL, '$tents', '$masp', '$giatri')";

    if ($conn->query($sql)) {
        echo json_encode([
            "status"  => true,
            "message" => "Thêm thông số thành công",
            "mats"    => $conn->insert_id
        ]);
    } else {
        echo json_encode(["status" => false, "message" => "Lỗi: " . $conn->error]);
    }
}

// ===================== CẬP NHẬT THÔNG SỐ =====================
else if ($action == 'update') {
    $mats   = $conn->real_escape_string($data['mats']   ?? '');
    $tents  = $conn->real_escape_string($data['tents']  ?? '');
    $masp   = $conn->real_escape_string($data['masp']   ?? '');
    $giatri = $conn->real_escape_string($data['giatri'] ?? '');

    $sql = "UPDATE thongso 
            SET tents = '$tents', masp = '$masp', giatri = '$giatri' 
            WHERE mats = '$mats'";

    if ($conn->query($sql)) {
        echo json_encode(["status" => true, "message" => "Cập nhật thông số thành công"]);
    } else {
        echo json_encode(["status" => false, "message" => "Lỗi: " . $conn->error]);
    }
}

// ===================== XÓA THÔNG SỐ =====================
else if ($action == 'delete') {
    $mats = $conn->real_escape_string($data['mats'] ?? '');

    $sql = "DELETE FROM thongso WHERE mats = '$mats'";

    if ($conn->query($sql)) {
        echo json_encode(["status" => true, "message" => "Xóa thông số thành công"]);
    } else {
        echo json_encode(["status" => false, "message" => "Lỗi: " . $conn->error]);
    }
}

// ===================== LẤY DANH SÁCH SẢN PHẨM (cho dropdown) =====================
else if ($action == 'getsanpham') {
    $sql    = "SELECT masp, tensp FROM sanpham ORDER BY tensp ASC";
    $result = $conn->query($sql);

    if ($result) {
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        echo json_encode([
            "status" => true,
            "data"   => $rows
        ]);
    } else {
        echo json_encode(["status" => false, "message" => "Lỗi: " . $conn->error]);
    }
}

else {
    echo json_encode([
        "status"  => false,
        "message" => "Action không hợp lệ. Sử dụng: getall, getone, add, update, delete, getsanpham"
    ]);
}

$conn->close();
?>