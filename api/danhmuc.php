<?php
/**
 * RESTful API cho Danh mục
 * 
 * Endpoints:
 *   GET    /api/danhmuc           - Lấy danh sách danh mục
 *   GET    /api/danhmuc/{id}      - Lấy chi tiết danh mục
 *   POST   /api/danhmuc           - Tạo danh mục mới
 *   PUT    /api/danhmuc/{id}      - Cập nhật danh mục
 *   DELETE /api/danhmuc/{id}      - Xóa danh mục
 */

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];
$id = null;

if (preg_match('/\/api\/danhmuc\/(\d+)/', $request_uri, $matches)) {
    $id = (int)$matches[1];
}

switch ($method) {
    case 'GET':
        if ($id) {
            getDanhmuc($conn, $id);
        } else {
            getAllDanhmuc($conn);
        }
        break;
        
    case 'POST':
        createDanhmuc($conn);
        break;
        
    case 'PUT':
        if ($id) {
            updateDanhmuc($conn, $id);
        } else {
            sendError(400, "ID danh mục không được để trống");
        }
        break;
        
    case 'DELETE':
        if ($id) {
            deleteDanhmuc($conn, $id);
        } else {
            sendError(400, "ID danh mục không được để trống");
        }
        break;
        
    default:
        sendError(405, "Method không được hỗ trợ");
}

function getAllDanhmuc($conn) {
    $sql = "SELECT * FROM danhmuc ORDER BY madm ASC";
    $result = $conn->query($sql);
    
    if ($result) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        http_response_code(200);
        echo json_encode([
            "status" => true,
            "message" => "Lấy danh sách danh mục thành công",
            "data" => $data,
            "total" => count($data)
        ]);
    } else {
        sendError(500, "Lỗi truy vấn database: " . $conn->error);
    }
}

function getDanhmuc($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM danhmuc WHERE madm = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        http_response_code(200);
        echo json_encode([
            "status" => true,
            "message" => "Lấy thông tin danh mục thành công",
            "data" => $row
        ]);
    } else {
        sendError(404, "Không tìm thấy danh mục với ID: $id");
    }
    
    $stmt->close();
}

function createDanhmuc($conn) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['tendm']) || trim($data['tendm']) === '') {
        sendError(400, "Tên danh mục không được để trống");
        return;
    }
    
    $stmt = $conn->prepare("INSERT INTO danhmuc (tendm) VALUES (?)");
    $stmt->bind_param("s", $data['tendm']);
    
    if ($stmt->execute()) {
        $new_id = $conn->insert_id;
        http_response_code(201);
        echo json_encode([
            "status" => true,
            "message" => "Thêm danh mục thành công",
            "data" => ["madm" => $new_id]
        ]);
    } else {
        sendError(500, "Lỗi khi thêm danh mục: " . $stmt->error);
    }
    
    $stmt->close();
}

function updateDanhmuc($conn, $id) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $check = $conn->prepare("SELECT madm FROM danhmuc WHERE madm = ?");
    $check->bind_param("i", $id);
    $check->execute();
    if (!$check->get_result()->fetch_assoc()) {
        sendError(404, "Không tìm thấy danh mục với ID: $id");
        $check->close();
        return;
    }
    $check->close();
    
    $stmt = $conn->prepare("UPDATE danhmuc SET tendm = ? WHERE madm = ?");
    $stmt->bind_param("si", $data['tendm'], $id);
    
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            "status" => true,
            "message" => "Cập nhật danh mục thành công"
        ]);
    } else {
        sendError(500, "Lỗi khi cập nhật danh mục: " . $stmt->error);
    }
    
    $stmt->close();
}

function deleteDanhmuc($conn, $id) {
    $stmt = $conn->prepare("SELECT madm FROM danhmuc WHERE madm = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    if (!$stmt->get_result()->fetch_assoc()) {
        sendError(404, "Không tìm thấy danh mục với ID: $id");
        $stmt->close();
        return;
    }
    $stmt->close();
    
    $stmt = $conn->prepare("DELETE FROM danhmuc WHERE madm = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            "status" => true,
            "message" => "Xóa danh mục thành công"
        ]);
    } else {
        sendError(500, "Lỗi khi xóa danh mục: " . $stmt->error);
    }
    
    $stmt->close();
}

function sendError($code, $message) {
    http_response_code($code);
    echo json_encode([
        "status" => false,
        "error" => $message,
        "code" => $code
    ]);
}
