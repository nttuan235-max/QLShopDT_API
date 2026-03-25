<?php
/**
 * RESTful API cho Sản phẩm
 * 
 * Endpoints:
 *   GET    /api/sanpham           - Lấy danh sách sản phẩm
 *   GET    /api/sanpham/{id}      - Lấy chi tiết sản phẩm
 *   POST   /api/sanpham           - Tạo sản phẩm mới
 *   PUT    /api/sanpham/{id}      - Cập nhật sản phẩm
 *   DELETE /api/sanpham/{id}      - Xóa sản phẩm
 */

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Xử lý preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/db.php';

// Lấy method và ID từ URL
$method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];
$id = null;

// Parse ID từ URL (ví dụ: /api/sanpham/5)
if (preg_match('/\/api\/sanpham\/(\d+)/', $request_uri, $matches)) {
    $id = (int)$matches[1];
}

// Router
switch ($method) {
    case 'GET':
        if ($id) {
            getSanpham($conn, $id);
        } else {
            getAllSanpham($conn);
        }
        break;
        
    case 'POST':
        createSanpham($conn);
        break;
        
    case 'PUT':
        if ($id) {
            updateSanpham($conn, $id);
        } else {
            sendError(400, "ID sản phẩm không được để trống");
        }
        break;
        
    case 'DELETE':
        if ($id) {
            deleteSanpham($conn, $id);
        } else {
            sendError(400, "ID sản phẩm không được để trống");
        }
        break;
        
    default:
        sendError(405, "Method không được hỗ trợ");
}

// ========== FUNCTIONS ==========

/**
 * Lấy danh sách tất cả sản phẩm
 */
function getAllSanpham($conn) {
    $sql = "SELECT sp.masp, sp.tensp, sp.gia, sp.sl, sp.hang, sp.baohanh, 
                   sp.ghichu, sp.hinhanh, sp.madm, dm.tendm
            FROM sanpham sp
            LEFT JOIN danhmuc dm ON sp.madm = dm.madm
            ORDER BY sp.masp DESC";
    
    $result = $conn->query($sql);
    
    if ($result) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        http_response_code(200);
        echo json_encode([
            "status" => true,
            "message" => "Lấy danh sách sản phẩm thành công",
            "data" => $data,
            "total" => count($data)
        ]);
    } else {
        sendError(500, "Lỗi truy vấn database: " . $conn->error);
    }
}

/**
 * Lấy chi tiết 1 sản phẩm
 */
function getSanpham($conn, $id) {
    $stmt = $conn->prepare("
        SELECT sp.masp, sp.tensp, sp.gia, sp.sl, sp.hang, sp.baohanh, 
               sp.ghichu, sp.hinhanh, sp.madm, dm.tendm
        FROM sanpham sp
        LEFT JOIN danhmuc dm ON sp.madm = dm.madm
        WHERE sp.masp = ?
    ");
    
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        http_response_code(200);
        echo json_encode([
            "status" => true,
            "message" => "Lấy thông tin sản phẩm thành công",
            "data" => $row
        ]);
    } else {
        sendError(404, "Không tìm thấy sản phẩm với ID: $id");
    }
    
    $stmt->close();
}

/**
 * Tạo sản phẩm mới
 */
function createSanpham($conn) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Validate input
    $required = ['tensp', 'gia', 'sl', 'hang', 'baohanh', 'madm'];
    foreach ($required as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            sendError(400, "Thiếu trường bắt buộc: $field");
            return;
        }
    }
    
    $stmt = $conn->prepare("
        INSERT INTO sanpham (tensp, gia, sl, hang, baohanh, ghichu, hinhanh, madm) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $ghichu = $data['ghichu'] ?? '';
    $hinhanh = $data['hinhanh'] ?? '';
    
    $stmt->bind_param(
        "sdisissi",
        $data['tensp'],
        $data['gia'],
        $data['sl'],
        $data['hang'],
        $data['baohanh'],
        $ghichu,
        $hinhanh,
        $data['madm']
    );
    
    if ($stmt->execute()) {
        $new_id = $conn->insert_id;
        http_response_code(201);
        echo json_encode([
            "status" => true,
            "message" => "Thêm sản phẩm thành công",
            "data" => ["masp" => $new_id]
        ]);
    } else {
        sendError(500, "Lỗi khi thêm sản phẩm: " . $stmt->error);
    }
    
    $stmt->close();
}

/**
 * Cập nhật sản phẩm
 */
function updateSanpham($conn, $id) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Kiểm tra sản phẩm có tồn tại không
    $check = $conn->prepare("SELECT masp FROM sanpham WHERE masp = ?");
    $check->bind_param("i", $id);
    $check->execute();
    if (!$check->get_result()->fetch_assoc()) {
        sendError(404, "Không tìm thấy sản phẩm với ID: $id");
        $check->close();
        return;
    }
    $check->close();
    
    $stmt = $conn->prepare("
        UPDATE sanpham 
        SET tensp = ?, gia = ?, sl = ?, hang = ?, 
            baohanh = ?, ghichu = ?, hinhanh = ?, madm = ?
        WHERE masp = ?
    ");
    
    $stmt->bind_param(
        "sdisissii",
        $data['tensp'],
        $data['gia'],
        $data['sl'],
        $data['hang'],
        $data['baohanh'],
        $data['ghichu'],
        $data['hinhanh'],
        $data['madm'],
        $id
    );
    
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            "status" => true,
            "message" => "Cập nhật sản phẩm thành công"
        ]);
    } else {
        sendError(500, "Lỗi khi cập nhật sản phẩm: " . $stmt->error);
    }
    
    $stmt->close();
}

/**
 * Xóa sản phẩm
 */
function deleteSanpham($conn, $id) {
    // Lấy thông tin ảnh trước khi xóa
    $stmt = $conn->prepare("SELECT hinhanh FROM sanpham WHERE masp = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if (!($row = $result->fetch_assoc())) {
        sendError(404, "Không tìm thấy sản phẩm với ID: $id");
        $stmt->close();
        return;
    }
    
    $hinhanh = $row['hinhanh'];
    $stmt->close();
    
    // Xóa sản phẩm
    $stmt = $conn->prepare("DELETE FROM sanpham WHERE masp = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Xóa file ảnh nếu tồn tại
        if ($hinhanh) {
            $imagePath = __DIR__ . "/../img/" . $hinhanh;
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }
        }
        
        http_response_code(200);
        echo json_encode([
            "status" => true,
            "message" => "Xóa sản phẩm thành công"
        ]);
    } else {
        sendError(500, "Lỗi khi xóa sản phẩm: " . $stmt->error);
    }
    
    $stmt->close();
}

/**
 * Gửi error response
 */
function sendError($code, $message) {
    http_response_code($code);
    echo json_encode([
        "status" => false,
        "error" => $message,
        "code" => $code
    ]);
}
