<?php
include($_SERVER['DOCUMENT_ROOT'] . '/QLShopDT_API/api/db.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/QLShopDT_API/api/db.php');
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Kiểm tra quyền - Chỉ Admin
$username = $_SESSION['username'];
$sql_role = "SELECT role FROM taikhoan WHERE tentk = '$username'";
$result_role = mysqli_query($conn, $sql_role);
$row_role = mysqli_fetch_assoc($result_role);
$role = $row_role['role'];

if ($role != 1) {
    echo "<script>alert('Bạn không có quyền xóa nhân viên!'); window.location.href='nhanvien.php';</script>";
    exit();
}

$manv = $_REQUEST["manv"];

mysqli_select_db($conn, "qlshopdienthoai") or die("Không tìm thấy CSDL");

$sql_del_tk = "DELETE FROM taikhoan WHERE matk = $manv";
mysqli_query($conn, $sql_del_tk);
header("Location: nhanvien.php");
?>
