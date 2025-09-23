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
}// إضافة منتج جديد
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = file_get_contents($_FILES["image"]["tmp_name"]);
        $sql = "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdb", $name, $description, $price, $null);
        $stmt->send_long_data(3, $image);

        if ($stmt->execute()) {
            echo "<script>alert('تمت إضافة المنتج بنجاح!'); window.location.href='admin.php';</script>";
        } else {
            echo "خطأ: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "لم يتم تحميل صورة.";
        exit;
    }
}
