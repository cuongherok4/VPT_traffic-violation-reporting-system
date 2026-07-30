<?php
include ('../../db/config.php');

// Lấy dữ liệu từ bảng bao_cao
$sqlBaoCao = "SELECT * FROM bao_cao ORDER BY ngay_gio_vi_pham DESC";
$resultBaoCao = $conn->query($sqlBaoCao);
$reports = [];
if ($resultBaoCao && $resultBaoCao->num_rows > 0) {
    while ($row = $resultBaoCao->fetch_assoc()) {
        $reports[] = $row;
    }
}

// Lấy dữ liệu từ bảng tin_tuc
$sqlTinTuc = "SELECT * FROM tin_tuc ORDER BY ngay_dang DESC";
$resultTinTuc = $conn->query($sqlTinTuc);
$news = [];
if ($resultTinTuc && $resultTinTuc->num_rows > 0) {
    while ($row = $resultTinTuc->fetch_assoc()) {
        $news[] = $row;
    }
}
$conn->close();
?>

<!-- Link tới file CSS riêng -->
<link rel="stylesheet" href="/BTL/assets/css_form_users/news.css">

<div class="news-reports-container">
    <!-- Cột bên trái: Tin Tức Giao Thông -->
    <div class="news-column">
        <h2>Tin Tức Giao Thông</h2>
        <div class="news-grid">
            <?php foreach ($news as $index => $item): ?>
                <div class="news-item" onclick="openModal('newsModal<?= $index ?>')">
                    <img src="/BTL/img/<?= htmlspecialchars($item['hinh_anh']) ?>" 
                         alt="<?= htmlspecialchars($item['ten_tin_tuc']) ?>">
                    <div class="news-content">
                        <h3><?= htmlspecialchars($item['ten_tin_tuc']) ?></h3>
                        <p><strong>Ngày đăng:</strong> <?= htmlspecialchars($item['ngay_dang']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Cột bên phải: Báo Cáo Vi phạm Giao Thông -->
    <div class="reports-column">
        <h2>Báo Cáo Vi phạm Giao Thông</h2>
        <div class="news-grid">
            <?php foreach ($reports as $idx => $report): ?>
                <div class="news-item" onclick="openModal('reportModal<?= $idx ?>')">
                    <img src="/BTL/img/<?= htmlspecialchars($report['hinh_anh']) ?>" 
                         alt="<?= htmlspecialchars($report['loai_vi_pham']) ?>">
                    <div class="news-content">
                        <h3><?= htmlspecialchars($report['loai_vi_pham']) ?></h3>
                        <p><strong>Thời gian:</strong> <?= htmlspecialchars($report['ngay_gio_vi_pham']) ?></p>
                        <p><strong>Địa điểm:</strong> <?= htmlspecialchars($report['dia_diem']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal hiển thị chi tiết Tin tức -->
<?php foreach ($news as $index => $item): ?>
    <div id="newsModal<?= $index ?>" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('newsModal<?= $index ?>')">&times;</span>
            <h2><?= htmlspecialchars($item['ten_tin_tuc']) ?></h2>
            <p><strong>Ngày đăng:</strong> <?= htmlspecialchars($item['ngay_dang']) ?></p>
            <p><strong>Nội dung:</strong> <?= nl2br(htmlspecialchars($item['noi_dung'])) ?></p>
        </div>
    </div>
<?php endforeach; ?>

<!-- Modal hiển thị chi tiết Báo cáo -->
<?php foreach ($reports as $idx => $report): ?>
    <div id="reportModal<?= $idx ?>" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('reportModal<?= $idx ?>')">&times;</span>
            <h2><?= htmlspecialchars($report['loai_vi_pham']) ?></h2>
            <p><strong>Biển số xe:</strong> <?= htmlspecialchars($report['bien_so_xe']) ?></p>
            <p><strong>Thời gian:</strong> <?= htmlspecialchars($report['ngay_gio_vi_pham']) ?></p>
            <p><strong>Địa điểm:</strong> <?= htmlspecialchars($report['dia_diem']) ?></p>
            <p><strong>Mô tả:</strong> <?= nl2br(htmlspecialchars($report['mo_ta'])) ?></p>
        </div>
    </div>
<?php endforeach; ?>
