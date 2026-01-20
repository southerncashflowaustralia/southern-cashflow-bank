<?php
session_start();
require_once __DIR__ . "/../config/db.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Fetch transactions
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user['id']]);
$transactions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaction History | Southern Cashflow Finance</title>
    <style>
        body { font-family: Arial; background:#f4f6f8; margin:0; }
        .container { padding:30px; max-width:900px; margin:auto; background:#fff; border-radius:8px; margin-top:50px; box-shadow:0 10px 25px rgba(0,0,0,.1); }
        table { width:100%; border-collapse: collapse; margin-top:20px; }
        th, td { padding:12px; text-align:left; border-bottom:1px solid #ddd; }
        th { background:#0b3a6e; color:#fff; }
        tr:hover { background:#f1f1f1; }
        button { padding:10px 15px; background:#0b3a6e; color:#fff; border:none; border-radius:5px; cursor:pointer; margin-top:20px; }
        button:hover { background:#09508b; }
    </style>
</head>
<body>

<div class="container">
    <h2>Transaction History</h2>
    <p>Available Balance: AUD <?= number_format($user['balance'], 2) ?></p>

    <?php if ($transactions): ?>
        <table>
            <tr>
                <th>Date & Time</th>
                <th>Type</th>
                <th>Description</th>
                <th>Amount (AUD)</th>
            </tr>
            <?php foreach ($transactions as $tx): ?>
            <tr>
                <td><?= $tx['created_at'] ?></td>
                <td><?= ucfirst($tx['type']) ?></td>
                <td><?= htmlspecialchars($tx['description']) ?></td>
                <td><?= number_format($tx['amount'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No transactions found.</p>
    <?php endif; ?>

    <button onclick="location.href='dashboard.php'">Back to Dashboard</button>
</div>

</body>
</html>