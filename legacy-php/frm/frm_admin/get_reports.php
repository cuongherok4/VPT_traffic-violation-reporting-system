<?php
require_once __DIR__ . "/../../db/config.php";

// Lấy danh sách báo cáo từ CSDL
$sql_reports = "SELECT * FROM bao_cao ORDER BY ngay_gio_vi_pham DESC";
$result_reports = $conn->query($sql_reports);
?>

<div class="report-list-container">
    <?php while ($row = $result_reports->fetch_assoc()): ?>
        <div class="report-item">
            <div>
                <h4><?= htmlspecialchars($row['loai_vi_pham']) ?> - Biển số: <?= htmlspecialchars($row['bien_so_xe']) ?></h4>
                <p>Địa điểm: <?= htmlspecialchars($row['dia_diem']) ?></p>
                <p>Ngày: <?= htmlspecialchars($row['ngay_gio_vi_pham']) ?></p>
                <p>Mô tả: <?= nl2br(htmlspecialchars($row['mo_ta'])) ?></p>
                <?php if (!empty($row['hinh_anh'])): ?>
                    <img src="/BTL/img/<?= htmlspecialchars($row['hinh_anh']) ?>" alt="Hình ảnh vi phạm" width="200">
                <?php endif; ?>
            </div>
            <div>
                <form class="admin-action-form">
                    <input type="hidden" name="ma_bao_cao" value="<?= $row['ma_bao_cao'] ?>">
                    <button type="button" onclick="handleAdminAction(this, 'accept')">Chấp nhận</button>
                    <button type="button" onclick="handleAdminAction(this, 'reject')" class="reject">Từ chối</button>
                </form>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php mysqli_close($conn); ?>