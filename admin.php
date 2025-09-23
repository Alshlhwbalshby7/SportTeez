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

// استرجاع المنتجات
$sql = "SELECT id, name, description, price, image FROM products";
$result = $conn->query($sql);

$conn->close();
?>

<?php
if (isset($_SESSION['username'])) {
    echo "<div class='auth-buttons'>";
    echo "<span class='welcome-text'>مرحبًا، " . $_SESSION['username'] . "!</span>";
    echo "<a href='logout.php' class='btn logout-btn'>تسجيل الخروج</a>";
    echo "</div>";
} else {
    echo "<div class='auth-buttons'>";
    echo "<a href='login.php' class='btn login-btn'>تسجيل الدخول</a>";
    echo "</div>";
}
?>
<!DOCTYPE html>
<html lang="ar">
    <head>
    <meta charset="UTF-8">
    <title>إدارة المنتجات</title>
    <style>

            body {
            font-family: Arial, sans-serif;
            background-color: rgb(115, 237, 168);
            text-align: center;
        }
     form {
            background: white;
            padding: 50px;
            width: 350px;
            margin: auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
           label {
            display: block;
            font-weight: bold;
           margin: 10px 0 5px;
        }