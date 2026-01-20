<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$loan_id = $_GET['id'] ?? null;

$stmt = $pdo->prepare("SELECT * FROM loans WHERE id = ? AND user_id = ?");
$stmt->execute([$loan_id, $_SESSION['user_id']]);
$loan = $stmt->fetch();

if (!$loan) {
    die("Loan not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Loan Repayment</title>
<style>
body { font-family:Arial; background:#f4f6f8; }
.box {
    max-width:500px;
    margin:60px auto;
    background:#fff;
    padding:30px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
label { display:block; margin-top:15px; font-weight:bold; }
input {
    width:100%;
    padding:10px;
    margin-top:5px;
}
button {
    margin-top:20px;
    padding:12px;
    width:100%;
    background:#0b3a6e;
    color:#fff;
    border:none;
    border-radius:6px;
    font-size:15px;
}
</style>
</head>
<body>

<div class="box">
<h2>Loan Repayment</h2>

<p><strong>Loan Type:</strong> <?= htmlspecialchars($loan['loan_type']) ?></p>
<p><strong>Outstanding Balance:</strong> AUD <?= number_format($loan['balance'],2) ?></p>

<form>
<label>Repayment Amount (AUD)</label>
<input type="number" value="<?= $loan['repayment_amount'] ?>" disabled>

<label>Payment Method</label>
<input type="text" value="Southern Cashflow Transaction Account" disabled>

<button type="button" onclick="alert('Repayment processing coming soon')">
Confirm Repayment
</button>
</form>

</div>
</body>
</html>