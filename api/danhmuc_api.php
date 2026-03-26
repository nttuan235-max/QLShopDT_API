<?php
header("Content-Type: application/json");

// Kết nối database
include "db.php";

// Đọc dữ liệu từ input
$data = json_decode(file_get_contents("php://input"), true);
$action = isset($data['action']) ? $data['action'] : '';

// XEM TẤT CẢ DANH MỤC
if($action == 'getall') {
    $sql = "SELECT * FROM danhmuc ORDER BY madm ASC";
    $result = $conn->query($sql);
    
    if($result) {
        $categories = [];
        while($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        
        echo json_encode([
            "status" => true,
            "message" => "Lấy danh sách danh mục thành công",
            "data" => $categories,
            "total" => count($categories)
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

// XEM 1 DANH MỤC
else if($action == 'getone') {
    $madm = $data['madm'];
    
    $sql = "SELECT * FROM danhmuc WHERE madm = '$madm'";
    $result = $conn->query($sql);
    
    if($result && $result->num_rows > 0) {
        $category = $result->fetch_assoc();
        
        echo json_encode([
            "status" => true,
            "message" => "Lấy thông tin danh mục thành công",
            "data" => $category
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Không tìm thấy danh mục"
        ]);
    }
}

// THÊM DANH MỤC
else if($action == 'add') {
    $tendm = $data['tendm'];
    
    $sql = "INSERT INTO danhmuc (tendm) VALUES ('$tendm')";
    
    if($conn->query($sql)) {
        $new_id = $conn->insert_id;
        
        echo json_encode([
            "status" => true,
            "message" => "Thêm danh mục thành công",
            "madm" => $new_id
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

// CẬP NHẬT DANH MỤC
else if($action == 'update') {
    $madm = $data['madm'];
    $tendm = $data['tendm'];
    
    $sql = "UPDATE danhmuc SET tendm = '$tendm' WHERE madm = '$madm'";
    
    if($conn->query($sql)) {
        echo json_encode([
            "status" => true,
            "message" => "Cập nhật danh mục thành công"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

// XÓA DANH MỤC
else if($action == 'delete') {
    $madm = $data['madm'];
    
    $sql = "DELETE FROM danhmuc WHERE madm = '$madm'";
    
    if($conn->query($sql)) {
        echo json_encode([
            "status" => true,
            "message" => "Xóa danh mục thành công"
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
        "message" => "Action không hợp lệ. Sử dụng: getall, getone, add, update, delete"
    ]);
}

$conn->close();
?>
