<?php
session_start();

// Xóa tất cả session
$_SESSION = array();
session_destroy();

// Chuyển về trang đăng nhập
header("Location: login.php");
exit();
