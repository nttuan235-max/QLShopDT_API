<?php
header('Content-Type: application/json');
include "db.php";

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {

    // GET - Lấy danh sách hoặc 1 nhân viên
    // GET /nhanvien_api.php        → lấy tất cả
    // GET /nhanvien_api.php?manv=1 → lấy 1
    case 'GET':
        if (isset($_GET['manv'])) {
            $manv = (int)$_GET['manv'];
            $sql = "SELECT * FROM nhanvien WHERE manv = $manv";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                echo json_encode([
                    "status" => true,
                    "data" => $result->fetch_assoc()
                ]);
            } else {
                echo json_encode(["status" => false, "message" => "Không tìm thấy nhân viên"]);
            }
        } else {
            $sql = "SELECT * FROM nhanvien ORDER BY manv DESC";
            $result = $conn->query($sql);
            $rows = [];
            while ($row = $result->fetch_assoc()) $rows[] = $row;
            echo json_encode([
                "status" => true,
                "data" => $rows,
                "total" => count($rows)
            ]);
        }
        break;

    // POST - Thêm nhân viên mới
    // Body: { "tennv": "Nguyen Van A" }
    case 'POST':
        $tennv = $conn->real_escape_string($data['tennv'] ?? '');
        if (empty($tennv)) {
            echo json_encode(["status" => false, "message" => "Tên nhân viên không được rỗng"]);
            break;
        }
        $sql = "INSERT INTO nhanvien (tennv) VALUES ('$tennv')";
        if ($conn->query($sql)) {
            echo json_encode([
                "status" => true,
                "message" => "Thêm nhân viên thành công",
                "manv" => $conn->insert_id
            ]);
        } else {
            echo json_encode(["status" => false, "message" => $conn->error]);
        }
        break;

    // PUT - Cập nhật nhân viên
    // Body: { "manv": 1, "tennv": "Nguyen Van B" }
    case 'PUT':
        $manv = (int)($data['manv'] ?? 0);
        $tennv = $conn->real_escape_string($data['tennv'] ?? '');
        if (!$manv || empty($tennv)) {
            echo json_encode(["status" => false, "message" => "Thiếu manv hoặc tennv"]);
            break;
        }
        $sql = "UPDATE nhanvien SET tennv = '$tennv' WHERE manv = $manv";
        if ($conn->query($sql)) {
            echo json_encode(["status" => true, "message" => "Cập nhật thành công"]);
        } else {
            echo json_encode(["status" => false, "message" => $conn->error]);
        }
        break;

    // DELETE - Xóa nhân viên
    // DELETE /nhanvien_api.php?manv=1
    case 'DELETE':
        $manv = (int)($_GET['manv'] ?? 0);
        if (!$manv) {
            echo json_encode(["status" => false, "message" => "Thiếu manv"]);
            break;
        }
        $sql = "DELETE FROM nhanvien WHERE manv = $manv";
        if ($conn->query($sql)) {
            echo json_encode(["status" => true, "message" => "Xóa thành công"]);
        } else {
            echo json_encode(["status" => false, "message" => $conn->error]);
        }
        break;

    default:
        echo json_encode(["status" => false, "message" => "Method không hỗ trợ"]);
        break;
}