<?php
header('Content-Type: application/json');
include "db.php";

$data= json_decode(file_get_contents("php://input"), true);
$action = isset($data['action']) ? $data['action'] : '';
if ($action == 'getall') {
    $sql = "SELECT * FROM nhanvien ORDER BY manv DESC";
    $result = $conn->query($sql);
    if ($result) {
        $nhanviens = [];
        while ($row = $result->fetch_assoc()) {
            $nhanviens[] = $row;
        }
        echo json_encode([
            "status" => true,
            "message" => "Lấy danh sách nhân viên thành công",
            "data" => $nhanviens,
            "total" => count($nhanviens)
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}
else if ($action == 'getone') {
    $manv = $data['manv'];
    $sql = "SELECT * FROM nhanvien WHERE manv = '$manv'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo json_encode([
            "status" => true,
            "message" => "Lấy thông tin nhân viên thành công",
            "data" => $result->fetch_assoc()
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Không tìm thấy nhân viên"
        ]);
    }
}
else if ($action == 'add') {
    $tennv = $data['tennv'] ?? '';
    $sql = "INSERT INTO nhanvien (tennv) VALUES ('$tennv')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode([
            "status" => true,
            "message" => "Thêm nhân viên thành công",
            "manv" => $conn->insert_id
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

else if ($action == 'update') {
    $manv = $data['manv'];
    $tennv = $data['tennv'] ?? '';
    $sql = "UPDATE nhanvien SET tennv = '$tennv' WHERE manv = '$manv'";
    if ($conn->query($sql) === TRUE) {
        echo json_encode([
            "status" => true,
            "message" => "Cập nhật nhân viên thành công"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}
else if ($action == 'delete') {
    $manv = $data['manv'];
    $sql = "DELETE FROM nhanvien WHERE manv = '$manv'";
    if ($conn->query($sql) === TRUE) {
        echo json_encode([
            "status" => true,
            "message" => "Xóa nhân viên thành công"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

else {
    echo json_encode([
        "status" => false,
        "message" => "Hành động không hợp lệ"
    ]);
}