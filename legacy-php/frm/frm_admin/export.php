<?php
include('../../db/config.php');

// Tắt hiển thị lỗi trong file CSV
error_reporting(0);
ini_set('display_errors', 0);

// Xóa bộ nhớ đệm để tránh lỗi dữ liệu dư thừa
ob_clean();

// Thiết lập header cho file CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="thong_ke_vi_pham.csv"');

// Tạo file output
$output = fopen('php://output', 'w');

// Thêm BOM để hiển thị tiếng Việt trong Excel
fputs($output, "\xEF\xBB\xBF");

// Tiêu đề các cột
fputcsv($output, ['Loại vi phạm', 'Số báo cáo'], ',', '"');

// Truy vấn dữ liệu
$sql = "SELECT loai_vi_pham, COUNT(*) as count 
        FROM bao_cao 
        GROUP BY loai_vi_pham 
        ORDER BY count DESC";
$result = $conn->query($sql);

// Ghi dữ liệu vào file CSV
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['loai_vi_pham'],
            $row['count']
        ], ',', '"');
    }
}

// Đóng kết nối CSDL
$conn->close();
exit();
?>
