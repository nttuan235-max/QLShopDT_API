<?php
include($_SERVER['DOCUMENT_ROOT'] . '/QLShopDT_API/api/db.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/QLShopDT_API/api/db.php');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];
$sql_role = "SELECT role FROM taikhoan WHERE tentk = '$username'";
$result_role = mysqli_query($conn, $sql_role);
$row_role = mysqli_fetch_assoc($result_role);
$role = $row_role['role'];

if ($role != 1 && $role != 2) {
    echo "<script>alert('Bạn không có quyền!'); window.location.href='thanhtoan.php';</script>";
    exit();
}

$madh = $_REQUEST["madh"];
$phuongthuc = $_REQUEST["phuongthuc"];
$sotien = $_REQUEST["sotien"];
$trangthai = $_REQUEST["trangthai"];
$ghichu = isset($_REQUEST["ghichu"]) ? $_REQUEST["ghichu"] : "";

$ngaythanhtoan = date('Y-m-d H:i:s');

$sql_insert = "INSERT INTO `thanhtoan` (`matt`, `madh`, `phuongthuc`, `ngaythanhtoan`, `sotien`, `trangthai`, `ghichu`) 
               VALUES (NULL, '$madh', '$phuongthuc', '$ngaythanhtoan', '$sotien', '$trangthai', '$ghichu')";

mysqli_query($conn, $sql_insert);
header("Location: thanhtoan.php");
?>
