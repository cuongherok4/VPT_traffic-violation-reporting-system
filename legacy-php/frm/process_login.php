<?php
session_start();
require_once __DIR__ . "/../db/config.php";

// Nếu nhấn nút Đăng Nhập
if (isset($_POST['btnDangNhap'])) {
    $tai_khoan = $_POST['tai_khoan'];
    $mat_khau  = $_POST['mat_khau'];

    // Kiểm tra người dùng
    $stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE tai_khoan=? AND mat_khau=?");
    $stmt->bind_param("ss", $tai_khoan, $mat_khau);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Lưu thông tin vào session
        $_SESSION['ma_nguoi_dung'] = $user['ma_nguoi_dung'];
        $_SESSION['tai_khoan']     = $user['tai_khoan'];
        $_SESSION['ma_quyen']      = $user['ma_quyen'];

        // Kiểm tra quyền
        if ($user['ma_nguoi_dung'] == 1) {
            // Admin
            header("Location: /BTL/frm/frm_admin/index.php");
        } else {
            // User
            header("Location: /BTL/frm/frm_user/index.php");
        }
        exit;
    } else {
        // Sai tài khoản hoặc mật khẩu
        $error = "Tài khoản hoặc mật khẩu không đúng!";
        header("Location: login.php?error=" . urlencode($error));
        exit;
    }
}

// Nếu nhấn nút Đăng Ký
if (isset($_POST['btnDangKy'])) {
    $tai_khoan_dk = $_POST['tai_khoan_dk'];
    $mat_khau_dk  = $_POST['mat_khau_dk'];
    $dia_chi      = $_POST['dia_chi'];
    // Người dùng thường => ma_quyen = 2
    $ma_quyen     = 2;

    // Thêm user mới
    $stmt = $conn->prepare("INSERT INTO nguoi_dung(tai_khoan, mat_khau, dia_chi, ma_quyen) VALUES(?, ?, ?, ?)");
    $stmt->bind_param("sssi", $tai_khoan_dk, $mat_khau_dk, $dia_chi, $ma_quyen);
    if ($stmt->execute()) {
        // Đăng ký thành công => chuyển về form login
        $success = "Đăng ký thành công, hãy đăng nhập!";
        header("Location: login.php?error=" . urlencode($success));
        exit;
    } else {
        // Lỗi khi insert
        $error = "Đăng ký thất bại: " . $stmt->error;
        header("Location: login.php?error=" . urlencode($error));
        exit;
    }
}

// Nếu truy cập file này trực tiếp, quay lại form đăng nhập
header("Location: login.php");
exit;
