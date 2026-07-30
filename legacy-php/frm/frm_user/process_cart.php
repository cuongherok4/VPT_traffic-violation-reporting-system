<?php
session_start();

if (isset($_POST['btnAddToCart']) && isset($_POST['ma_san_pham'])) {
    $maSanPham = $_POST['ma_san_pham'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (!isset($_SESSION['cart'][$maSanPham])) {
        $_SESSION['cart'][$maSanPham] = 1;
    } else {
        $_SESSION['cart'][$maSanPham] += 1;
    }
    header("Location: shop.php");
    exit;
}
elseif (isset($_POST['btnBuyNow']) && isset($_POST['ma_san_pham'])) {
    $maSanPham = $_POST['ma_san_pham'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][$maSanPham] = 1;
    header("Location: cart.php");
    exit;
}
elseif (isset($_POST['btnIncrease']) && isset($_POST['ma_san_pham'])) {
    $maSanPham = $_POST['ma_san_pham'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    // Tăng số lượng
    $_SESSION['cart'][$maSanPham] = ($_SESSION['cart'][$maSanPham] ?? 0) + 1;
    header("Location: cart.php");
    exit;
}
elseif (isset($_POST['btnDecrease']) && isset($_POST['ma_san_pham'])) {
    $maSanPham = $_POST['ma_san_pham'];
    if (isset($_SESSION['cart'][$maSanPham])) {
        $_SESSION['cart'][$maSanPham] -= 1;
        // Nếu số lượng giảm xuống 0, loại bỏ sản phẩm khỏi giỏ
        if ($_SESSION['cart'][$maSanPham] <= 0) {
            unset($_SESSION['cart'][$maSanPham]);
        }
    }
    header("Location: cart.php");
    exit;
}
else {
    header("Location: shop.php");
    exit;
}
?>
