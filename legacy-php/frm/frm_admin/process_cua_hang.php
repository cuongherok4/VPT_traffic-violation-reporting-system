<?php
require_once __DIR__ . "/../../db/config.php";

// Thêm sản phẩm
if (isset($_POST['btnThemMoi'])) {
    $ten_sp      = $_POST['ten_sp'];
    $so_luong    = $_POST['so_luong'];
    $mo_ta       = $_POST['mo_ta'];
    $gia_ban     = $_POST['gia_ban'];
    $ma_danh_muc = $_POST['ma_danh_muc'];

    // Upload ảnh (nếu có)
    $hinh_anh = "";
    if (!empty($_FILES['hinh_anh']['name'])) {
        $hinh_anh = time() . '_' . basename($_FILES['hinh_anh']['name']);
        move_uploaded_file($_FILES['hinh_anh']['tmp_name'], 'uploads/' . $hinh_anh);
    }

    $sql = "INSERT INTO san_pham (ten_sp, so_luong, mo_ta, gia_ban, ma_danh_muc, hinh_anh)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    // 6 biến: s (ten_sp), i (so_luong), s (mo_ta), d (gia_ban), i (ma_danh_muc), s (hinh_anh)
    $stmt->bind_param("sisdis", $ten_sp, $so_luong, $mo_ta, $gia_ban, $ma_danh_muc, $hinh_anh);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit;
}

// Cập nhật sản phẩm
if (isset($_POST['btnCapNhat'])) {
    $ma_san_pham = $_POST['ma_san_pham'];
    $ten_sp      = $_POST['ten_sp'];
    $so_luong    = $_POST['so_luong'];
    $mo_ta       = $_POST['mo_ta'];
    $gia_ban     = $_POST['gia_ban'];
    $ma_danh_muc = $_POST['ma_danh_muc'];

    // Nếu có upload ảnh mới
    if (!empty($_FILES['hinh_anh']['name'])) {
        $hinh_anh = time() . '_' . basename($_FILES['hinh_anh']['name']);
        move_uploaded_file($_FILES['hinh_anh']['tmp_name'], 'uploads/' . $hinh_anh);

        $sql = "UPDATE san_pham 
                SET ten_sp=?, so_luong=?, mo_ta=?, gia_ban=?, ma_danh_muc=?, hinh_anh=?
                WHERE ma_san_pham=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisdisi", $ten_sp, $so_luong, $mo_ta, $gia_ban, $ma_danh_muc, $hinh_anh, $ma_san_pham);
    } else {
        // Không upload ảnh mới => giữ nguyên ảnh cũ
        $sql = "UPDATE san_pham 
                SET ten_sp=?, so_luong=?, mo_ta=?, gia_ban=?, ma_danh_muc=?
                WHERE ma_san_pham=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisdii", $ten_sp, $so_luong, $mo_ta, $gia_ban, $ma_danh_muc, $ma_san_pham);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit;
}

// Xóa sản phẩm
if (isset($_GET['delete'])) {
    $ma_san_pham = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM san_pham WHERE ma_san_pham=?");
    $stmt->bind_param("i", $ma_san_pham);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit;
}

// Nếu không có thao tác nào, quay lại
header("Location: index.php");
exit;
