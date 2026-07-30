<?php
session_start();
require_once __DIR__ . "/../../db/config.php";

// Nếu giỏ hàng chưa có hoặc trống
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    ?>
    <link rel="stylesheet" href="/BTL/assets/css_form_users/cart.css">
    <div class="cart-container">
        <h2>Giỏ hàng của bạn trống.</h2>
        <div class="cart-actions">
            <a href="index.php?page=shop" class="btn-shop">🏬 Cửa hàng</a>
        </div>
    </div>
    <?php
    exit;
}

// Lấy mảng giỏ hàng: [ma_san_pham => so_luong]
$cartItems = $_SESSION['cart'];

// Tạo danh sách ID để truy vấn
$ids = implode(",", array_keys($cartItems));

// Truy vấn lấy thông tin sản phẩm
$sql = "SELECT * FROM san_pham WHERE ma_san_pham IN ($ids)";
$result = $conn->query($sql);

// Tính tổng tiền
$total = 0;
?>
<link rel="stylesheet" href="/BTL/assets/css_form_users/cart.css">

<div class="cart-container">
    <h2>Giỏ hàng của bạn</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while ($row = $result->fetch_assoc()): 
                    $maSanPham = $row['ma_san_pham'];
                    $soLuong   = $cartItems[$maSanPham];
                    $giaBan    = (float)$row['gia_ban'];
                    $thanhTien = $giaBan * $soLuong;
                    $total    += $thanhTien;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['ten_sp']); ?></td>
                    <td><?php echo number_format($giaBan); ?> VNĐ</td>
                    <td><?php echo $soLuong; ?></td>
                    <td><?php echo number_format($thanhTien); ?> VNĐ</td>
                    <td>
                        <!-- Form cập nhật số lượng sản phẩm -->
                        <form action="process_cart.php" method="POST" class="update-form">
                            <input type="hidden" name="ma_san_pham" value="<?php echo $maSanPham; ?>">
                            <button type="submit" name="btnIncrease" class="btn-update">➕</button>
                            <button type="submit" name="btnDecrease" class="btn-update">➖</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <h3 class="total-price">Tổng cộng: <?php echo number_format($total); ?> VNĐ</h3>

        <!-- Form "Đặt hàng" gửi sang process_order.php -->
        <form action="process_order.php" method="POST">
            <input type="hidden" name="tong_tien" value="<?php echo $total; ?>">

            <label for="phuong_thuc_thanh_toan">Phương thức thanh toán:</label>
            <select name="phuong_thuc_thanh_toan" id="phuong_thuc_thanh_toan" required>
                <option value="cod">Thanh toán khi nhận hàng</option>
                <option value="chuyen_khoan">Chuyển khoản</option>
            </select>

            <div class="cart-actions">
                <a href="index.php?page=shop" class="btn-shop">🏬 Cửa hàng</a>
                <button type="submit" name="btnDatHang" class="btn-checkout">✅ Đặt hàng</button>
            </div>
        </form>
    <?php else: ?>
        <!-- Nếu truy vấn sản phẩm không có kết quả, hiển thị giỏ trống -->
        <p>Giỏ hàng của bạn trống.</p>
        <div class="cart-actions">
            <a href="index.php?page=shop" class="btn-shop">🏬 Cửa hàng</a>
        </div>
    <?php endif; ?>
</div>
<?php
$conn->close();
?>
