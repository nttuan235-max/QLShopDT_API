<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "qlshopdienthoai";

$conn = new mysqli($host, $user, $pass, $db);

if($conn -> connect_error)
    {
        die("CONNECT FAILED: " . $conn -> connect_error);
    }
?>