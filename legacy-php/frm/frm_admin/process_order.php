<?php
include('../../db/config.php');

if (isset($_GET['delete'])) {
    $ma_hoa_don = (int) $_GET['delete'];
    
    // Chỉ cần xóa trong bảng hoa_don, chi_tiet_don_hang sẽ tự động bị xóa do ON DELETE CASCADE
    $stmt = $conn->prepare("DELETE FROM hoa_don WHERE ma_hoa_don = ?");
    $stmt->bind_param("i", $ma_hoa_don);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

// Chuyển hướng lại về trang quản lý đơn hàng
header("Location: index.php");
exit;
?>
