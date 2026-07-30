<?php
include('../../db/config.php');

// Query tổng số báo cáo
$sqlTotal = "SELECT COUNT(*) as total FROM bao_cao";
$resultTotal = $conn->query($sqlTotal);
$rowTotal = $resultTotal->fetch_assoc();
$totalReports = $rowTotal['total'];

// Query số báo cáo đã duyệt (trang_thai = 1)
$sqlApproved = "SELECT COUNT(*) as approved FROM bao_cao WHERE trang_thai = 1";
$resultApproved = $conn->query($sqlApproved);
$rowApproved = $resultApproved->fetch_assoc();
$approvedReports = $rowApproved['approved'];

// Query số báo cáo chưa duyệt (trang_thai = 0)
$sqlPending = "SELECT COUNT(*) as pending FROM bao_cao WHERE trang_thai = 0";
$resultPending = $conn->query($sqlPending);
$rowPending = $resultPending->fetch_assoc();
$pendingReports = $rowPending['pending'];

// Query khu vực vi phạm phổ biến và số lượng
$sqlRegion = "SELECT dia_diem, COUNT(*) as count FROM bao_cao GROUP BY dia_diem ORDER BY count DESC LIMIT 1";
$resultRegion = $conn->query($sqlRegion);
if ($resultRegion && $resultRegion->num_rows > 0) {
    $rowRegion = $resultRegion->fetch_assoc();
    $popularRegion = $rowRegion['dia_diem'];
    $regionCount = $rowRegion['count'];
} else {
    $popularRegion = "N/A";
    $regionCount = 0;
}

// Query dữ liệu cho thống kê loại vi phạm: Lấy số lượng báo cáo theo từng loại vi phạm
$sqlChart = "SELECT loai_vi_pham, COUNT(*) as count FROM bao_cao GROUP BY loai_vi_pham";
$resultChart = $conn->query($sqlChart);
$violationTypes = [];
if ($resultChart && $resultChart->num_rows > 0) {
    while ($row = $resultChart->fetch_assoc()) {
        $violationTypes[] = $row;
    }
}

$conn->close();
?>

<link rel="stylesheet" href="/BTL/assets/css_form_admin/thong_ke.css">

<div class="admin-container">
    <center>
        <h2>Thống Kê Báo Cáo Vi Phạm Giao Thông</h2>
        <p>Xem các thống kê chi tiết về báo cáo vi phạm giao thông trong hệ thống.</p>
    </center>
    
    <!-- Thống kê tổng quan -->
    <div class="stats-container">
        <div class="stat-box wide">
            <h3>Tổng Số Báo Cáo</h3>
            <p><?php echo number_format($totalReports); ?></p>
        </div>
        <div class="stat-box wide">
            <h3>Báo Cáo Đã Duyệt</h3>
            <p><?php echo number_format($approvedReports); ?></p>
        </div>
        <div class="stat-box wide">
            <h3>Báo Cáo Chưa Duyệt</h3>
            <p><?php echo number_format($pendingReports); ?></p>
        </div>
    </div>
    
    <!-- Thống kê theo loại vi phạm (dạng bảng) -->
    <div class="violation-table-container">
    <a href="export.php" class="export-button">Xuất Excel</a>
        <h3>Thống kê theo loại vi phạm</h3>
        <table class="violation-table">
            <thead>
                <tr>
                    <th>Loại vi phạm</th>
                    <th>Số báo cáo</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($violationTypes) > 0): ?>
                    <?php foreach ($violationTypes as $vt): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($vt['loai_vi_pham']); ?></td>
                            <td><?php echo number_format($vt['count']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="2">Không có dữ liệu</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Bản đồ vi phạm -->
    <div class="map-container">
        <h3>Bản đồ Vi Phạm</h3>
        <div class="map-placeholder">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.637064541317!2d106.67584897593205!3d10.762663459309488!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f38c74b4fbd%3A0x79b7129a47d3b19a!2zMTIzIMSQLiBBQkMsIFF14bqtbiAxLCBUUC5IQ00!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s" 
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </div>
    
    <!-- Thống kê theo khu vực -->
    <div class="stats-container">
        <div class="stat-box wide">
            <h3>Khu Vực Vi Phạm Phổ Biến</h3>
            <p><?php echo htmlspecialchars($popularRegion); ?></p>
        </div>
        <div class="stat-box wide">
            <h3>Tổng Vi Phạm Tại Khu Vực</h3>
            <p><?php echo number_format($regionCount); ?></p>
        </div>
    </div>
</div>
