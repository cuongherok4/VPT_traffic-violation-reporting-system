<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Tin Tức</title>
    <link rel="stylesheet" href="/BTL/assets/css_form_admin/tin_tuc.css">
</head>
<body>

<?php
// Nếu muốn hiển thị thông báo (vd: khi redirect kèm ?msg=...), có thể kiểm tra ở đây:
if (isset($_GET['msg'])) {
    echo '<p class="message">' . htmlspecialchars($_GET['msg']) . '</p>';
}
?>

<form id="newsForm" action="process_tin_tuc.php" method="POST" enctype="multipart/form-data">
    <h2>Đăng Tin Tức</h2>

    <label for="tenTinTuc">Tiêu đề:</label>
    <input type="text" id="tenTinTuc" name="tenTinTuc" required placeholder="Nhập tiêu đề tin tức">

    <label for="date">Ngày đăng:</label>
    <input type="date" id="date" name="date" required>

    <label for="time">Giờ đăng:</label>
    <input type="time" id="time" name="time" required>

    <label for="noiDung">Nội dung:</label>
    <textarea id="noiDung" name="noiDung" rows="6" required placeholder="Nhập nội dung tin tức"></textarea>

    <label for="hinhAnh">Hình ảnh minh họa:</label>
    <input type="file" id="hinhAnh" name="hinhAnh" accept="image/*">

    <button type="submit" name="btnDangTin">Đăng tin</button>

    <div id="newsGuidelines">
        <h3>📌 Yêu cầu trước khi đăng tin:</h3>
        <ul>
            <li>✔️ Nội dung tin tức phải chính xác, không chứa thông tin sai lệch.</li>
            <li>✔️ Không đăng tải tin tức mang tính kích động, thù địch hoặc vi phạm pháp luật.</li>
            <li>✔️ Hình ảnh đính kèm phải rõ ràng, không vi phạm bản quyền.</li>
            <li>✔️ Tiêu đề tin tức cần ngắn gọn, súc tích và phản ánh đúng nội dung.</li>
            <li>✔️ Kiểm tra lại thông tin trước khi gửi để tránh sai sót.</li>
        </ul>
    </div>
</form>

</body>
</html>
