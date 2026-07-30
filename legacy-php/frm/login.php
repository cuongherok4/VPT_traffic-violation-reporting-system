<?php
session_start();
// Nếu có thông báo lỗi (hoặc thành công) truyền qua GET, hiển thị
if (isset($_GET['error'])) {
    echo '<p style="color:red; text-align:center;">' . htmlspecialchars($_GET['error']) . '</p>';
}
?>
<link rel="stylesheet" href="/BTL/assets/css_form_admin/login.css">
<!-- Form Đăng Nhập -->
<div id="loginForm">
    <h2>Đăng Nhập</h2>
    <form action="process_login.php" method="POST">
        <label for="tai_khoan">Tài khoản:</label>
        <input type="text" name="tai_khoan" id="tai_khoan" required>

        <label for="mat_khau">Mật khẩu:</label>
        <input type="password" name="mat_khau" id="mat_khau" required>

        <button type="submit" name="btnDangNhap">Đăng nhập</button>
    </form>
    <p class="switch-form">
        Chưa có tài khoản?
        <!-- Ẩn form login, hiện form đăng ký -->
        <a href="#" onclick="document.getElementById('loginForm').style.display='none';document.getElementById('registerForm').style.display='block';">
            Đăng ký
        </a>
    </p>
</div>

<!-- Form Đăng Ký (ẩn mặc định) -->
<div id="registerForm" style="display:none;">
    <h2>Đăng Ký</h2>
    <form action="process_login.php" method="POST">
        <label for="tai_khoan_dk">Tài khoản:</label>
        <input type="text" name="tai_khoan_dk" id="tai_khoan_dk" required>

        <label for="mat_khau_dk">Mật khẩu:</label>
        <input type="password" name="mat_khau_dk" id="mat_khau_dk" required>

        <label for="dia_chi">Địa chỉ:</label>
        <input type="text" name="dia_chi" id="dia_chi">

        <button type="submit" name="btnDangKy">Đăng ký</button>
    </form>
    <p class="switch-form">
        Đã có tài khoản?
        <!-- Ẩn form đăng ký, hiện form login -->
        <a href="#" onclick="document.getElementById('registerForm').style.display='none';document.getElementById('loginForm').style.display='block';">
            Đăng nhập
        </a>
    </p>
</div>
