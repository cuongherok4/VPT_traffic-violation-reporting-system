<?php
session_start();
require_once __DIR__ . "/../../db/config.php";

if (isset($_POST['btnDatHang'])) {
    // Kiểm tra đăng nhập
    if (!isset($_SESSION['ma_nguoi_dung'])) {
        echo "<p style='color:red; text-align:center;'>Bạn chưa đăng nhập. Vui lòng <a href='/BTL/frm/login.php'>đăng nhập</a> trước khi đặt hàng.</p>";
        echo "<div style='text-align:center; margin-top:20px;'>
                <a href='index.php?page=shop' style='background: #28a745; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 4px;'>🏬 Quay lại cửa hàng</a>
              </div>";
        exit;
    }

    // Nếu giỏ hàng trống
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        // Hiển thị thông báo giỏ trống + nút quay lại
?>
        <!DOCTYPE html>
        <html lang="vi">

        <head>
            <meta charset="UTF-8">
            <title>Giỏ hàng trống</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                }

                .empty-container {
                    background-color: #fff;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    text-align: center;
                    max-width: 400px;
                    width: 90%;
                }

                .empty-container h2 {
                    color: #333;
                    margin-bottom: 20px;
                }

                .btn-back {
                    display: inline-block;
                    background-color: #28a745;
                    color: #fff;
                    text-decoration: none;
                    padding: 10px 20px;
                    border-radius: 4px;
                    transition: background-color 0.3s ease;
                }

                .btn-back:hover {
                    background-color: #218838;
                }
            </style>
        </head>

        <body>
            <div class="empty-container">
                <h2>Giỏ hàng của bạn trống</h2>
                <a href="index.php?page=shop" class="btn-back">🏬 Quay lại cửa hàng</a>
            </div>
        </body>

        </html>
    <?php
        exit;
    }

    // Lấy dữ liệu
    $maNguoiDung = $_SESSION['ma_nguoi_dung'];
    $tongTien    = (float)$_POST['tong_tien'];
    $phuongThuc  = $_POST['phuong_thuc_thanh_toan'];

    // 1. Tạo hóa đơn
    $sqlHD = "INSERT INTO hoa_don (ma_nguoi_dung, ngay_dat, phuong_thuc_thanh_toan, tong_tien)
              VALUES (?, NOW(), ?, ?)";
    $stmt = $conn->prepare($sqlHD);
    $stmt->bind_param("isd", $maNguoiDung, $phuongThuc, $tongTien);
    $stmt->execute();
    $maHoaDon = $conn->insert_id;
    $stmt->close();

    // 2. Lưu chi tiết đơn hàng
    foreach ($_SESSION['cart'] as $maSanPham => $soLuong) {
        // Lấy giá sản phẩm
        $sqlSP = "SELECT gia_ban FROM san_pham WHERE ma_san_pham = $maSanPham";
        $resultSP = $conn->query($sqlSP);
        $giaBan = 0;
        if ($resultSP && $resultSP->num_rows > 0) {
            $rowSP = $resultSP->fetch_assoc();
            $giaBan = (float)$rowSP['gia_ban'];
        }
        $sqlCT = "INSERT INTO chi_tiet_don_hang (ma_hoa_don, ma_san_pham, so_luong, gia)
                  VALUES (?, ?, ?, ?)";
        $stmtCT = $conn->prepare($sqlCT);
        $stmtCT->bind_param("iiid", $maHoaDon, $maSanPham, $soLuong, $giaBan);
        $stmtCT->execute();
        $stmtCT->close();
    }

    // 3. Xóa giỏ hàng
    unset($_SESSION['cart']);

    // 4. Hiển thị kết quả
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="vi">

    <head>
        <meta charset="UTF-8">
        <title>Đặt Hàng Thành Công</title>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }

            .order-container {
                background-color: #fff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                text-align: center;
                max-width: 500px;
                width: 90%;
            }

            .order-container h2 {
                color: #333;
                margin-bottom: 20px;
                font-size: 28px;
            }

            .order-container p {
                font-size: 16px;
                color: #555;
                margin-bottom: 20px;
            }

            .order-container img {
                width: 200px;
                height: auto;
                margin-bottom: 20px;
            }

            .btn-back {
                display: inline-block;
                background-color: #28a745;
                color: #fff;
                text-decoration: none;
                padding: 10px 20px;
                border-radius: 4px;
                transition: background-color 0.3s ease;
            }

            .btn-back:hover {
                background-color: #218838;
            }

            .order-container img {
                width: 200px;
                height: auto;
                margin: 20px auto;
                /* Căn giữa theo chiều ngang */
                display: block;
                /* Đảm bảo nó là block để margin auto hoạt động */
            }
        </style>
    </head>

    <body>
        <div class="order-container">
            <h2>Đặt hàng thành công!</h2>
            <p>Mã hóa đơn của bạn: <strong><?php echo $maHoaDon; ?></strong></p>
            <?php if ($phuongThuc === 'chuyen_khoan'): ?>
                <p>Vui lòng chuyển khoản với nội dung: <strong>Thanh toán HD <?php echo $maHoaDon; ?></strong></p>
                <img src="/BTL/img/huynh.png" alt="QR code ngân hàng">
            <?php else: ?>
                <p>Phương thức: Thanh toán khi nhận hàng</p>
            <?php endif; ?>
            <a href="index.php?page=shop" class="btn-back">🏬 Quay lại cửa hàng</a>
        </div>
    </body>

    </html>
<?php
    $output = ob_get_clean();
    echo $output;
    $conn->close();
    exit;
} else {
    header("Location: index.php");
    exit;
}
