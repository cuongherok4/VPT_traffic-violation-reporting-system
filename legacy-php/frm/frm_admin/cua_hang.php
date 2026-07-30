<?php
require_once __DIR__ . "/../../db/config.php";

// Lấy thông tin sản phẩm nếu đang ở chế độ sửa
$editData = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $sql = "SELECT * FROM san_pham WHERE ma_san_pham = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $editData = $result->fetch_assoc();
    }
    $stmt->close();
}

// Lấy danh sách danh mục để hiển thị trong dropdown
$sqlDanhMuc = "SELECT ma_danh_muc, ten_danh_muc FROM danh_muc_san_pham";
$resultDanhMuc = $conn->query($sqlDanhMuc);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="/BTL/assets/css_form_admin/cua_hang.css">
</head>
<body>

<!-- Form thêm/cập nhật sản phẩm -->
<div class="product-form-container">
    <h2><?php echo $editData ? "Cập nhật Sản Phẩm" : "Thêm Sản Phẩm"; ?></h2>
    <form action="process_cua_hang.php" method="POST" enctype="multipart/form-data">
        <!-- Nếu đang sửa, chứa mã sản phẩm ẩn -->
        <?php if ($editData): ?>
            <input type="hidden" name="ma_san_pham" value="<?php echo $editData['ma_san_pham']; ?>">
        <?php endif; ?>

        <label>Tên sản phẩm:</label>
        <input type="text" name="ten_sp" required
               value="<?php echo $editData ? htmlspecialchars($editData['ten_sp']) : ''; ?>">

        <label>Số lượng:</label>
        <input type="number" name="so_luong" required
               value="<?php echo $editData ? (int)$editData['so_luong'] : ''; ?>">

        <label>Mô tả:</label>
        <input type="text" name="mo_ta"
               value="<?php echo $editData ? htmlspecialchars($editData['mo_ta']) : ''; ?>">

        <label>Giá bán:</label>
        <input type="number" step="0.01" name="gia_ban" required
               value="<?php echo $editData ? (float)$editData['gia_ban'] : ''; ?>">

        <!-- Chọn danh mục -->
        <label>Danh mục:</label>
        <select name="ma_danh_muc" required>
            <option value="">-- Chọn danh mục --</option>
            <?php if ($resultDanhMuc && $resultDanhMuc->num_rows > 0): ?>
                <?php while ($dm = $resultDanhMuc->fetch_assoc()): ?>
                    <?php 
                        $selected = ($editData && $editData['ma_danh_muc'] == $dm['ma_danh_muc']) ? 'selected' : ''; 
                    ?>
                    <option value="<?php echo $dm['ma_danh_muc']; ?>" <?php echo $selected; ?>>
                        <?php echo $dm['ten_danh_muc']; ?>
                    </option>
                <?php endwhile; ?>
            <?php endif; ?>
        </select>

        <label>Chọn hình ảnh:</label>
        <input type="file" name="hinh_anh" accept="image/*" <?php echo $editData ? "" : "required"; ?>>
        <?php if ($editData && !empty($editData['hinh_anh'])): ?>
            <p>Hình hiện tại: 
                <img src="uploads/<?php echo $editData['hinh_anh']; ?>" alt="Hình sản phẩm" width="80">
            </p>
        <?php endif; ?>

        <button type="submit" 
                name="<?php echo $editData ? 'btnCapNhat' : 'btnThemMoi'; ?>">
            <?php echo $editData ? "Cập nhật sản phẩm" : "Thêm sản phẩm"; ?>
        </button>
    </form>
</div>

<!-- Danh sách sản phẩm -->
<div class="product-list-container">
    <h2>Danh Sách Sản Phẩm</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Mô tả</th>
            <th>Giá bán</th>
            <th>Mã danh mục</th>
            <th>Hình ảnh</th>
            <th>Hành động</th>
        </tr>
        <?php
        // Lấy danh sách sản phẩm
        $sql = "SELECT * FROM san_pham";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['ma_san_pham']}</td>
                        <td>{$row['ten_sp']}</td>
                        <td>{$row['so_luong']}</td>
                        <td>{$row['mo_ta']}</td>
                        <td>" . number_format($row['gia_ban']) . " VNĐ</td>
                        <td>{$row['ma_danh_muc']}</td>
                        <td><img src='uploads/{$row['hinh_anh']}' alt='Ảnh SP' width='50'></td>
                        <td>
                            <!-- Link sửa -->
                            <a href='cua_hang.php?edit={$row['ma_san_pham']}'>Sửa</a> |
                            <!-- Link xóa -->
                            <a href='process_cua_hang.php?delete={$row['ma_san_pham']}'
                               onclick='return confirm(\"Bạn có chắc muốn xóa?\");'>
                                Xóa
                            </a>
                        </td>
                     </tr>";
            }
        } else {
            echo "<tr><td colspan='9'>Không có sản phẩm nào</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</div>

</body>
</html>
