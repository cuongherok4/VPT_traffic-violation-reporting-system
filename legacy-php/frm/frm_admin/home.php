<?php
require_once __DIR__ . "/../../db/config.php"; // Kết nối CSDL

// Xử lý các yêu cầu POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Nếu là báo cáo từ người dùng (btnGuiBaoCao) - không thay đổi
    if (isset($_POST['btnGuiBaoCao'])) {
        // Nếu là báo cáo từ người dùng (form gửi báo cáo có nút "btnGuiBaoCao")
        if (isset($_POST['btnGuiBaoCao'])) {
            // Lấy dữ liệu từ form và làm sạch dữ liệu
            $loaiViPham     = mysqli_real_escape_string($conn, $_POST['violationType']);
            $bienSoXe       = mysqli_real_escape_string($conn, $_POST['plateNumber']);
            $moTaChiTiet    = mysqli_real_escape_string($conn, $_POST['description']);
            $ngayViPham     = mysqli_real_escape_string($conn, $_POST['violationDate']);
            $gioViPham      = mysqli_real_escape_string($conn, $_POST['violationTime']);
            $diaDiemViPham  = mysqli_real_escape_string($conn, $_POST['location']);

            // Xử lý file hình ảnh: chỉ lưu tên file nếu có upload thành công
            $hinhAnh = ''; // Mặc định là rỗng nếu không có ảnh
            if (isset($_FILES['evidence']) && $_FILES['evidence']['error'] == 0) {
                $uploadDir = "/BTL/img/";
                $fileName = basename($_FILES['evidence']['name']);
                $uploadFile = $uploadDir . $fileName;

                // Di chuyển file upload vào thư mục lưu trữ
                if (move_uploaded_file($_FILES['evidence']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $uploadFile)) {
                    $hinhAnh = $fileName; // Lưu chỉ tên file, không lưu file ảnh vào DB
                }
            }

            // Ghép ngày và giờ thành một chuỗi (định dạng cho MySQL: YYYY-MM-DD HH:MM:SS)
            $ngayGioViPham = $ngayViPham . ' ' . $gioViPham;

            // Thực hiện INSERT vào bảng bao_cao (ma_bao_cao là AUTO_INCREMENT)
            $insertQuery = "
            INSERT INTO bao_cao (
                bien_so_xe,
                dia_diem,
                hinh_anh,
                loai_vi_pham,
                mo_ta,
                ngay_gio_vi_pham,
                trang_thai
            ) VALUES (?, ?, ?, ?, ?, ?, 0)
        ";

            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssssss", $bienSoXe, $diaDiemViPham, $hinhAnh, $loaiViPham, $moTaChiTiet, $ngayGioViPham);

            if ($stmt->execute()) {
                // Nếu lưu dữ liệu thành công, chuyển hướng về trang chủ (index.php)
                header("Location: /BTL/frm/frm_user/index.php");
                exit;
            } else {
                echo "Lỗi khi lưu dữ liệu: " . mysqli_error($conn);
            }

            $stmt->close();
        }
    }
    // Nếu là hành động của Admin (chấp nhận hoặc từ chối)
    elseif (isset($_POST['action'])) {
        $ma_bao_cao = $_POST['ma_bao_cao'];
        if ($_POST['action'] == "accept") {
            // Lấy tiền phạt do Admin nhập
            $tien_phat = isset($_POST['tien_phat']) ? (int)$_POST['tien_phat'] : 0;
            // Cập nhật trạng thái = 1 (đã duyệt) + tiền phạt
            $query = "UPDATE bao_cao SET trang_thai = 1, tien_phat = ? WHERE ma_bao_cao = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $tien_phat, $ma_bao_cao);
        } elseif ($_POST['action'] == "reject") {
            $ly_do = isset($_POST['ly_do']) ? $_POST['ly_do'] : 0;
            // Nếu từ chối, chỉ cập nhật trạng thái = 2 (bị từ chối)
            $query = "UPDATE bao_cao SET trang_thai = 2,ly_do = ? WHERE ma_bao_cao = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $ly_do ,$ma_bao_cao);
        }
        if ($stmt->execute()) {
            // Sau hành động của Admin, chuyển hướng về trang home admin
            header("Location: /BTL/frm/frm_admin/index.php");
            exit;
        }
        $stmt->close();
    }
}

// Lấy số liệu thống kê: số báo cáo chưa duyệt (0) và đã duyệt (1)
$sql_count = "SELECT 
    SUM(CASE WHEN trang_thai = 0 THEN 1 ELSE 0 END) AS chua_duyet,
    SUM(CASE WHEN trang_thai = 1 THEN 1 ELSE 0 END) AS da_duyet
    FROM bao_cao";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$chua_duyet = $row_count['chua_duyet'];
$da_duyet   = $row_count['da_duyet'];

// Lấy danh sách báo cáo có trang_thai = 0 (chưa duyệt)
$sql_reports = "SELECT * FROM bao_cao WHERE trang_thai = 0 ORDER BY ngay_gio_vi_pham DESC";
$result_reports = $conn->query($sql_reports);
?>

<link rel="stylesheet" href="/BTL/assets/css_form_admin/home.css">

<div class="admin-container">
    <h2>Chào mừng, Quản trị viên</h2>
    <p>Quản lý các báo cáo vi phạm giao thông và thống kê hệ thống.</p>

    <!-- Thống kê báo cáo -->
    <div class="stats-container">
        <div class="stat-box wide">
            <h3>Báo cáo chưa duyệt</h3>
            <p><?php echo $chua_duyet; ?></p>
        </div>
        <div class="stat-box wide">
            <h3>Báo cáo đã duyệt</h3>
            <p><?php echo $da_duyet; ?></p>
        </div>
        <div class="stat-box wide">
            <h3>Số phần thưởng đã phát</h3>
            <p>$12,345</p>
        </div>
    </div>

    <!-- Danh sách báo cáo (chưa duyệt) -->
    <div class="report-list-container">
        <?php while ($row = $result_reports->fetch_assoc()) { ?>
            <div class="report-item">
                <div>
                    <h4><?php echo htmlspecialchars($row['loai_vi_pham']); ?>
                        - Biển số: <?php echo htmlspecialchars($row['bien_so_xe']); ?>
                    </h4>
                    <p>Địa điểm: <?php echo htmlspecialchars($row['dia_diem']); ?></p>
                    <p>Ngày: <?php echo htmlspecialchars($row['ngay_gio_vi_pham']); ?></p>
                    <p>Mô tả: <?php echo nl2br(htmlspecialchars($row['mo_ta'])); ?></p>
                    <?php if (!empty($row['hinh_anh'])) { ?>
                        <p>
                            <img src="/BTL/img/<?php echo htmlspecialchars($row['hinh_anh']); ?>"
                                alt="Hình ảnh vi phạm" width="200">
                        </p>
                    <?php } ?>
                </div>
                <div>
                    <!-- Form xử lý duyệt hoặc từ chối -->
                    <form method="POST" action="/BTL/frm/frm_admin/home.php">
                        <input type="hidden" name="ma_bao_cao" value="<?php echo $row['ma_bao_cao']; ?>">
                        <!-- Admin nhập tiền phạt -->
                        <label for="tien_phat">Tiền phạt:</label>
                        <input type="number" name="tien_phat" placeholder="Nhập tiền phạt"
                            style="width: 100px; margin-bottom: 8px;">
                        <label for="ly_do">Mô tả:</label>
                        <input type="text" name="ly_do" placeholder="Nhập nội dung"
                            style="width: 200px; 
                                    margin-bottom: 8px;
                                    padding: 5px; 
                                    border: 1px solid #ccc;
                                    border-radius: 4px;">
                        <button type="submit" name="action" value="accept">Chấp nhận</button>
                        <button type="submit" name="action" value="reject" class="reject">Từ chối</button>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Bản đồ vi phạm -->
    <div class="map-container">
        <div class="stat-box map-box">
            <h3>Bản đồ vi phạm</h3>
            <p>Xem báo cáo gần đây trên bản đồ</p>
            <div class="map-placeholder">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.637064541317!2d106.67584897593205!3d10.762663459309488!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f38c74b4fbd%3A0x79b7129a47d3b19a!2zMTIzIMSQLiBBQkMsIFF14bqtbiAxLCBUUC5IQ00!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s"
                    width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>
    </div>
</div>

<?php
mysqli_close($conn);
?>