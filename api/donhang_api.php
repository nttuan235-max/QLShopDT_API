<?php
header("Content-Type: application/json");

// Kết nối database
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$action = isset($data['action']) ? $data['action'] : '';

// XEM TẤT CẢ ĐƠN HÀNG
if($action == 'getall') {
    $sql = "SELECT dh.*, kh.tenkh, kh.diachi, kh.sdt, nv.tennv 
            FROM donhang dh 
            LEFT JOIN khachhang kh ON dh.makh = kh.makh 
            LEFT JOIN nhanvien nv ON dh.manv = nv.manv 
            ORDER BY dh.madh DESC";
    $result = $conn->query($sql);
    
    if($result) {
        $orders = [];
        while($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        
        echo json_encode([
            "status" => true,
            "message" => "Lấy danh sách đơn hàng thành công",
            "data" => $orders,
            "total" => count($orders)
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

// XEM ĐƠN HÀNG CỦA KHÁCH HÀNG
else if($action == 'getbycustomer') {
    $makh = $data['makh'];
    $sql = "SELECT dh.*, kh.tenkh, kh.diachi, kh.sdt, nv.tennv 
            FROM donhang dh 
            LEFT JOIN khachhang kh ON dh.makh = kh.makh 
            LEFT JOIN nhanvien nv ON dh.manv = nv.manv 
            WHERE dh.makh = '$makh'
            ORDER BY dh.madh DESC";
    $result = $conn->query($sql);
    
    if($result) {
        $orders = [];
        while($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        
        echo json_encode([
            "status" => true,
            "message" => "Lấy danh sách đơn hàng thành công",
            "data" => $orders,
            "total" => count($orders)
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

// XEM 1 ĐƠN HÀNG (KÈM CHI TIẾT)
else if($action == 'getone') {
    $madh = $data['madh'];
    
    // Lấy thông tin đơn hàng
    $sql = "SELECT dh.*, kh.tenkh, kh.diachi, kh.sdt, nv.tennv 
            FROM donhang dh 
            LEFT JOIN khachhang kh ON dh.makh = kh.makh 
            LEFT JOIN nhanvien nv ON dh.manv = nv.manv 
            WHERE dh.madh = '$madh'";
    $result = $conn->query($sql);
    
    if($result && $result->num_rows > 0) {
        $order = $result->fetch_assoc();
        
        // Lấy chi tiết đơn hàng
        $sql_detail = "SELECT ct.*, sp.tensp, sp.gia, sp.hinhanh, sp.hang
                       FROM chitietdonhang ct 
                       LEFT JOIN sanpham sp ON ct.masp = sp.masp 
                       WHERE ct.madh = '$madh'";
        $result_detail = $conn->query($sql_detail);
        
        $details = [];
        if($result_detail) {
            while($row = $result_detail->fetch_assoc()) {
                $details[] = $row;
            }
        }
        
        $order['chitiet'] = $details;
        
        echo json_encode([
            "status" => true,
            "message" => "Lấy thông tin đơn hàng thành công",
            "data" => $order
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Không tìm thấy đơn hàng"
        ]);
    }
}

// LẤY CHI TIẾT ĐƠN HÀNG
else if($action == 'getchitiet') {
    $madh = $data['madh'];
    
    $sql_detail = "SELECT ct.*, sp.tensp, sp.gia, sp.hinhanh, sp.hang
                   FROM chitietdonhang ct 
                   LEFT JOIN sanpham sp ON ct.masp = sp.masp 
                   WHERE ct.madh = '$madh'";
    $result_detail = $conn->query($sql_detail);
    
    $details = [];
    if($result_detail) {
        while($row = $result_detail->fetch_assoc()) {
            $details[] = $row;
        }
    }
    
    echo json_encode([
        "status" => true,
        "message" => "Lấy chi tiết đơn hàng thành công",
        "data" => $details
    ]);
}

// TẠO ĐƠN HÀNG MỚI
else if($action == 'add') {
    $makh = $data['makh'] ?? null;
    $manv = $data['manv'] ?? 0;
    $trigia = $data['trigia'] ?? 0;
    $ngaydat = date('Y-m-d H:i:s');
    
    if (!$makh) {
        echo json_encode([
            "status" => false,
            "message" => "Thiếu mã khách hàng"
        ]);
        exit;
    }
    
    // Thêm đơn hàng
    $sql = "INSERT INTO donhang (makh, ngaydat, manv, trigia) 
            VALUES ('$makh', '$ngaydat', '$manv', '$trigia')";
    
    if($conn->query($sql)) {
        $madh = $conn->insert_id;
        
        echo json_encode([
            "status" => true,
            "message" => "Tạo đơn hàng thành công",
            "data" => ["madh" => $madh]
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

// CẬP NHẬT ĐƠN HÀNG
else if($action == 'update') {
    $madh = $data['madh'];
    $trigia = $data['trigia'] ?? null;
    
    $updates = [];
    if ($trigia !== null) $updates[] = "trigia = '$trigia'";
    
    if (empty($updates)) {
        echo json_encode([
            "status" => false,
            "message" => "Không có dữ liệu để cập nhật"
        ]);
        exit;
    }
    
    $sql = "UPDATE donhang SET " . implode(", ", $updates) . " WHERE madh = '$madh'";
    
    if($conn->query($sql)) {
        echo json_encode([
            "status" => true,
            "message" => "Cập nhật đơn hàng thành công"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

// XÓA ĐƠN HÀNG
else if($action == 'delete') {
    $madh = $data['madh'];
    
    // Xóa chi tiết đơn hàng trước
    $sql_detail = "DELETE FROM chitietdonhang WHERE madh = '$madh'";
    $conn->query($sql_detail);
    
    // Xóa đơn hàng
    $sql = "DELETE FROM donhang WHERE madh = '$madh'";
    
    if($conn->query($sql)) {
        echo json_encode([
            "status" => true,
            "message" => "Xóa đơn hàng thành công"
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
        "message" => "Action không được hỗ trợ"
    ]);
}
?>