<?php
session_start();

 
if (isset($_POST['id'])) {
    $productId = intval($_POST['id']);

    
    include 'config.php';
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
 
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();  
        }

        // إضافة المنتج إلى السلة
        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1 // تعيين الكمية الافتراضية إلى 1
            ];
            echo "تم إضافة المنتج إلى السلة بنجاح!";
        } else {
            $_SESSION['cart'][$productId]['quantity'] += 1; 
            echo "تم تحديث الكمية في السلة!";
        }
    } else {
        echo "المنتج غير موجود.";
    }
}
?>