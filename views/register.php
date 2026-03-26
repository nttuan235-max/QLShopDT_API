<?php
session_start();

$page_title = 'Đăng ký';
$extra_css = '<link rel="stylesheet" href="/QLShopDT_API/assets/css/register.css">';

include "../includes/header.php";

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    // Validate
    if ($password !== $confirm) {
        $error = "Mật khẩu xác nhận không khớp!";
    } else {
        // Kiểm tra username đã tồn tại chưa
        $check = mysqli_query($conn, "SELECT tentk FROM taikhoan WHERE tentk = '$username'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Tên đăng nhập đã tồn tại!";
        } else {
            // Tạo tài khoản (role = 0: khách hàng)
            $sql = "INSERT INTO taikhoan (tentk, mk, role) VALUES ('$username', '$password', 0)";
            if (mysqli_query($conn, $sql)) {
                $matk = mysqli_insert_id($conn);
                
                // Tạo thông tin khách hàng
                $sql2 = "INSERT INTO khachhang (makh, tenkh, diachi, sdt) 
                         VALUES ($matk, '$name', '$address', '$phone')";
                
                if (mysqli_query($conn, $sql2)) {
                    // Tạo giỏ hàng cho khách hàng
                    mysqli_query($conn, "INSERT INTO giohang (makh) VALUES ($matk)");
                    
                    $success = "Đăng ký thành công! Đang chuyển đến trang đăng nhập...";
                    echo "<script>setTimeout(function(){ window.location.href='login.php'; }, 2000);</script>";
                } else {
                    $error = "Lỗi khi tạo thông tin khách hàng!";
                }
            } else {
                $error = "Lỗi khi tạo tài khoản!";
            }
        }
    }
}
?>

<div class="register-container">
    <h2><i class="fa fa-user-plus"></i> ĐĂNG KÝ TÀI KHOẢN</h2>
    
    <?php if ($error): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success-msg"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label><i class="fa fa-user"></i> Tên đăng nhập *</label>
            <input type="text" name="username" required>
        </div>
        
        <div class="form-group">
            <label><i class="fa fa-lock"></i> Mật khẩu *</label>
            <input type="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label><i class="fa fa-lock"></i> Xác nhận mật khẩu *</label>
            <input type="password" name="confirm_password" required>
        </div>
        
        <div class="form-group">
            <label><i class="fa fa-id-card"></i> Họ và tên *</label>
            <input type="text" name="name" required>
        </div>
        
        <div class="form-group">
            <label><i class="fa fa-map-marker-alt"></i> Địa chỉ</label>
            <input type="text" name="address">
        </div>
        
        <div class="form-group">
            <label><i class="fa fa-phone"></i> Số điện thoại</label>
            <input type="tel" name="phone">
        </div>
        
        <button type="submit" class="btn-register">
            <i class="fa fa-user-plus"></i> Đăng ký
        </button>
    </form>
    
    <div class="login-link">
        Đã có tài khoán? <a href="login.php">Đăng nhập</a>
    </div>
</div>

</body>
</html>
