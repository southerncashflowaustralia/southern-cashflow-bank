<?php
session_start();
require_once __DIR__ . "/../config/db.php";

// Redirect if not logged in
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

$error = "";
$success = "";

// Handle ATM withdrawal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount']);

    if ($amount <= 0) {
        $error = "Invalid withdrawal amount.";
    } elseif ($amount > $user['balance']) {
        $error = "Insufficient funds.";
    } else {
        try {
            $pdo->beginTransaction();

            // Deduct balance
            $newBalance = $user['balance'] - $amount;
            $stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
            $stmt->execute([$newBalance, $user['id']]);

            // Insert transaction
            $stmt = $pdo->prepare("
                INSERT INTO transactions 
                (user_id, type, description, amount, status, created_at)
                VALUES (?, 'debit', 'ATM Cash Withdrawal', ?, 'Completed', NOW())
            ");
            $stmt->execute([$user['id'], $amount]);

            $pdo->commit();
            header("Location: dashboard.php?withdraw=success");
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Transaction failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>ATM Withdrawal | Southern Cashflow Finance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background:#f2f5f7;
            padding:40px;
        }
        .box {
            max-width:420px;
            margin:auto;
            background:#fff;
            padding:30px;
            border-radius:10px;
            box-shadow:0 4px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align:center;
            color:#0b3a6e;
            margin-bottom:20px;
        }
        input {
            width:100%;
            padding:12px;
            margin-bottom:15px;
            font-size:16px;
            border-radius:6px;
            border:1px solid #ccc;
        }
        button {
            width:100%;
            padding:14px;
            background:#0b3a6e;
            color:#fff;
            border:none;
            border-radius:6px;
            font-size:16px;
            cursor:pointer;
        }
        button:hover {
            background:#094b8a;
        }
        .error {
            background:#f8d7da;
            color:#721c24;
            padding:10px;
            margin-bottom:15px;
            border-radius:5px;
        }
        .balance {
            text-align:center;
            margin-bottom:20px;
            font-weight:bold;
        }
        a {
            display:block;
            text-align:center;
            margin-top:15px;
            text-decoration:none;
            color:#0b3a6e;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>🏧 ATM Withdrawal</h2>

    <div class="balance">
        Available Balance: AUD <?= number_format($user['balance'], 2) ?>
    </div>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="number" name="amount" step="0.01" min="1" placeholder="Enter amount" required>
        <button type="submit">Withdraw Cash</button>
    </form>

    <a href="dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
