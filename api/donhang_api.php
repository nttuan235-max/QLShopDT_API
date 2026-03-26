<?php
header("Content-Type: application/json");

// Kết nối database
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$action = isset($data['action']) ? $data['action'] : '';

// XEM TẤT CẢ ĐỔN HÀNG
if($action == 'getall') {
    $sql = "SELECT dh.*, kh.tenkh, nv.tennv 
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
        $sql_detail = "SELECT ct.*, sp.tensp, sp.gia, sp.hinhanh 
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

// TẠO ĐƠN HÀNG MỚI
else if($action == 'add') {
    $makh = $data['makh'];
    $manv = $data['manv'];
    $trigia = $data['trigia'];
    $ngaydat = date('Y-m-d H:i:s');
    $sanpham = $data['sanpham']; // Array các sản phẩm [{masp, sl}, ...]
    
    // Thêm đơn hàng
    $sql = "INSERT INTO donhang (makh, ngaydat, manv, trigia) 
            VALUES ('$makh', '$ngaydat', '$manv', '$trigia')";
    
    if($conn->query($sql)) {
        $madh = $conn->insert_id;
        
        // Thêm chi tiết đơn hàng
        $success = true;
        foreach($sanpham as $sp) {
            $masp = $sp['masp'];
            $sl = $sp['sl'];
            
            $sql_detail = "INSERT INTO chitietdonhang (madh, masp, sl) 
                          VALUES ('$madh', '$masp', '$sl')";
            
            if(!$conn->query($sql_detail)) {
                $success = false;
                break;
            }
        }
        
        if($success) {
            echo json_encode([
                "status" => true,
                "message" => "Tạo đơn hàng thành công",
                "madh" => $madh
            ]);
        } else {
            echo json_encode([
                "status" => false,
                "message" => "Lỗi khi thêm chi tiết đơn hàng"
            ]);
        }
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
    $manv = isset($data['manv']) ? $data['manv'] : '';
    $trigia = isset($data['trigia']) ? $data['trigia'] : '';
    
    $sql = "UPDATE donhang SET manv = '$manv', trigia = '$trigia' WHERE madh = '$madh'";
    
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
    
    // Xóa đơn hàng sẽ tự động xóa chi tiết (CASCADE)
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
        "message" => "Action không hợp lệ"
    ]);
}

$conn->close();
?>
