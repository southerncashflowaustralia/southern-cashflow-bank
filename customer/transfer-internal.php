<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Fetch current user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: ../auth/login.php");
    exit;
}

// Fetch other users for selection
$stmt = $pdo->prepare("SELECT id, full_name FROM users WHERE id != ?");
$stmt->execute([$user['id']]);
$otherUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to_user_id   = intval($_POST['to_user_id']);
    $amount       = floatval($_POST['amount']);
    $description  = trim($_POST['description']);

    // Check if user has enough balance
    if ($amount <= 0 || $amount > $user['balance']) {
        $message = "Invalid amount or insufficient funds.";
    } else {

        // Deduct from current user
        $stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
        $stmt->execute([$amount, $user['id']]);

        // Add to recipient
        $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        $stmt->execute([$amount, $to_user_id]);

        // Insert transaction for sender
        $stmt = $pdo->prepare("
            INSERT INTO transactions (user_id, type, amount, description, status, created_at)
            VALUES (?, 'debit', ?, ?, 'Completed', NOW())
        ");
        $stmt->execute([
            $user['id'],
            $amount,
            "Internal Transfer to User ID $to_user_id - $description"
        ]);

        // Insert transaction for recipient
        $stmt = $pdo->prepare("
            INSERT INTO transactions (user_id, type, amount, description, status, created_at)
            VALUES (?, 'credit', ?, ?, 'Completed', NOW())
        ");
        $stmt->execute([
            $to_user_id,
            $amount,
            "Internal Transfer from {$user['full_name']} - $description"
        ]);

        // Notification for recipient
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message, read_status) VALUES (?, ?, 0)");
        $stmt->execute([
            $to_user_id,
            "You received AUD " . number_format($amount,2) . " from {$user['full_name']}."
        ]);

        // Redirect back to dashboard
        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Internal Transfer | Southern Cashflow Finance</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f2f5f7; margin:0; }
        .container { max-width:650px; margin:60px auto; background:#fff; padding:35px; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.1); }
        h2 { text-align:center; color:#0b3a6e; margin-bottom:30px; }
        label { font-weight:bold; display:block; margin-top:15px; }
        select, input, textarea { width:100%; padding:12px; margin-top:6px; border-radius:6px; border:1px solid #ccc; font-size:14px; }
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
    <h2>🔁 Southern Cashflow Finance Transfer</h2>

    <?php if ($message): ?>
        <div class="error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">

        <label>Select Recipient</label>
        <select name="to_user_id" required>
            <option value="">-- Select a user --</option>
            <?php foreach ($otherUsers as $ou): ?>
                <option value="<?= $ou['id'] ?>"><?= htmlspecialchars($ou['full_name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Amount (AUD)</label>
        <input type="number" name="amount" step="0.01" required placeholder="Max: <?= number_format($user['balance'],2) ?>">

        <label>Description</label>
        <textarea name="description" rows="3" placeholder="Payment for services, gifts, etc." required></textarea>

        <button type="submit">Submit Internal Transfer</button>
    </form>

    <p class="note">
        Internal transfers are instant and will reflect immediately in both accounts.
    </p>

    <a href="transfer.php" class="back">← Back to Transfer Options</a>
</div>

</body>
</html>
