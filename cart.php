<?php
session_start();

// إعادة التوجيه إلى صفحة تسجيل الدخول إذا لم يكن المستخدم مسجلاً الدخول
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit(); // إيقاف تنفيذ الكود بعد إعادة التوجيه
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// إزالة منتج واحد من السلة
if (isset($_GET['remove_from_cart'])) {
    $product_id = intval($_GET['remove_from_cart']);
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        $_SESSION['message'] = "تمت إزالة المنتج من السلة.";
    }
}

// مسح السلة بالكامل
if (isset($_GET['clear_cart'])) {
    $_SESSION['cart'] = [];
    $_SESSION['message'] = "تمت إزالة جميع المنتجات من السلة.";
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>عربة التسوق</title>
    <link rel="stylesheet" href="css.css">
</head>
<body id="body_cart">

<div class="container">
    <h1>عربة التسوق</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <?php if (empty($_SESSION['cart'])): ?>
        <p>السلة فارغة.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($_SESSION['cart'] as $id => $product): ?>
                <li>
                    <span><?php echo $product['name']; ?> - <?php echo $product['price']; ?> ريال (<?php echo $product['quantity']; ?>)</span>
                    <a href="cart.php?remove_from_cart=<?php echo $id; ?>" class="btn remove-btn">إزالة</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="cart.php?clear_cart=1" class="btn clear-btn">مسح السلة</a>
    <?php endif; ?>

    <a href="index.php" class="back-btn">العودة إلى المتجر</a>
</div>

</body>
</html>