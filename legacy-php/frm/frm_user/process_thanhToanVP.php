<?php
// process_thanhToanVP.php
// Nhận thông tin thanh toán qua GET
$bien_so_xe  = isset($_GET['bien_so_xe']) ? $_GET['bien_so_xe'] : "";
$loai_vi_pham = isset($_GET['loai_vi_pham']) ? $_GET['loai_vi_pham'] : "";
$tien_phat   = isset($_GET['tien_phat']) ? $_GET['tien_phat'] : 0;
?>
<!-- Nội dung phần thân, bạn có cấu trúc sẵn -->
<div class="payment-container">
  <h2>Thanh toán vi phạm</h2>
  <p><strong>Tiền phạt:</strong> <?php echo number_format($tien_phat); ?> VNĐ</p>
  <p><strong>Nội dung chuyển tiền:</strong> <?php echo htmlspecialchars($bien_so_xe . " - " . $loai_vi_pham); ?></p>
  <img src="/BTL/img/huynh.png" alt="QR code ngân hàng" class="qr-img">
  <div class="payment-actions">
      <a href="index.php" class="btn-home">Quay lại trang chủ</a>
  </div>
</div>

<!-- Inline CSS (hoặc bạn có thể tách ra file process_thanhToanVP.css) -->
<style>
.payment-container {
    max-width: 500px;
    margin: 40px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    text-align: center;
    font-family: Arial, sans-serif;
}
.payment-container h2 {
    color: #333;
    margin-bottom: 20px;
    font-size: 28px;
}
.payment-container p {
    font-size: 16px;
    color: #555;
    margin: 10px 0;
}
.qr-img {
    width: 250px;
    height: auto;
    margin: 20px auto;
    display: block;
}
.payment-actions {
    margin-top: 20px;
}
.payment-actions a {
    display: inline-block;
    background-color: #28a745;
    color: #fff;
    text-decoration: none;
    padding: 10px 20px;
    margin: 0 10px;
    border-radius: 4px;
    transition: background-color 0.3s;
}
.payment-actions a:hover {
    background-color: #218838;
}
</style>
