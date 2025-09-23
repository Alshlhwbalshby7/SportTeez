<?php
session_start();
include 'config.php';

// إعادة التوجيه إلى صفحة تسجيل الدخول إذا لم يكن المستخدم مسجلاً الدخول
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit(); // إيقاف تنفيذ الكود بعد إعادة التوجيه
}

 
 

// البحث عن المنتجات
$search = "";
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $like_search = "%" . $search . "%";  
    $sql = "SELECT * FROM products WHERE name LIKE '$like_search' OR description LIKE '$like_search'";  
    $result = $conn->query($sql);  
} else {
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);  // استعلام جلب جميع المنتجات
}

?>
 

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>متجر الملابس الرياضية</title>
    <link rel="stylesheet" href="css.css">
    <script>
        // دالة إضافة المنتج إلى السلة
        function addToCart(productId) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "add_to_cart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert(xhr.responseText);
                }
            };
            xhr.send("id=" + productId); 
        }
    </script>
</head>
<body id="body_index">

<div class="container">
    <div class="user-info">
        <?php
        echo "مرحبًا، " . $_SESSION['username'] . "! | <a class='lout' href='logout.php'>تسجيل الخروج</a>";
        ?>
    </div>

    <h1 style="text-align: center;">المنتجات المتاحة</h1>

    <!-- نموذج البحث -->
    <div class="search-bar">
        <form method="GET">
            <input type="text" name="search" placeholder="ابحث عن منتج..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">بحث</button>
        </form>
    </div>

    <div class="product-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product">';
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" alt="' . $row['name'] . '">';
                echo '<h2>' . $row['name'] . '</h2>';
                echo '<p>' . $row['description'] . '</p>';
                echo '<p>السعر: ' . $row['price'] . ' ريال</p>';
                echo '<button onclick="addToCart(' . $row['id'] . ')">إضافة إلى السلة</button>';
                echo '</div>';
            }
        } else {
            echo "<p style='text-align: center;'>لا توجد منتجات مطابقة لبحثك.</p>";
        }
        ?>
    </div>

    <a id="aa" href="cart.php">عرض السلة</a>
</div>

</body>
</html>

<?php
$conn->close();
?>