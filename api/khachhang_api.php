<?php
header("Content-Type: application/json");

// Kết nối database
include "db.php";

// Đọc dữ liệu từ input
$data = json_decode(file_get_contents("php://input"), true);
$action = isset($data['action']) ? $data['action'] : '';

// XEM TẤT CẢ KHÁCH HÀNG
if($action == 'getall') {
    $sql = "SELECT kh.*, tk.tentk, tk.role 
            FROM khachhang kh 
            LEFT JOIN taikhoan tk ON kh.makh = tk.matk 
            ORDER BY kh.makh DESC";
    $result = $conn->query($sql);
    
    if($result) {
        $customers = [];
        while($row = $result->fetch_assoc()) {
            $customers[] = $row;
        }
        
        echo json_encode([
            "status" => true,
            "message" => "Lấy danh sách khách hàng thành công",
            "data" => $customers,
            "total" => count($customers)
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

// XEM 1 KHÁCH HÀNG
else if($action == 'getone') {
    $makh = $data['makh'];
    
    $sql = "SELECT kh.*, tk.tentk 
            FROM khachhang kh 
            LEFT JOIN taikhoan tk ON kh.makh = tk.matk 
            WHERE kh.makh = '$makh'";
    $result = $conn->query($sql);
    
    if($result && $result->num_rows > 0) {
        $customer = $result->fetch_assoc();
        
        echo json_encode([
            "status" => true,
            "message" => "Lấy thông tin khách hàng thành công",
            "data" => $customer
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Không tìm thấy khách hàng"
        ]);
    }
}

// CẬP NHẬT KHÁCH HÀNG
else if($action == 'update') {
    $makh = $data['makh'];
    $tenkh = $data['tenkh'];
    $diachi = isset($data['diachi']) ? $data['diachi'] : '';
    $sdt = isset($data['sdt']) ? $data['sdt'] : '';
    
    $sql = "UPDATE khachhang 
            SET tenkh = '$tenkh', diachi = '$diachi', sdt = '$sdt' 
            WHERE makh = '$makh'";
    
    if($conn->query($sql)) {
        echo json_encode([
            "status" => true,
            "message" => "Cập nhật khách hàng thành công"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

// XÓA KHÁCH HÀNG
else if($action == 'delete') {
    $makh = $data['makh'];
    
    // Xóa tài khoản sẽ tự động xóa khách hàng (CASCADE)
    $sql = "DELETE FROM taikhoan WHERE matk = '$makh'";
    
    if($conn->query($sql)) {
        echo json_encode([
            "status" => true,
            "message" => "Xóa khách hàng thành công"
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
        "message" => "Action không hợp lệ. Sử dụng: getall, getone, update, delete"
    ]);
}

$conn->close();
?>
