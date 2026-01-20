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

$message = "";

// Daily deposit limit
$dailyLimit = 3000000.00; // AUD 3,000,000

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount']);
    $bank_name = trim($_POST['bank_name']);
    $account_number = trim($_POST['account_number']);
    $description = trim($_POST['description']);

    if ($amount <= 0) {
        $message = "Please enter a valid deposit amount.";
    } elseif ($amount > $dailyLimit) {
        $message = "Deposit exceeds the daily limit of AUD " . number_format($dailyLimit,2);
    } elseif (empty($bank_name) || empty($account_number)) {
        $message = "Please provide bank name and account number.";
    } else {
        // Update user balance
        $newBalance = $user['balance'] + $amount;
        $stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
        $stmt->execute([$newBalance, $user['id']]);

        // Insert transaction
        $stmt = $pdo->prepare("INSERT INTO transactions 
            (user_id, type, amount, description, status, created_at) 
            VALUES (?, 'credit', ?, ?, 'Completed', NOW())");
        $stmt->execute([
            $user['id'], 
            $amount, 
            "Bank Deposit: $bank_name / $account_number - $description"
        ]);

        // Notification
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message, read_status) VALUES (?, ?, 0)");
        $stmt->execute([$user['id'], "Bank deposit of AUD " . number_format($amount,2) . " completed."]);

        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bank Deposit | Southern Cashflow Finance</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f2f5f7; margin:0; }
        .container { max-width:550px; margin:80px auto; background:#fff; padding:35px; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.1); }
        h2 { text-align:center; color:#0b3a6e; margin-bottom:25px; }
        label { display:block; margin-top:15px; font-weight:bold; }
        input, textarea { width:100%; padding:12px; margin-top:6px; border-radius:6px; border:1px solid #ccc; font-size:14px; }
        textarea { resize:none; }
        button { margin-top:20px; width:100%; padding:14px; background:#28a745; color:#fff; border:none; border-radius:8px; font-size:16px; font-weight:bold; cursor:pointer; }
        button:hover { opacity:0.9; }
        .back { display:block; text-align:center; margin-top:20px; text-decoration:none; font-weight:bold; color:#0b3a6e; }
        .error { background:#ffe0e0; padding:10px; border-radius:6px; color:#900; margin-bottom:15px; text-align:center; }
        .note { font-size:13px; color:#555; margin-top:15px; text-align:center; }
    </style>
</head>
<body>
<div class="container">
    <h2>🏦 Bank Transfer Deposit</h2>

    <?php if ($message): ?>
        <div class="error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Deposit Amount (AUD)</label>
        <input type="number" name="amount" step="0.01" required>

        <label>Bank Name</label>
        <input type="text" name="bank_name" required>

        <label>Account Number</label>
        <input type="text" name="account_number" required>

        <label>Description</label>
        <textarea name="description" rows="3" placeholder="Invoice, salary, savings..." required></textarea>

        <button type="submit">Submit Bank Deposit</button>
    </form>

    <p class="note">Maximum daily deposit: AUD <?= number_format($dailyLimit,2) ?></p>
    <a href="deposit.php" class="back">← Back to Deposit Options</a>
</div>
</body>
</html>
