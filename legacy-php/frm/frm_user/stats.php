<?php
session_start();
require_once __DIR__ . "/../../db/config.php"; // Kết nối CSDL

$sql = "SELECT * FROM bao_cao ORDER BY ma_bao_cao DESC";
$result = $conn->query($sql);
?>

<!-- Link tới file CSS -->
<link rel="stylesheet" href="/BTL/assets/css_form_users/stats.css">

<div class="user-statistics-container">
    <h2>Thống Kê Báo Cáo</h2>

    <table class="statistics-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Loại Vi Phạm</th>
                <th>Ngày Gửi</th>
                <th>Trạng Thái</th>
                <th>Mô tả</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <?php
                    $maBaoCao   = $row['ma_bao_cao'];
                    $loaiViPham = $row['loai_vi_pham'];
                    // ngày_gio_vi_pham có thể là DATETIME hoặc VARCHAR
                    $ngayGui    = $row['ngay_gio_vi_pham']; 
                    $trangThai  = (int)$row['trang_thai'];

                    // Xác định text hiển thị + class màu sắc
                    switch($trangThai) {
                        case 0:
                            $statusText  = "Chờ duyệt";
                            $statusClass = "pending";
                            break;
                        case 1:
                            $statusText  = "Đã duyệt";
                            $statusClass = "approved";
                            break;
                        case 2:
                            $statusText  = "Bị từ chối";
                            $statusClass = "rejected";
                            break;
                        default:
                            $statusText  = "Không rõ";
                            $statusClass = "";
                    }
                    $lyDo = $row['ly_do'];
                ?>
                <tr>
                    <td><?php echo $maBaoCao; ?></td>
                    <td><?php echo htmlspecialchars($loaiViPham); ?></td>
                    <td><?php echo htmlspecialchars($ngayGui); ?></td>
                    <td class="status <?php echo $statusClass; ?>">
                        <?php echo $statusText; ?>
                        <td><?php echo htmlspecialchars($lyDo);?></td>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Chưa có báo cáo nào</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$conn->close();
?>
