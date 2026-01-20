<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Settings | Southern Cashflow Finance</title>
    <style>
        body { font-family: Arial, sans-serif; margin:30px; background:#f4f6f8; }
        h1 { color:#0b3a6e; }
        label { display:block; margin-top:15px; font-weight:bold; }
        input { width:300px; padding:10px; margin-top:5px; border-radius:5px; border:1px solid #ccc; }
        button { margin-top:20px; padding:12px 25px; background:#0b3a6e; color:#fff; border:none; border-radius:5px; cursor:pointer; }
        button:hover { background:#09508b; }
    </style>
</head>
<body>
    <h1>Account Settings</h1>
    <form method="post">
        <label>Full Name</label>
        <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" disabled>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter new password">

        <button type="submit">Update Settings</button>
    </form>
</body>
</html>