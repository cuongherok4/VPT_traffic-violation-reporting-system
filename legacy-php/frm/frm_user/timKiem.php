<?php
include('../../db/config.php');

// Lấy biển số xe từ POST
$licensePlate = isset($_POST['licensePlate']) ? trim($_POST['licensePlate']) : "";

// Truy vấn dữ liệu từ bảng bao_cao theo biển số xe (nếu có nhập)
if ($licensePlate !== "") {
    $stmt = $conn->prepare("SELECT * FROM bao_cao WHERE bien_so_xe LIKE ? ORDER BY ngay_gio_vi_pham DESC");
    $like_param = "%" . $licensePlate . "%";
    $stmt->bind_param("s", $like_param);
} else {
    $stmt = $conn->prepare("SELECT * FROM bao_cao ORDER BY ngay_gio_vi_pham DESC");
}
$stmt->execute();
$result = $stmt->get_result();

$reports = [];
while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}
$stmt->close();
$conn->close();

$isSearch = ($licensePlate !== "");
?>
<link rel="stylesheet" href="/BTL/assets/css_form_users/search.css">

<!-- Form tìm kiếm -->
<div class="search-bao-cao-container">
    <form method="POST" action="timKiem.php">
        <label for="licensePlate">Tìm kiếm theo biển số xe:</label>
        <input type="text" name="licensePlate" id="licensePlate" placeholder="Nhập biển số xe...">
        <button type="submit" name="btnSearch"><i class="fa fa-search"></i> Tìm kiếm</button>
    </form>
    <!-- Nút quay lại trang chủ -->
    <div class="back-home">
        <a href="index.php">Quay lại trang chủ</a>
    </div>
</div>

<!-- Hiển thị kết quả -->
<div class="reports-container">
    <h2>Danh sách báo cáo</h2>
    <?php if (count($reports) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Loại vi phạm</th>
                    <th>Biển số xe</th>
                    <th>Mô tả</th>
                    <th>Địa điểm</th>
                    <th>Hình ảnh</th>
                    <th>Thời gian</th>
                    <th>Tiền phạt</th>
                    <th>Thanh toán</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $report): ?>
                <tr>
                    <td><?php echo $report['ma_bao_cao']; ?></td>
                    <td><?php echo htmlspecialchars($report['loai_vi_pham']); ?></td>
                    <td><?php echo htmlspecialchars($report['bien_so_xe']); ?></td>
                    <td><?php echo htmlspecialchars($report['mo_ta']); ?></td>
                    <td><?php echo htmlspecialchars($report['dia_diem']); ?></td>
                    <td>
                        <?php if (!empty($report['hinh_anh'])): ?>
                            <img src="/BTL/img/<?php echo htmlspecialchars($report['hinh_anh']); ?>" alt="Vi phạm" width="80">
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($report['ngay_gio_vi_pham']); ?></td>
                    <td><?php echo number_format($report['tien_phat']); ?> VNĐ</td>
                    <td>
                        <a href="process_thanhToanVP.php?bien_so_xe=<?php echo urlencode($report['bien_so_xe']); ?>&loai_vi_pham=<?php echo urlencode($report['loai_vi_pham']); ?>&tien_phat=<?php echo urlencode($report['tien_phat']); ?>">
                            Thanh toán ngay
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Không tìm thấy báo cáo nào.</p>
    <?php endif; ?>
</div>
