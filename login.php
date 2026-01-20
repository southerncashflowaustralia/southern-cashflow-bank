<?php 
session_start();
require_once __DIR__ . "/../config/db.php";

$error = "";

// If already logged in, go to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: ../customer/dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $account_number = trim($_POST['account_number']);
    $password = $_POST['password'];

    // Fetch user by account number
    $stmt = $pdo->prepare("
        SELECT id, full_name, account_number, password_hash, role, status
        FROM users
        WHERE account_number = ?
        LIMIT 1
    ");
    $stmt->execute([$account_number]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {

        if ($user['status'] !== 'active') {
            $error = "Your account is not active. Please contact support.";
        } else {
            // Login success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['account_number'] = $user['account_number'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];

            // Update last_login
            $update = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $update->execute([$user['id']]);

            header("Location: ../customer/dashboard.php");
            exit;
        }

    } else {
        $error = "Invalid account number or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Access Account | Southern Cashflow Finance</title>
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
        label {
            display:block;
            margin-bottom:5px;
            font-weight:bold;
            font-size:14px;
        }
        input {
            width:100%;
            padding:12px;
            margin-bottom:20px;
            border:1px solid #ccc;
            border-radius:6px;
            font-size:14px;
        }
        button {
            width:100%;
            padding:14px;
            background:#ffcc00;
            border:none;
            color:#004466;
            font-size:16px;
            font-weight:bold;
            border-radius:6px;
            cursor:pointer;
        }
        button:hover {
            background:#e6b800;
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
        .create-link {
            display:block;
            text-align:center;
            margin-top:15px;
            font-size:14px;
            color:#004466;
            font-weight:bold;
            text-decoration:none;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h1>Southern Cashflow Finance</h1>
    <p>Access Your Account Securely</p>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Account Number</label>
        <input type="text" name="account_number" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Access Account</button>
    </form>

    <a class="create-link" href="../createaccount.php">
        Don’t have an account? Create Account
    </a>

    <div class="footer-text">
        © <?= date("Y") ?> Southern Cashflow Finance · All rights reserved
    </div>
</div>

</body>
</html>
