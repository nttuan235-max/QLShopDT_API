<?php
header("Content-Type: application/json");

// Kết nối database
include "db.php";

// Đọc dữ liệu từ input
$data   = json_decode(file_get_contents("php://input"), true);
$action = isset($data['action']) ? $data['action'] : '';

// XEM TẤT CẢ VẬN CHUYỂN
if ($action == 'getall') {
    $sql    = "SELECT vc.*, kh.tenkh, kh.diachi, kh.sdt, dh.ngaydat, dh.trigia
               FROM vanchuyen vc
               LEFT JOIN khachhang kh ON vc.makh = kh.makh
               LEFT JOIN donhang dh ON vc.madh = dh.madh
               ORDER BY vc.mavc ASC";
    $result = $conn->query($sql);

    if ($result) {
        $shipments = [];
        while ($row = $result->fetch_assoc()) {
            $shipments[] = $row;
        }
        echo json_encode([
            "status"  => true,
            "message" => "Lấy danh sách vận chuyển thành công",
            "data"    => $shipments,
            "total"   => count($shipments)
        ]);
    } else {
        echo json_encode([
            "status"  => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

// XEM CHI TIẾT 1 VẬN CHUYỂN
else if ($action == 'getone') {
    $mavc = $data['mavc'];

    $sql    = "SELECT vc.*, kh.tenkh, kh.diachi, kh.sdt, dh.ngaydat, dh.trigia
               FROM vanchuyen vc
               LEFT JOIN khachhang kh ON vc.makh = kh.makh
               LEFT JOIN donhang dh ON vc.madh = dh.madh
               WHERE vc.mavc = '$mavc'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        echo json_encode([
            "status"  => true,
            "message" => "Lấy thông tin vận chuyển thành công",
            "data"    => $result->fetch_assoc()
        ]);
    } else {
        echo json_encode([
            "status"  => false,
            "message" => "Không tìm thấy vận chuyển"
        ]);
    }
}

// THÊM VẬN CHUYỂN
else if ($action == 'add') {
    $madh      = $data['madh'];
    $makh      = $data['makh'];
    $ngaygiao  = $data['ngaygiao'];

    $sql = "INSERT INTO vanchuyen (madh, makh, ngaygiao)
            VALUES ('$madh', '$makh', '$ngaygiao')";

    if ($conn->query($sql)) {
        echo json_encode([
            "status"  => true,
            "message" => "Thêm vận chuyển thành công",
            "mavc"    => $conn->insert_id
        ]);
    } else {
        echo json_encode([
            "status"  => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

// CẬP NHẬT VẬN CHUYỂN
else if ($action == 'update') {
    $mavc      = $data['mavc'];
    $madh      = $data['madh'];
    $makh      = $data['makh'];
    $ngaygiao  = $data['ngaygiao'];

    $sql = "UPDATE vanchuyen
            SET madh='$madh', makh='$makh', ngaygiao='$ngaygiao'
            WHERE mavc='$mavc'";

    if ($conn->query($sql)) {
        echo json_encode([
            "status"  => true,
            "message" => "Cập nhật vận chuyển thành công"
        ]);
    } else {
        echo json_encode([
            "status"  => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

// XÓA VẬN CHUYỂN
else if ($action == 'delete') {
    $mavc = $data['mavc'];

    if ($conn->query("DELETE FROM vanchuyen WHERE mavc = '$mavc'")) {
        echo json_encode([
            "status"  => true,
            "message" => "Xóa vận chuyển thành công"
        ]);
    } else {
        echo json_encode([
            "status"  => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

// ACTION KHÔNG HỢP LỆ
else {
    echo json_encode([
        "status"  => false,
        "message" => "Action không hợp lệ. Sử dụng: getall, getone, add, update, delete"
    ]);
}

$conn->close();
?>
