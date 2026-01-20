<?php 
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Withdraw | Southern Cashflow Finance</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f2f5f7; margin:0; }
        .container { max-width:600px; margin:80px auto; background:#fff; padding:40px; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.1); text-align:center; }
        h2 { color:#0b3a6e; margin-bottom:30px; }
        .btn { display:block; width:80%; margin:15px auto; padding:15px; font-size:16px; font-weight:bold; border:none; border-radius:8px; cursor:pointer; color:#fff; transition:0.3s; text-decoration:none; }
        .atm { background:#28a745; }
        .atm:hover { background:#218838; }
        .bank { background:#0b3a6e; }
        .bank:hover { background:#09508b; }
        .back { display:block; margin-top:30px; color:#0b3a6e; text-decoration:none; font-weight:bold; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Withdraw Funds</h2>

        <a href="withdraw-atm.php" class="btn atm">💵 ATM Withdrawal</a>
        <a href="withdraw-bank.php" class="btn bank">🏦 Bank Withdrawal</a>

        <a href="dashboard.php" class="back">← Back to Dashboard</a>
    </div>
</body>
</html>