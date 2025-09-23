<?php
session_start();

// تدمير الجلسة وتسجيل الخروج
if (isset($_SESSION['username'])) {
    // تعيين رسالة نجاح تسجيل الخروج
    $_SESSION['logout_message'] = "تم تسجيل خروجك بنجاح!";

    // تدمير الجلسة
    session_destroy();
    session_unset();
}

// إعادة التوجيه إلى صفحة تسجيل الدخول
header("Location: login.php");
exit();
?>