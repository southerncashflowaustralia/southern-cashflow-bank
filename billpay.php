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
    <title>Bill Pay | Southern Cashflow Finance</title>
    <style>
        body { font-family: Arial, sans-serif; margin:30px; background:#f4f6f8; }
        h1 { color:#0b3a6e; margin-bottom:20px; }

        label { display:block; margin-top:15px; font-weight:bold; }
        input, select { width:300px; padding:10px; margin-top:5px; border-radius:5px; border:1px solid #ccc; }
        button { margin-top:20px; padding:12px 25px; background:#0b3a6e; color:#fff; border:none; border-radius:5px; cursor:pointer; }
        button:hover { background:#09508b; }
    </style>
</head>
<body>
    <h1>Bill Payment</h1>

    <form method="post">
        <label>Payee Name</label>
        <input type="text" name="payee" placeholder="Electricity / Water / School">

        <label>Account / Reference Number</label>
        <input type="text" name="account_number" placeholder="Enter account number">

        <label>Amount (AUD)</label>
        <input type="number" name="amount" placeholder="0.00" step="0.01">

        <label>Payment Date</label>
        <input type="date" name="pay_date">

        <label>Payment Reference / Reason</label>
        <input type="text" name="reason" placeholder="e.g., Electricity bill, Term Fees">

        <button type="submit">Pay Bill</button>
    </form>
</body>
</html>