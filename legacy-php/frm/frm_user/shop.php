<?php
require_once __DIR__ . "/../../db/config.php"; // Kết nối CSDL

// Lấy danh sách sản phẩm
$sql = "SELECT * FROM san_pham ORDER BY ma_san_pham DESC";
$result = $conn->query($sql);
$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
$conn->close();
?>

<link rel="stylesheet" href="/BTL/assets/css_form_users/shop.css">

<div class="store-container">
    <h1>Cửa Hàng Đồ Bảo Hộ Giao Thông Chính Hãng</h1>
    <p>Chuyên cung cấp mũ bảo hiểm, áo mưa, bảo hiểm xe máy, ô tô và các phụ kiện an toàn giao thông.</p>

    <!-- Liên kết để xem giỏ hàng -->
    <div class="cart-link-container">
        <a href="cart.php" class="cart-link">Xem giỏ hàng</a>
    </div>

    <!-- Nút "Xem đơn hàng của bạn" -->
    <div class="order-link-container">
        <a href="lay_DH.php" class="order-link">Xem đơn hàng của bạn</a>
    </div>

    <div class="store-items">
        <?php foreach ($products as $product): ?>
            <div class="store-item">
            <img src="/BTL/frm/frm_admin/uploads/<?php echo htmlspecialchars($product['hinh_anh']); ?>" 
            alt="<?php echo htmlspecialchars($product['ten_sp']); ?>">

                <h2><?php echo htmlspecialchars($product['ten_sp']); ?></h2>
                <p><?php echo htmlspecialchars($product['mo_ta']); ?></p>
                <p>Giá: <strong><?php echo number_format($product['gia_ban']); ?> VNĐ</strong></p>

                <!-- Form Thêm vào giỏ hàng / Mua ngay -->
                <form action="process_cart.php" method="POST">
                    <input type="hidden" name="ma_san_pham" value="<?php echo $product['ma_san_pham']; ?>">
                    <button type="submit" name="btnAddToCart">Thêm vào giỏ hàng</button>
                    <button type="submit" name="btnBuyNow">Mua ngay</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>
