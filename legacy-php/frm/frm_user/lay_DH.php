<?php
session_start();
require_once __DIR__ . "/../../db/config.php";

// Kiểm tra nếu user chưa đăng nhập thì chuyển về trang đăng nhập
if (!isset($_SESSION['ma_nguoi_dung'])) {
    header("Location: login.php");
    exit;
}
$maNguoiDung = $_SESSION['ma_nguoi_dung'];

// Lấy danh sách đơn hàng của người dùng
$sqlOrders = "SELECT * FROM hoa_don WHERE ma_nguoi_dung = ? ORDER BY ngay_dat DESC";
$stmt = $conn->prepare($sqlOrders);
$stmt->bind_param("i", $maNguoiDung);
$stmt->execute();
$resultOrders = $stmt->get_result();
$orders = [];
if ($resultOrders && $resultOrders->num_rows > 0) {
    while ($row = $resultOrders->fetch_assoc()) {
        $orders[] = $row;
    }
}
$stmt->close();
?>

<link rel="stylesheet" href="/BTL/assets/css_form_users/lay_DH.css">

<div class="order-container">
    <h2>Đơn hàng của bạn</h2>
    <?php if (empty($orders)): ?>
        <p>Chưa có đơn hàng nào.</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="order-card">
                <h3>Hóa đơn #<?php echo $order['ma_hoa_don']; ?></h3>
                <p><strong>Ngày đặt:</strong> <?php echo $order['ngay_dat']; ?></p>
                <p><strong>Phương thức thanh toán:</strong> 
                    <?php 
                        echo ($order['phuong_thuc_thanh_toan'] === 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản'); 
                    ?>
                </p>
                <p><strong>Tổng tiền:</strong> <?php echo number_format($order['tong_tien']); ?> VNĐ</p>

                <?php
                // Lấy chi tiết đơn hàng cho đơn này
                $maHoaDon = $order['ma_hoa_don'];
                $sqlCT = "SELECT ct.*, sp.ten_sp, sp.gia_ban 
                          FROM chi_tiet_don_hang ct 
                          JOIN san_pham sp ON ct.ma_san_pham = sp.ma_san_pham 
                          WHERE ct.ma_hoa_don = ?";
                $stmtCT = $conn->prepare($sqlCT);
                $stmtCT->bind_param("i", $maHoaDon);
                $stmtCT->execute();
                $resultCT = $stmtCT->get_result();
                ?>
                <table class="order-details">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá bán</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($resultCT && $resultCT->num_rows > 0) {
                            while ($rowCT = $resultCT->fetch_assoc()) {
                                $thanhTienCT = $rowCT['gia_ban'] * $rowCT['so_luong'];
                                echo "<tr>
                                        <td>" . htmlspecialchars($rowCT['ten_sp']) . "</td>
                                        <td>" . number_format($rowCT['gia_ban']) . " VNĐ</td>
                                        <td>" . $rowCT['so_luong'] . "</td>
                                        <td>" . number_format($thanhTienCT) . " VNĐ</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Không có chi tiết đơn hàng nào.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <?php $stmtCT->close(); ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="back-link">
        <a href="index.php?page=shop">🏬 Quay lại cửa hàng</a>
    </div>
</div>

<?php
$conn->close();
?>
