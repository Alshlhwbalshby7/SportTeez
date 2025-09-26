<?php
session_start();
include 'config.php';

$error_message = "";  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

                  
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

        
            if ($user['username'] === "admin") {
                header("Location: admin.php"); // توجيه المسؤول إلى لوحة التحكم
            } else {
                header("Location: index.php");  
            }
            exit();
        } else {
            $error_message = "❌ كلمة المرور  ليست صحيحة.";
        }
    } else {
        $error_message = "❌ اسم المستخدم  ليس موجود.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="css.css">
    <style>
        /* تنسيق رسالة الخطأ */
        .error-message {
            color: red;
            background: #ffe6e6;
            padding: 10px;
            border: 1px solid red;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body id="body_login">
    <!-- <h1 id="h1l">تسجيل الدخول</h1> -->
    <div class="al">
        <form method="POST">
            <?php if (!empty($error_message)) { ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php } ?>
            <label id="h1l">تسجيل الدخول</label><br><br>
            <label id="pu" for="username">اسم المستخدم:</label><br>
            <input id="pu" type="text" name="username" required><br><br>

            <label for="password">كلمة المرور:</label><br>
            <input id="pu" type="password" name="password" required><br>

            <button id="bo" type="submit">تسجيل الدخول</button>
        </form>
        <p>ليس لديك حساب؟ <a href="register.php">سجل هنا</a></p>
    </div>
</body>
</html>

<?php
$conn->close();
?>