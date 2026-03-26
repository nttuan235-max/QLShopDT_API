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
    echo "<script>alert('Bạn không có quyền thêm nhân viên!'); window.location.href='nhanvien.php';</script>";
    exit();
}

$manv = $_REQUEST["manv"];
$tennv = $_REQUEST["txt_tennv"];
$diachi = $_REQUEST["txt_diachi"];
$sdt = $_REQUEST["txt_sdt"];
$ns = $_REQUEST["date_ns"];

mysqli_select_db($conn, "qlshopdienthoai");

$sql_create_tk = "INSERT INTO taikhoan VALUES (null, '$tennv', '123456', '2')";
mysqli_query($conn, $sql_create_tk);
$result = mysqli_query($conn, "SELECT LAST_INSERT_ID()");

$id = -1;
if ($result)
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['LAST_INSERT_ID()'];
    }

if ($id == -1) die("KO co id");
$sql_insert = "INSERT INTO `nhanvien` (`manv`, `tennv`, `diachi`, `sdt`, `ns`) 
               VALUES ('$id', '$tennv', '$diachi', '$sdt', '$ns');";

mysqli_query($conn, $sql_insert);
header("Location: nhanvien.php");
?>
