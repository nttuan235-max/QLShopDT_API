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
    echo "<script>alert('Bạn không có quyền sửa nhân viên!'); window.location.href='nhanvien.php';</script>";
    exit();
}

$manv = $_REQUEST["manv"];
$tennv = $_REQUEST["txt_tennv"];
$diachi = $_REQUEST["txt_diachi"];
$sdt = $_REQUEST["txt_sdt"];
$ns = $_REQUEST["date_ns"];

$sql_edit = "UPDATE `nhanvien` SET `tennv` = '$tennv', `diachi` = '$diachi', `sdt` = '$sdt', `ns` = '$ns' 
             WHERE `nhanvien`.`manv` = $manv;";

mysqli_query($conn, $sql_edit) or die("Query unsuccessful");
header("Location: nhanvien.php");
?>
