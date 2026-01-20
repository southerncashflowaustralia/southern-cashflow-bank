<?php
session_start();
require_once __DIR__ . "/../config/db.php";

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: ../pages/dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        if ($user['status'] !== 'active') {
            $error = "Your account is not active. Please contact support.";
        } else {
            // Login success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            header("Location: ../pages/dashboard.php");
            exit;
        }

    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Secure Login | Southern Cashflow Finance</title>
    <style>
        body {
            margin:0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #0b3a6e, #0f5fa8);
            height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        .login-box {
            background:#fff;
            width:400px;
            padding:30px;
            border-radius:10px;
            box-shadow:0 10px 30px rgba(0,0,0,0.2);
        }

        .login-box h1 {
            text-align:center;
            margin-bottom:10px;
            color:#0b3a6e;
        }

        .login-box p {
            text-align:center;
            font-size:14px;
            color:#666;
            margin-bottom:25px;
        }

        .login-box label {
            display:block;
            margin-bottom:5px;
            font-weight:bold;
            font-size:14px;
        }

        .login-box input {
            width:100%;
            padding:12px;
            margin-bottom:20px;
            border:1px solid #ccc;
            border-radius:6px;
            font-size:14px;
        }

        .login-box button {
            width:100%;
            padding:14px;
            background:#0b3a6e;
            border:none;
            color:#fff;
            font-size:16px;
            font-weight:bold;
            border-radius:6px;
            cursor:pointer;
        }

        .login-box button:hover {
            background:#09508b;
        }

        .error {
            background:#ffe0e0;
            color:#a40000;
            padding:10px;
            border-radius:6px;
            margin-bottom:15px;
            text-align:center;
            font-size:14px;
        }

        .footer-text {
            text-align:center;
            font-size:12px;
            margin-top:15px;
            color:#777;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h1>Southern Cashflow Finance</h1>
    <p>Secure Online Banking Login</p>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Email Address</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Sign In</button>
    </form>

    <div class="footer-text">
        © <?= date("Y") ?> Southern Cashflow Finance · All rights reserved
    </div>
</div>

</body>
</html>