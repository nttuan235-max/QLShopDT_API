<?php
header("Content-Type: application/json");
include "db.php";

$method = $_SERVER['REQUEST_METHOD'];
$data   = json_decode(file_get_contents("php://input"), true);

switch ($method) {

    // ===================== GET =====================
    case 'GET':
        if (!empty($_GET['manv'])) {
            $manv   = (int)$_GET['manv'];
            $sql    = "SELECT nv.*, tk.tentk 
                       FROM nhanvien nv 
                       LEFT JOIN taikhoan tk ON nv.manv = tk.matk 
                       WHERE nv.manv = $manv";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                echo json_encode(["status" => true, "data" => $result->fetch_assoc()]);
            } else {
                echo json_encode(["status" => false, "message" => "Không tìm thấy nhân viên"]);
            }
        } else {
            $sql    = "SELECT nv.*, tk.tentk, tk.role 
                       FROM nhanvien nv 
                       LEFT JOIN taikhoan tk ON nv.manv = tk.matk 
                       ORDER BY nv.manv ASC";
            $result = $conn->query($sql);
            $rows   = [];
            while ($row = $result->fetch_assoc()) $rows[] = $row;
            echo json_encode(["status" => true, "data" => $rows, "total" => count($rows)]);
        }
        break;

    // ===================== POST =====================
    case 'POST':
        $tennv  = $conn->real_escape_string($data['tennv']  ?? '');
        $diachi = $conn->real_escape_string($data['diachi'] ?? '');
        $sdt    = $conn->real_escape_string($data['sdt']    ?? '');
        $ns     = $conn->real_escape_string($data['ns']     ?? '');

        if (empty($tennv)) {
            echo json_encode(["status" => false, "message" => "Tên nhân viên không được rỗng"]);
            break;
        }

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
        break;

    // ===================== PUT =====================
    case 'PUT':
        $manv   = (int)($data['manv']   ?? 0);
        $tennv  = $conn->real_escape_string($data['tennv']  ?? '');
        $diachi = $conn->real_escape_string($data['diachi'] ?? '');
        $sdt    = $conn->real_escape_string($data['sdt']    ?? '');
        $ns     = $conn->real_escape_string($data['ns']     ?? '');

        if (!$manv || empty($tennv)) {
            echo json_encode(["status" => false, "message" => "Thiếu manv hoặc tennv"]);
            break;
        }

        $sql = "UPDATE nhanvien 
                SET tennv='$tennv', diachi='$diachi', sdt='$sdt', ns='$ns' 
                WHERE manv=$manv";

        if ($conn->query($sql)) {
            echo json_encode(["status" => true, "message" => "Cập nhật nhân viên thành công"]);
        } else {
            echo json_encode(["status" => false, "message" => "Lỗi: " . $conn->error]);
        }
        break;

    // ===================== DELETE =====================
    case 'DELETE':
        $manv = (int)($_GET['manv'] ?? 0);

        if (!$manv) {
            echo json_encode(["status" => false, "message" => "Thiếu manv"]);
            break;
        }

        // Xóa tài khoản sẽ tự động xóa nhân viên (CASCADE)
        if ($conn->query("DELETE FROM taikhoan WHERE matk = $manv")) {
            echo json_encode(["status" => true, "message" => "Xóa nhân viên thành công"]);
        } else {
            echo json_encode(["status" => false, "message" => "Lỗi: " . $conn->error]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["status" => false, "message" => "Method không hỗ trợ"]);
        break;
}

$conn->close();
?>