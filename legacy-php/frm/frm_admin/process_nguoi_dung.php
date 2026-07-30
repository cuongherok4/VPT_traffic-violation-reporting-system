<?php
require_once __DIR__ . "/../../db/config.php";

// Thêm người dùng
if (isset($_POST['btnThemMoi'])) {
    $tai_khoan = $_POST['tai_khoan'];
    $mat_khau  = $_POST['mat_khau'];
    $ma_quyen  = $_POST['ma_quyen'];

    $stmt = $conn->prepare("INSERT INTO nguoi_dung (tai_khoan, mat_khau, ma_quyen) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $tai_khoan, $mat_khau, $ma_quyen);
    $stmt->execute();
    $stmt->close();

    header("Location: quan_ly_nguoi_dung.php");
    exit;
}

// Cập nhật người dùng
if (isset($_POST['btnCapNhat'])) {
    $ma_nguoi_dung = $_POST['ma_nguoi_dung'];
    $tai_khoan = $_POST['tai_khoan'];
    $mat_khau  = $_POST['mat_khau'];
    $ma_quyen  = $_POST['ma_quyen'];

    $stmt = $conn->prepare("UPDATE nguoi_dung SET tai_khoan=?, mat_khau=?, ma_quyen=? WHERE ma_nguoi_dung=?");
    $stmt->bind_param("ssii", $tai_khoan, $mat_khau, $ma_quyen, $ma_nguoi_dung);
    $stmt->execute();
    $stmt->close();

    header("Location: quan_ly_nguoi_dung.php");
    exit;
}

// Xóa người dùng
if (isset($_POST['btnXoa'])) {
    $ma_nguoi_dung = $_POST['ma_nguoi_dung'];
    $stmt = $conn->prepare("DELETE FROM nguoi_dung WHERE ma_nguoi_dung = ?");
    $stmt->bind_param("i", $ma_nguoi_dung);
    $stmt->execute();
    $stmt->close();

    header("Location: quan_ly_nguoi_dung.php");
    exit;
}

// Nếu không có thao tác nào
header("Location: quan_ly_nguoi_dung.php");
exit;
