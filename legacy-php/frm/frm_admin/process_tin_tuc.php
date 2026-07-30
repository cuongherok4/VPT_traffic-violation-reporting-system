<?php
require_once __DIR__ . "/../../db/config.php"; // Kết nối CSDL

if (isset($_POST['btnDangTin'])) {
    // Lấy dữ liệu từ form
    $tenTinTuc = mysqli_real_escape_string($conn, $_POST['tenTinTuc']);
    $date      = mysqli_real_escape_string($conn, $_POST['date']); // YYYY-MM-DD
    $time      = mysqli_real_escape_string($conn, $_POST['time']); // HH:MM
    $noiDung   = mysqli_real_escape_string($conn, $_POST['noiDung']);

    // Ghép ngày và giờ thành dạng YYYY-MM-DD HH:MM:SS
    $ngayDang = $date . ' ' . $time . ':00';

    // Xử lý upload hình ảnh (nếu có)
    $imageName = "";
    if (!empty($_FILES['hinhAnh']['name'])) {
        $uploadDir  = "/BTL/img/"; // Thư mục lưu ảnh trên server
        $fileName   = basename($_FILES['hinhAnh']['name']);
        $targetPath = $_SERVER['DOCUMENT_ROOT'] . $uploadDir . $fileName;

        // Thử di chuyển file upload
        if (move_uploaded_file($_FILES['hinhAnh']['tmp_name'], $targetPath)) {
            $imageName = $fileName; // Chỉ lưu tên file vào DB
        } else {
            // Nếu upload ảnh thất bại
            $msg = "Không thể upload ảnh. Kiểm tra quyền ghi thư mục /BTL/img/.";
            header("Location: dang_tin_tuc.php?msg=" . urlencode($msg));
            exit;
        }
    }

    // Thực hiện INSERT vào bảng tin_tuc
    // Cột: ma_tin_tuc (PK, AI), ten_tin_tuc, ngay_dang, noi_dung, hinh_anh
    $sqlInsert = "INSERT INTO tin_tuc (ten_tin_tuc, ngay_dang, noi_dung, hinh_anh)
                  VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sqlInsert);
    $stmt->bind_param("ssss", $tenTinTuc, $ngayDang, $noiDung, $imageName);

    if ($stmt->execute()) {
        $msg = "Đăng tin thành công!";
    } else {
        $msg = "Lỗi khi thêm tin tức: " . $conn->error;
    }
    $stmt->close();

    // Quay lại form kèm thông báo
    header("Location: /BTL/frm/frm_admin/index.php");
    exit;
} else {
    // Nếu truy cập trực tiếp file này mà không submit form
    header("Location: /BTL/frm/frm_admin/index.php");
    exit;
}
