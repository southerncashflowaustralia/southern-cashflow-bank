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

// Sample investments
$investments = [
    ['type'=>'Superannuation', 'amount'=>'5,000,000.00', 'status'=>'Active'],
    ['type'=>'Stocks Portfolio', 'amount'=>'2,500,000.00', 'status'=>'Active'],
    ['type'=>'Property Fund', 'amount'=>'10,000,000.00', 'status'=>'Active'],
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Investments | Southern Cashflow Finance</title>
    <style>
        body { font-family: Arial, sans-serif; margin:30px; background:#f4f6f8; }
        h1 { color:#0b3a6e; margin-bottom:20px; }
        table { width:100%; border-collapse: collapse; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.05); }
        th, td { padding:12px; text-align:left; border-bottom:1px solid #ddd; font-size:14px; }
        th { background:#0b3a6e; color:#fff; text-transform:uppercase; font-size:14px; }
        tr:hover { background:#f1f1f1; }
    </style>
</head>
<body>
    <h1>My Investments</h1>

    <table>
        <tr>
            <th>Type</th>
            <th>Amount (AUD)</th>
            <th>Status</th>
        </tr>
        <?php foreach($investments as $inv): ?>
        <tr>
            <td><?= $inv['type'] ?></td>
            <td><?= $inv['amount'] ?></td>
            <td><?= $inv['status'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>