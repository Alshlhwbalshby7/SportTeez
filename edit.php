<?php
session_start();
include 'config.php';

// التحقق من حالة تسجيل الدخول
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// استرجاع بيانات المنتج للتعديل
if (!empty($_GET['id'])) {
    $id = intval($_GET['id']); 

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();

//     if (!$row) {
//         echo "لم يتم العثور على المنتج!";
//         exit();
//     }
// } else {
//     echo "معرف المنتج غير صالح!";
//     exit();
}

// معالجة طلب التعديل
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $sql = "UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdi", $name, $description, $price, $id);
    if ($stmt->execute()) {
        echo "<p>تم تحديث المنتج بنجاح.</p>";
        header("Location: admin.php");
        exit();
    } else {
        echo "<p>حدث خطأ أثناء التحديث.</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تعديل المنتج</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        form {
            background: white;
            padding: 20px;
            width: 300px;
            margin: 100px auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            border-radius: 5px;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>تعديل المنتج</h1>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label for="name">اسم المنتج:</label>
        <input type="text" name="name" value="<?php echo $row['name']; ?>" required><br>
        <label for="description">وصف المنتج:</label>
        <textarea name="description" required><?php echo $row['description']; ?></textarea><br>
        <label for="price">السعر:</label>
        <input type="number" name="price" step="0.01" value="<?php echo $row['price']; ?>" required><br>
        <button type="submit">حفظ التعديلات</button>
    </form>
    <a href="admin.php" style="color: #dc3545; text-decoration: none; font-size:30px">العودة إلى الصفحة الرئيسية</a>
</body>
</html>
<?php
$conn->close();
?>