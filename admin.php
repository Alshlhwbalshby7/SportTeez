<?php
include 'config.php';

 
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
 
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('تم حذف المنتج بنجاح!'); window.location.href='admin.php';</script>";
    } else {
        echo "خطأ: " . $stmt->error;
    }
}