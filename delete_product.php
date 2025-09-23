<?php
session_start();
include 'config.php';

// التحقق من حالة تسجيل الدخول
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// التحقق من وجود معرف المنتج
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // استعلام لحذف المنتج
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "تم حذف المنتج بنجاح.";
    } else {
        echo "حدث خطأ أثناء الحذف: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "معرف المنتج غير محدد.";
}

$conn->close();

إعادة التوجيه إلى الصفحة الرئيسية بعد الحذف
header("Location: index.php");
exit();
?>