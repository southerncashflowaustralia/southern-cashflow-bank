<?php
session_start();
require_once __DIR__ . "/../config/db.php";

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Fetch user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: ../auth/login.php");
    exit;
}

$message = "";

// 🔹 Daily withdrawal limit (high limit, instant withdrawals)
$dailyLimit = 3000000;

// Get today's total withdrawals
$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(amount), 0) 
    FROM transactions 
    WHERE user_id = ? 
      AND type = 'debit' 
      AND DATE(created_at) = CURDATE()
");
$stmt->execute([$user['id']]);
$todayWithdrawn = (float) $stmt->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $amount = floatval($_POST['amount']);
    $description = trim($_POST['description']);

    if ($amount <= 0) {
        $message = "Invalid withdrawal amount.";
    } elseif (($todayWithdrawn + $amount) > $dailyLimit) {
        $message = "Daily withdrawal limit exceeded. Maximum allowed is AUD " . number_format($dailyLimit, 2) . ".";
    } elseif ($amount > $user['balance']) {
        $message = "Insufficient balance.";
    } else {
        // Insert withdrawal transaction
        $stmt = $pdo->prepare("
            INSERT INTO transactions (user_id, type, amount, description, status, created_at)
            VALUES (?, 'debit', ?, ?, 'Completed', NOW())
        ");
        $stmt->execute([
            $user['id'],
            $amount,
            "ATM Withdrawal - $description"
        ]);

        // Insert notification
        $stmt = $pdo->prepare("
            INSERT INTO notifications (user_id, message, read_status)
            VALUES (?, ?, 0)
        ");
        $stmt->execute([
            $user['id'],
            "You withdrew AUD " . number_format($amount,2) . "."
        ]);

        // Update user balance
        $stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
        $stmt->execute([$amount, $user['id']]);

        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ATM Withdrawal | Southern Cashflow Finance</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f2f5f7; margin:0; }
        .container { max-width:500px; margin:80px auto; background:#fff; padding:35px; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.1); }
        h2 { text-align:center; color:#0b3a6e; margin-bottom:25px; }
        label { display:block; font-weight:bold; margin-top:15px; }
        input, textarea { width:100%; padding:12px; margin-top:6px; border-radius:6px; border:1px solid #ccc; font-size:14px; }
        textarea { resize:none; }
        button { margin-top:25px; width:100%; padding:14px; background:#0b3a6e; color:#fff; border:none; border-radius:8px; font-size:16px; font-weight:bold; cursor:pointer; }
        button:hover { opacity:0.9; }
        .back { display:block; text-align:center; margin-top:20px; text-decoration:none; font-weight:bold; color:#0b3a6e; }
        .note { font-size:13px; color:#555; margin-top:15px; text-align:center; }
        .error { background:#ffe0e0; padding:10px; border-radius:6px; color:#900; margin-bottom:15px; text-align:center; }
    </style>
</head>
<body>

<div class="container">
    <h2>💵 ATM Withdrawal</h2>

    <?php if ($message): ?>
        <div class="error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Withdrawal Amount (AUD)</label>
        <input type="number" name="amount" step="0.01" required>

        <label>Description</label>
        <textarea name="description" rows="3" placeholder="Cash, personal use, etc..." required></textarea>

        <button type="submit">Withdraw Now</button>
    </form>

    <p class="note">
        Daily withdrawal limit: AUD <?= number_format($dailyLimit, 2) ?><br>
        Withdrawn today: AUD <?= number_format($todayWithdrawn, 2) ?><br>
        Available Balance: AUD <?= number_format($user['balance'], 2) ?>
    </p>

    <a href="dashboard.php" class="back">← Back to Dashboard</a>
</div>

</body>
</html>
