<?php
require_once __DIR__ . "/../../db/config.php"; // Kết nối CSDL

// Chuẩn bị biến để chứa dữ liệu người dùng cần sửa (nếu có)
$editData = null;

// Kiểm tra xem có tham số GET 'edit' => Lấy dữ liệu user để hiển thị form sửa
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE ma_nguoi_dung = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $editData = $result->fetch_assoc();
    }
    $stmt->close();
}

// Lấy danh sách quyền để hiển thị dropdown
$sqlQuyen = "SELECT ma_quyen, ten_quyen FROM phan_quyen ORDER BY ma_quyen ASC";
$resultQuyen = $conn->query($sqlQuyen);

// Xử lý hiển thị danh sách người dùng
$sqlUser = "SELECT nd.*, pq.ten_quyen 
            FROM nguoi_dung nd 
            LEFT JOIN phan_quyen pq ON nd.ma_quyen = pq.ma_quyen
            ORDER BY nd.ma_nguoi_dung DESC";
$resultUser = $conn->query($sqlUser);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Người Dùng</title>
    <link rel="stylesheet" href="/BTL/assets/css_form_admin/nguoi_dung.css">
</head>
<body>

<h2><?php echo $editData ? "Sửa Thông Tin Người Dùng" : "Thêm Người Dùng"; ?></h2>

<!-- Form Thêm/Sửa Người Dùng -->
<form action="process_nguoi_dung.php" method="POST">
    <?php if ($editData): ?>
        <input type="hidden" name="ma_nguoi_dung" value="<?php echo $editData['ma_nguoi_dung']; ?>">
    <?php endif; ?>

    <label for="tai_khoan">Tài khoản:</label>
    <input type="text" name="tai_khoan" id="tai_khoan" required
           value="<?php echo $editData ? htmlspecialchars($editData['tai_khoan']) : ''; ?>">

    <label for="mat_khau">Mật khẩu:</label>
    <input type="text" name="mat_khau" id="mat_khau" required
           value="<?php echo $editData ? htmlspecialchars($editData['mat_khau']) : ''; ?>">

    <label for="ma_quyen">Quyền:</label>
    <select name="ma_quyen" id="ma_quyen" required>
        <option value="">-- Chọn quyền --</option>
        <?php if ($resultQuyen && $resultQuyen->num_rows > 0): ?>
            <?php while($rowQ = $resultQuyen->fetch_assoc()): ?>
                <?php
                $selected = '';
                if ($editData && $editData['ma_quyen'] == $rowQ['ma_quyen']) {
                    $selected = 'selected';
                }
                ?>
                <option value="<?php echo $rowQ['ma_quyen']; ?>" <?php echo $selected; ?>>
                    <?php echo $rowQ['ten_quyen']; ?>
                </option>
            <?php endwhile; ?>
        <?php endif; ?>
    </select>

    <?php if ($editData): ?>
        <button type="submit" name="btnCapNhat">Cập nhật</button>
    <?php else: ?>
        <button type="submit" name="btnThemMoi">Thêm mới</button>
    <?php endif; ?>
</form>

<hr>

<!-- Danh Sách Người Dùng -->
<h2>Danh Sách Người Dùng</h2>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Mã</th>
        <th>Tài khoản</th>
        <th>Mật khẩu</th>
        <th>Quyền</th>
        <th>Hành động</th>
    </tr>
    <?php
    if ($resultUser && $resultUser->num_rows > 0):
        while($rowU = $resultUser->fetch_assoc()):
    ?>
    <tr>
        <td><?php echo $rowU['ma_nguoi_dung']; ?></td>
        <td><?php echo htmlspecialchars($rowU['tai_khoan']); ?></td>
        <td><?php echo htmlspecialchars($rowU['mat_khau']); ?></td>
        <td><?php echo htmlspecialchars($rowU['ten_quyen'] ?: ''); ?></td>
        <td>
            <!-- Link Sửa -->
            <a href="qLy_nguoiDung.php?edit=<?php echo $rowU['ma_nguoi_dung']; ?>">Sửa</a> | 
            <!-- Xóa -->
            <form action="process_nguoi_dung.php" method="POST" style="display:inline;" 
                  onsubmit="return confirm('Xác nhận xóa người dùng này?');">
                <input type="hidden" name="ma_nguoi_dung" value="<?php echo $rowU['ma_nguoi_dung']; ?>">
                <button type="submit" name="btnXoa" style="color:red;">Xóa</button>
            </form>
        </td>
    </tr>
    <?php
        endwhile;
    else:
        echo "<tr><td colspan='5'>Chưa có người dùng nào</td></tr>";
    endif;
    ?>
</table>

</body>
</html>
