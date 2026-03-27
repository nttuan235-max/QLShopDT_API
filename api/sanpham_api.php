<?php
header("Content-Type: application/json");

// Kết nối database
include "db.php";

// Đọc dữ liệu từ input
$data   = json_decode(file_get_contents("php://input"), true);
$action = isset($data['action']) ? $data['action'] : '';

// XEM DANH SÁCH SẢN PHẨM
if ($action == 'getall') {
    $sql    = "SELECT sp.*, dm.tendm
               FROM sanpham sp
               LEFT JOIN danhmuc dm ON sp.madm = dm.madm
               ORDER BY sp.masp ASC";
    $result = $conn->query($sql);

    if ($result) {
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        echo json_encode([
            "status"  => true,
            "message" => "Lấy danh sách sản phẩm thành công",
            "data"    => $products,
            "total"   => count($products)
        ]);
    } else {
        echo json_encode([
            "status"  => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

// XEM CHI TIẾT 1 SẢN PHẨM
else if ($action == 'getone') {
    $masp = $data['masp'];

    $sql    = "SELECT sp.*, dm.tendm
               FROM sanpham sp
               LEFT JOIN danhmuc dm ON sp.madm = dm.madm
               WHERE sp.masp = '$masp'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        echo json_encode([
            "status"  => true,
            "message" => "Lấy thông tin sản phẩm thành công",
            "data"    => $result->fetch_assoc()
        ]);
    } else {
        echo json_encode([
            "status"  => false,
            "message" => "Không tìm thấy sản phẩm"
        ]);
    }
}

// THÊM SẢN PHẨM
else if ($action == 'add') {
    $tensp   = $data['tensp'];
    $gia     = $data['gia'];
    $sl      = $data['sl'];
    $hang    = $data['hang'];
    $baohanh = $data['baohanh'];
    $ghichu  = isset($data['ghichu'])  ? $data['ghichu']  : '';
    $hinhanh = isset($data['hinhanh']) ? $data['hinhanh'] : '';
    $madm    = $data['madm'];

    $sql = "INSERT INTO sanpham (tensp, gia, sl, hang, baohanh, ghichu, hinhanh, madm)
            VALUES ('$tensp', '$gia', '$sl', '$hang', '$baohanh', '$ghichu', '$hinhanh', '$madm')";

    if ($conn->query($sql)) {
        echo json_encode([
            "status"  => true,
            "message" => "Thêm sản phẩm thành công",
            "masp"    => $conn->insert_id
        ]);
    } else {
        echo json_encode([
            "status"  => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

// CẬP NHẬT SẢN PHẨM
else if ($action == 'update') {
    $masp    = $data['masp'];
    $tensp   = $data['tensp'];
    $gia     = $data['gia'];
    $sl      = $data['sl'];
    $hang    = $data['hang'];
    $baohanh = $data['baohanh'];
    $ghichu  = $data['ghichu'];
    $hinhanh = $data['hinhanh'];
    $madm    = $data['madm'];

    $sql = "UPDATE sanpham
            SET tensp='$tensp', gia='$gia', sl='$sl', hang='$hang',
                baohanh='$baohanh', ghichu='$ghichu', hinhanh='$hinhanh', madm='$madm'
            WHERE masp='$masp'";

    if ($conn->query($sql)) {
        echo json_encode([
            "status"  => true,
            "message" => "Cập nhật sản phẩm thành công"
        ]);
    } else {
        echo json_encode([
            "status"  => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

// XÓA SẢN PHẨM
else if ($action == 'delete') {
    $masp = $data['masp'];

    // Lấy tên ảnh trước khi xóa
    $hinhanh = '';
    $res_img = $conn->query("SELECT hinhanh FROM sanpham WHERE masp = '$masp'");
    if ($res_img && $res_img->num_rows > 0) {
        $hinhanh = $res_img->fetch_assoc()['hinhanh'];
    }

    if ($conn->query("DELETE FROM sanpham WHERE masp = '$masp'")) {
        // Xóa file ảnh nếu có
        if ($hinhanh != '') {
            $file_path = "../img/" . $hinhanh;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        echo json_encode([
            "status"  => true,
            "message" => "Xóa sản phẩm thành công"
        ]);
    } else {
        echo json_encode([
            "status"  => false,
            "message" => "Lỗi: " . $conn->error
        ]);
    }
}

// ACTION KHÔNG HỢP LỆ
else {
    echo json_encode([
        "status"  => false,
        "message" => "Action không hợp lệ. Sử dụng: getall, getone, add, update, delete"
    ]);
}

$conn->close();
?>