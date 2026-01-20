<?php
session_start();
require_once __DIR__ . "/config/db.php"; // fixed path

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $balance = 0;
    $account_number = rand(1000000000, 9999999999); // random 10-digit account number
    $role = 'customer';
    $status = 'active';
    $created_at = date("Y-m-d H:i:s");

    $stmt = $pdo->prepare("
        INSERT INTO users 
        (full_name, email, password, balance, account_number, role, status, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $fullname,
        $email,
        $password,
        $balance,
        $account_number,
        $role,
        $status,
        $created_at
    ]);

    header("Location: customer/accessaccount.php"); // redirect to login after account creation
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Account | Southern Cashflow Finance</title>
    <style>
        body {
            margin:0;
            font-family: Arial, sans-serif;
            background:#f4f6f8;
        }
        .container {
            max-width:500px;
            margin:90px auto;
            background:#fff;
            padding:40px;
            border-radius:10px;
            box-shadow:0 5px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align:center;
            color:#004466;
            margin-bottom:30px;
        }
        label {
            display:block;
            margin:15px 0 5px;
            font-weight:bold;
        }
        input {
            width:100%;
            padding:12px;
            border:1px solid #ccc;
            border-radius:6px;
        }
        button {
            width:100%;
            padding:15px;
            margin-top:25px;
            background:#004466;
            color:#fff;
            border:none;
            border-radius:8px;
            font-size:16px;
            font-weight:bold;
            cursor:pointer;
        }
        button:hover {
            background:#006699;
        }
        .message {
            color:red;
            text-align:center;
            margin-bottom:15px;
        }
        .login-link {
            text-align:center;
            margin-top:20px;
        }
        .login-link a {
            color:#004466;
            font-weight:bold;
            text-decoration:none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Create Your Account</h2>

    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Full Name</label>
        <input type="text" name="fullname" required>

        <label>Email Address</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Create Account</button>
    </form>

    <div class="login-link">
        Already have an account?
        <a href="customer/accessaccount.php">Access Account</a>
    </div>
</div>

</body>
</html>