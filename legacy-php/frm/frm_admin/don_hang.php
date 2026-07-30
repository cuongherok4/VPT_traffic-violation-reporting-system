<?php
include('../../db/config.php');

// Query danh sách hóa đơn cùng với thông tin người dùng (bao gồm dia_chi)
$sqlOrders = "SELECT h.*, n.tai_khoan, n.dia_chi 
              FROM hoa_don h 
              LEFT JOIN nguoi_dung n ON h.ma_nguoi_dung = n.ma_nguoi_dung
              ORDER BY h.ngay_dat DESC";
$resultOrders = $conn->query($sqlOrders);
$orders = [];
if ($resultOrders && $resultOrders->num_rows > 0) {
    while ($row = $resultOrders->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>
<link rel="stylesheet" href="/BTL/assets/css_form_admin/don_hang.css">

<div class="admin-orders-container">
    <h2>Quản lý Đơn Hàng</h2>
    <?php if (count($orders) > 0): ?>
        <?php foreach ($orders as $order): ?>
            <div class="order-card">
                <div class="order-header">
                    <p><strong>Mã đơn hàng:</strong> <?php echo $order['ma_hoa_don']; ?></p>
                    <p><strong>Người đặt:</strong> <?php echo htmlspecialchars($order['tai_khoan']); ?> (ID: <?php echo $order['ma_nguoi_dung']; ?>)</p>
                    <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['dia_chi']); ?></p>
                    <p><strong>Ngày đặt:</strong> <?php echo $order['ngay_dat']; ?></p>
                    <p><strong>Tổng tiền:</strong> <?php echo number_format($order['tong_tien']); ?> VNĐ</p>
                    <!-- Nút xóa đơn hàng: truyền ma_hoa_don qua GET -->
                    <a href="process_order.php?delete=<?php echo $order['ma_hoa_don']; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">Xóa đơn hàng</a>
                </div>
                <?php
                // Query chi tiết đơn hàng cho đơn này
                $sqlDetails = "SELECT ctdh.*, sp.ten_sp, sp.hinh_anh 
                               FROM chi_tiet_don_hang ctdh 
                               LEFT JOIN san_pham sp ON ctdh.ma_san_pham = sp.ma_san_pham 
                               WHERE ctdh.ma_hoa_don = ?";
                $stmt = $conn->prepare($sqlDetails);
                $stmt->bind_param("i", $order['ma_hoa_don']);
                $stmt->execute();
                $resultDetails = $stmt->get_result();
                ?>
                <div class="order-details">
                    <table>
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultDetails && $resultDetails->num_rows > 0): ?>
                                <?php while ($detail = $resultDetails->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($detail['ten_sp']); ?></td>
                                        <td><?php echo $detail['so_luong']; ?></td>
                                        <td><?php echo number_format($detail['gia']); ?> VNĐ</td>
                                        <td><?php echo number_format($detail['gia'] * $detail['so_luong']); ?> VNĐ</td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">Không có chi tiết đơn hàng nào.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php $stmt->close(); ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Không có đơn hàng nào.</p>
    <?php endif; ?>
</div>
