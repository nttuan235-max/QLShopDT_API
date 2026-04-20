<?php
header("Content-Type: application/json");
include "db.php";

$data   = json_decode(file_get_contents("php://input"), true);
$action = isset($data['action']) ? $data['action'] : '';

// ===================== XEM TẤT CẢ NHÂN VIÊN =====================
if ($action == 'getall') {
    $sql    = "SELECT nv.*, tk.tentk, tk.role 
               FROM nhanvien nv 
               LEFT JOIN taikhoan tk ON nv.manv = tk.matk 
               ORDER BY nv.manv ASC";
    $result = $conn->query($sql);

    if ($result) {
        $employees = [];
        while ($row = $result->fetch_assoc()) $employees[] = $row;
        echo json_encode([
            "status"  => true,
            "message" => "Lấy danh sách nhân viên thành công",
            "data"    => $employees,
            "total"   => count($employees)
        ]);
    } else {
        echo json_encode(["status" => false, "message" => "Lỗi: " . $conn->error]);
    }
}

// ===================== XEM 1 NHÂN VIÊN =====================
else if ($action == 'getone') {
    $manv   = $conn->real_escape_string($data['manv']);
    $sql    = "SELECT nv.*, tk.tentk 
               FROM nhanvien nv 
               LEFT JOIN taikhoan tk ON nv.manv = tk.matk 
               WHERE nv.manv = '$manv'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        echo json_encode([
            "status"  => true,
            "message" => "Lấy thông tin nhân viên thành công",
            "data"    => $result->fetch_assoc()
        ]);
    } else {
        echo json_encode(["status" => false, "message" => "Không tìm thấy nhân viên"]);
    }
}

// ===================== THÊM NHÂN VIÊN =====================
else if ($action == 'add') {
    $tennv  = $conn->real_escape_string($data['tennv']  ?? '');
    $diachi = $conn->real_escape_string($data['diachi'] ?? '');
    $sdt    = $conn->real_escape_string($data['sdt']    ?? '');
    $ns     = $conn->real_escape_string($data['ns']     ?? '');

    // Tạo tài khoản trước, role = 2 (nhân viên)
    $sql_tk = "INSERT INTO taikhoan VALUES (null, '$tennv', '123456', '2')";

    if ($conn->query($sql_tk)) {
        $id     = $conn->insert_id;
        $sql_nv = "INSERT INTO nhanvien (manv, tennv, diachi, sdt, ns) 
                   VALUES ('$id', '$tennv', '$diachi', '$sdt', '$ns')";

        if ($conn->query($sql_nv)) {
            echo json_encode([
                "status"  => true,
                "message" => "Thêm nhân viên thành công",
                "manv"    => $id
            ]);
        } else {
            $conn->query("DELETE FROM taikhoan WHERE matk = $id");
            echo json_encode(["status" => false, "message" => "Lỗi thêm nhân viên: " . $conn->error]);
        }
    } else {
        echo json_encode(["status" => false, "message" => "Lỗi tạo tài khoản: " . $conn->error]);
    }
}

// ===================== CẬP NHẬT NHÂN VIÊN =====================
else if ($action == 'update') {
    $manv   = $conn->real_escape_string($data['manv']);
    $tennv  = $conn->real_escape_string($data['tennv']  ?? '');
    $diachi = $conn->real_escape_string($data['diachi'] ?? '');
    $sdt    = $conn->real_escape_string($data['sdt']    ?? '');
    $ns     = $conn->real_escape_string($data['ns']     ?? '');

    $sql = "UPDATE nhanvien 
            SET tennv='$tennv', diachi='$diachi', sdt='$sdt', ns='$ns' 
            WHERE manv='$manv'";

    if ($conn->query($sql)) {
        echo json_encode(["status" => true, "message" => "Cập nhật nhân viên thành công"]);
    } else {
        echo json_encode(["status" => false, "message" => "Lỗi: " . $conn->error]);
    }
}

// ===================== XÓA NHÂN VIÊN =====================
else if ($action == 'delete') {
    $manv = $conn->real_escape_string($data['manv']);

    // Xóa tài khoản sẽ tự động xóa nhân viên (CASCADE)
    $sql = "DELETE FROM taikhoan WHERE matk = '$manv'";

    if ($conn->query($sql)) {
        echo json_encode(["status" => true, "message" => "Xóa nhân viên thành công"]);
    } else {
        echo json_encode(["status" => false, "message" => "Lỗi: " . $conn->error]);
    }
}

else {
    echo json_encode([
        "status"  => false,
        "message" => "Action không hợp lệ. Sử dụng: getall, getone, add, update, delete"
    ]);
}

$conn->close();
?>