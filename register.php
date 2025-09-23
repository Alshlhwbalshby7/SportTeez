<?php
include 'config.php';

$message = "";  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // تشفير كلمة المرور
    $email = trim($_POST['email']);

 
    $check_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("ss", $username, $email);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        $message = "<div class='error-message'>❌ اسم المستخدم أو البريد الإلكتروني مسجل بالفعل!</div>";
    } else {
  
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $email);

        if ($stmt->execute()) {
            $message = "<div class='success-message'>✔️ تم تسجيل المستخدم بنجاح!</div>";
        } else {
            $message = "<div class='error-message'>❌ حدث خطأ أثناء التسجيل: " . $conn->error . "</div>";
        }
        $stmt->close();
    }

    $stmt_check->close();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل مستخدم جديد</title>
    <style>
        body {
            background-color: black;
            font-family: Arial, sans-serif;
            text-align: center;
            direction: rtl;
            margin: 50px;
        }
        .container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 5px;
            margin-bottom: 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #218838;
        }
        .message {
            margin-bottom: 10px;
        }
        .success-message {
            color: green;
            background: #e6ffe6;
            padding: 10px;
            border: 1px solid green;
            border-radius: 5px;
        }
        .error-message {
            color: red;
            background: #ffe6e6;
            padding: 10px;
            border: 1px solid red;
            border-radius: 5px;
        }
        p {
            margin-top: 15px;
        }
        a {
            text-decoration: none; /* إزالة الخط من الرابط */
            color: blue;
            font-weight: bold;
        }
        a:hover {
            color: darkblue;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>تسجيل مستخدم جديد</h1>
    
    <!-- عرض الرسالة هنا -->
    <?php echo $message; ?>

    <form method="POST">
        <label for="username">اسم المستخدم:</label>
        <input type="text" name="username" required>

        <label for="password">كلمة المرور:</label>
        <input type="password" name="password" required>

        <label for="email">البريد الإلكتروني:</label>
        <input type="email" name="email" required>

        <button type="submit" class="btn">تسجيل</button>
    </form>

    <p>لديك حساب بالفعل؟ <a href="login.php">سجل الدخول هنا</a></p>
</div>

</body>
</html>

<?php
$conn->close();
?>